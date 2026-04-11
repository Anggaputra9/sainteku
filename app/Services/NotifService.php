<?php

namespace App\Services;

use App\Models\User;
use App\Notifications\GlobalNotification;
use Illuminate\Support\Facades\Notification;

class NotifService
{
    /**
     * Kirim notif ke 1 user spesifik
     */
    public static function sendToUser($userId, array $data)
    {
        $user = User::find($userId);
        if ($user) {
            $user->notify(new GlobalNotification($data));
        }
    }

    /**
     * Kirim notif ke banyak user (array of IDs)
     */
    public static function sendToMany(array $userIds, array $data)
    {
        $users = User::whereIn('id', $userIds)->get();
        if ($users->isNotEmpty()) {
            Notification::send($users, new GlobalNotification($data));
        }
    }

    /**
     * Kirim notif berdasarkan Role dan Unit (Misal: Ke semua Kaprodi di Fakultas X)
     */
    public static function sendToRole($roleName, $unitId, array $data)
    {
        $users = User::whereHas('roles', function ($q) use ($roleName) {
            $q->where('role_name', $roleName);
        })->where('unit_id', $unitId)->get();

        if ($users->isNotEmpty()) {
            Notification::send($users, new GlobalNotification($data));
        }
    }

    /**
     * Kirim notif dinamis ke User berdasarkan KODE Modul dan KODE Permission
     * Contoh: sendToApprovers('RVW_SL', 'A', $unitId, $data)
     */
    public static function sendToApprovers($moduleCode, $permissionCode, $unitId, array $data)
    {
        // 1. Cari Role ID pake Double JOIN biar bisa nyari berdasarkan KODE (String)
        $roleIds = \Illuminate\Support\Facades\DB::table('trx_role_permission')
            ->join('mst_module', 'trx_role_permission.modul_id', '=', 'mst_module.id')
            ->join('ref_permission', 'trx_role_permission.permission_id', '=', 'ref_permission.id')
            ->where('mst_module.module_code', $moduleCode)       // <-- Filter Kode Modul (RVW_SL)
            ->where('ref_permission.permission_code', $permissionCode) // <-- Filter Kode Akses (A)
            ->where('trx_role_permission.allowed', 1)
            ->pluck('trx_role_permission.role_id');

        // Kalau matriksnya belum disetting (kosong), stop aja biar ga error
        if ($roleIds->isEmpty()) {
            return;
        }

        // 2. Tarik data User yang punya Role tersebut di Unit yang sama
        $users = \App\Models\User::whereHas('roles', function ($q) use ($roleIds) {
            $q->whereIn('mst_role.id', $roleIds); // Sesuaikan nama tabel role lu kalau beda
        })
            ->where('unit_id', $unitId)
            ->get();

        // 3. Kirim notifnya
        if ($users->isNotEmpty()) {
            \Illuminate\Support\Facades\Notification::send($users, new \App\Notifications\GlobalNotification($data));
        }
    }
}