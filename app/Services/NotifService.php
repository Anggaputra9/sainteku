<?php

namespace App\Services;

use App\Models\User;
use App\Notifications\GlobalNotification;
use App\Services\MailService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

/**
 * NotifService
 *
 * Pusat pengiriman notifikasi (in-app + email) untuk seluruh aplikasi.
 *
 * Konvensi struktur $data (sama untuk in-app & email):
 *   [
 *     'action'       => 'mengajukan review untuk',     // verb dari pengirim
 *     'item_name'    => 'UTS Algoritma',               // objek yang dirujuk
 *     'type'         => 'Tashih Soal',                 // kategori notif
 *     'url'          => route(...),                    // tujuan klik
 *     'reference_id' => $proposal->uuid,               // optional
 *     'click_action' => 'open_tashih_modal',           // optional
 *     'status'       => 'online' | 'offline',          // optional, indikator UI
 *     'send_email'   => true | false,                  // optional, default true
 *     'sender_name'  => '...',                         // optional override
 *   ]
 *
 * Konsep pengarahan notifikasi mengikuti pola di MonevAkademik:
 *  - Aksi pengajuan -> sendToApprovers(moduleCode, permissionCode, unitId, data)
 *    Akan menarik semua user yang punya permission tsb di unit yang sama.
 *  - Aksi follow-up ke individu (approve/revisi) -> sendToUser($userId, $data)
 */
class NotifService
{
    /**
     * Kirim notif ke 1 user spesifik (in-app + email).
     */
    public static function sendToUser($userId, array $data): void
    {
        $user = User::find($userId);
        if (!$user) return;

        try {
            $user->notify(new GlobalNotification($data));
        } catch (\Throwable $e) {
            Log::error("NotifService::sendToUser gagal kirim notif in-app: " . $e->getMessage());
        }

        self::dispatchEmails(collect([$user]), $data);
    }

    /**
     * Kirim notif ke banyak user (array of IDs).
     */
    public static function sendToMany(array $userIds, array $data): void
    {
        $users = User::whereIn('id', $userIds)->get();
        if ($users->isEmpty()) return;

        try {
            Notification::send($users, new GlobalNotification($data));
        } catch (\Throwable $e) {
            Log::error("NotifService::sendToMany gagal kirim notif in-app: " . $e->getMessage());
        }

        self::dispatchEmails($users, $data);
    }

    /**
     * Kirim notif berdasarkan Role + Unit (cth: ke semua Kaprodi di Fakultas X).
     */
    public static function sendToRole($roleName, $unitId, array $data): void
    {
        $users = User::whereHas('roles', function ($q) use ($roleName) {
            $q->where('role_name', $roleName);
        })->where('unit_id', $unitId)->get();

        if ($users->isEmpty()) return;

        try {
            Notification::send($users, new GlobalNotification($data));
        } catch (\Throwable $e) {
            Log::error("NotifService::sendToRole gagal kirim notif in-app: " . $e->getMessage());
        }

        self::dispatchEmails($users, $data);
    }

    /**
     * Kirim notif dinamis berdasarkan KODE Modul + KODE Permission (RBAC).
     * Cth: sendToApprovers('RVW_SL', 'A', $unitId, $data)
     *      => kirim ke semua user yang punya permission Approve di modul Review Soal
     *         di unit yang sama dengan pengirim.
     */
    public static function sendToApprovers($moduleCode, $permissionCode, $unitId, array $data): void
    {
        // 1. Cari role yang punya permission tsb di modul tsb
        $roleIds = DB::table('trx_role_permission')
            ->join('mst_module', 'trx_role_permission.modul_id', '=', 'mst_module.id')
            ->join('ref_permission', 'trx_role_permission.permission_id', '=', 'ref_permission.id')
            ->where('mst_module.module_code', $moduleCode)
            ->where('ref_permission.permission_code', $permissionCode)
            ->where('trx_role_permission.allowed', 1)
            ->pluck('trx_role_permission.role_id');

        self::dispatchByRoleIds($roleIds, $unitId, $data);
    }

    /**
     * Sama dengan sendToApprovers tapi pakai ID modul langsung.
     * Berguna untuk modul yang module_code-nya belum tersedia / belum konsisten
     * di seeder (mis. modul Infrastruktur saat ini hanya teridentifikasi
     * lewat ID di tabel trx_role_permission).
     */
    public static function sendToApproversByModuleId($moduleId, $permissionCode, $unitId, array $data): void
    {
        $roleIds = DB::table('trx_role_permission')
            ->join('ref_permission', 'trx_role_permission.permission_id', '=', 'ref_permission.id')
            ->where('trx_role_permission.modul_id', $moduleId)
            ->where('ref_permission.permission_code', $permissionCode)
            ->where('trx_role_permission.allowed', 1)
            ->pluck('trx_role_permission.role_id');

        self::dispatchByRoleIds($roleIds, $unitId, $data);
    }

    /**
     * Helper: kirim notif (in-app + email) ke user-user yang memiliki
     * salah satu role di $roleIds dan berasal dari unit $unitId
     * (unit utama maupun unit tambahan).
     *
     * Kalau $unitId di-passing null/0, akan kirim ke semua user yang
     * punya role tsb tanpa filter unit (cth: notifikasi ke admin level kampus).
     */
    private static function dispatchByRoleIds($roleIds, $unitId, array $data): void
    {
        if (empty($roleIds) || (is_object($roleIds) && method_exists($roleIds, 'isEmpty') && $roleIds->isEmpty())) {
            return;
        }

        $query = User::query()->whereHas('roles', function ($q) use ($roleIds) {
            $q->whereIn('mst_role.id', $roleIds);
        });

        if (!empty($unitId)) {
            $query->where(function ($q) use ($unitId) {
                $q->where('unit_id', $unitId)
                  ->orWhereHas('unitTambahan', function ($qq) use ($unitId) {
                      $qq->where('mst_unit.id', $unitId);
                  });
            });
        }

        // Jangan kirim notif balik ke pengirim sendiri
        if ($authId = auth()->id()) {
            $query->where('id', '!=', $authId);
        }

        $users = $query->get();
        if ($users->isEmpty()) return;

        try {
            Notification::send($users, new GlobalNotification($data));
        } catch (\Throwable $e) {
            Log::error("NotifService dispatchByRoleIds gagal: " . $e->getMessage());
        }

        self::dispatchEmails($users, $data);
    }

    /**
     * Kirim email ke list user. Dilakukan di belakang try/catch supaya
     * kegagalan email TIDAK pernah membatalkan flow utama (pengajuan, dll).
     *
     * Default: kirim email. Set 'send_email' => false di $data untuk skip.
     */
    private static function dispatchEmails($users, array $data): void
    {
        if (array_key_exists('send_email', $data) && $data['send_email'] === false) {
            return;
        }

        $sender = auth()->user()->name ?? ($data['sender_name'] ?? 'Sistem');

        foreach ($users as $user) {
            if (empty($user->email)) continue;

            try {
                MailService::sendNotification($user, array_merge($data, [
                    'sender_name' => $sender,
                ]));
            } catch (\Throwable $e) {
                // Jangan throw — cukup catat di log
                Log::warning("NotifService email ke {$user->email} gagal: " . $e->getMessage());
            }
        }
    }
}
