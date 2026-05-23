<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmailSetting;
use App\Services\EmailSenderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EmailSettingController extends Controller
{
    private function guardAdmin(): void
    {
        abort_unless(
            Auth::user()?->roles()->where('role_code', 'ADM')->exists(),
            403,
            'Hanya administrator yang boleh mengakses pengaturan email.'
        );
    }

    public function index(Request $request)
    {
        $this->guardAdmin();

        $settings = EmailSetting::query()
            ->when($request->filled('search'), function ($q) use ($request) {
                $s = $request->string('search');
                $q->where(function ($qq) use ($s) {
                    $qq->where('name', 'like', "%{$s}%")
                       ->orWhere('from_email', 'like', "%{$s}%")
                       ->orWhere('provider', 'like', "%{$s}%");
                });
            })
            ->when($request->filled('provider'), fn ($q) => $q->where('provider', $request->provider))
            ->when($request->filled('status'), fn ($q) => $q->where('is_active', $request->status === 'active'))
            ->orderByDesc('is_default')
            ->orderBy('priority')
            ->orderBy('id')
            ->paginate(10)
            ->withQueryString();

        $providers = EmailSetting::providers();

        if ($request->expectsJson()) {
            return response()->json($settings);
        }

        return view('settings.email.index', compact('settings', 'providers'))
            ->with('title', 'Pengaturan Email');
    }

    public function store(Request $request)
    {
        $this->guardAdmin();
        $data = $this->validated($request);

        DB::transaction(function () use ($data) {
            if (!empty($data['is_default'])) {
                EmailSetting::query()->update(['is_default' => false]);
            }
            EmailSetting::create($data);
        });

        return back()->with('success', 'Konfigurasi email berhasil ditambahkan.');
    }

    public function update(Request $request, EmailSetting $emailSetting)
    {
        $this->guardAdmin();
        $data = $this->validated($request, $emailSetting->id);

        // Jika field password / api_key kosong, jangan timpa nilai lama
        foreach (['password', 'api_key', 'api_secret'] as $secret) {
            if (empty($data[$secret])) {
                unset($data[$secret]);
            }
        }

        DB::transaction(function () use ($data, $emailSetting) {
            if (!empty($data['is_default'])) {
                EmailSetting::where('id', '!=', $emailSetting->id)->update(['is_default' => false]);
            }
            $emailSetting->update($data);
        });

        return back()->with('success', 'Konfigurasi email berhasil diperbarui.');
    }

    public function destroy(EmailSetting $emailSetting)
    {
        $this->guardAdmin();
        $emailSetting->delete();
        return back()->with('success', 'Konfigurasi email dihapus.');
    }

    public function setDefault(EmailSetting $emailSetting)
    {
        $this->guardAdmin();
        DB::transaction(function () use ($emailSetting) {
            EmailSetting::query()->update(['is_default' => false]);
            $emailSetting->update(['is_default' => true, 'is_active' => true]);
        });
        return back()->with('success', "[{$emailSetting->name}] dijadikan default.");
    }

    public function test(Request $request, EmailSetting $emailSetting)
    {
        $this->guardAdmin();
        $request->validate(['to' => 'required|email']);

        $result = EmailSenderService::sendTest($emailSetting, $request->input('to'));

        return back()->with($result['success'] ? 'success' : 'error', $result['message']);
    }

    private function validated(Request $request, ?int $id = null): array
    {
        $providers = EmailSetting::providers();
        $providerKey = $request->input('provider');
        $authMode = $request->input('auth_mode', 'smtp');

        // Validasi auth_mode harus didukung provider
        $supportedModes = $providers[$providerKey]['auth_modes'] ?? ['smtp'];
        if (!in_array($authMode, $supportedModes, true)) {
            $authMode = $supportedModes[0];
        }

        $rules = [
            'name'        => 'required|string|max:100',
            'provider'    => 'required|string|in:' . implode(',', array_keys($providers)),
            'auth_mode'   => 'required|in:smtp,api',
            'from_email'  => 'nullable|email|max:150',
            'from_name'   => 'nullable|string|max:150',
            'daily_limit' => 'nullable|integer|min:0',
            'priority'    => 'nullable|integer|min:0',
            'notes'       => 'nullable|string|max:1000',
            'is_active'   => 'nullable|boolean',
            'is_default'  => 'nullable|boolean',
        ];

        // Aturan field tergantung mode auth
        if ($authMode === 'smtp') {
            $rules += [
                'host'       => 'required|string|max:150',
                'port'       => 'required|integer|min:1|max:65535',
                'username'   => 'nullable|string|max:200',
                'password'   => $id ? 'nullable|string' : 'required|string',
                'encryption' => 'nullable|in:tls,ssl',
            ];
        } else { // api
            $rules += [
                'api_key'    => $id ? 'nullable|string' : 'required|string',
                'api_domain' => $providerKey === 'mailgun' ? 'required|string|max:150' : 'nullable|string|max:150',
                'api_secret' => 'nullable|string',
            ];
        }

        $data = $request->validate($rules);
        $data['auth_mode']   = $authMode;
        $data['is_active']   = $request->boolean('is_active');
        $data['is_default']  = $request->boolean('is_default');
        $data['daily_limit'] = $data['daily_limit'] ?? 0;
        $data['priority']    = $data['priority'] ?? 0;

        // Bersihkan field yang tidak relevan dengan mode terpilih
        if ($authMode === 'smtp') {
            $data['api_key']    = null;
            $data['api_domain'] = null;
            $data['api_secret'] = null;
        } else {
            $data['host']       = null;
            $data['port']       = null;
            $data['username']   = null;
            $data['password']   = null;
            $data['encryption'] = null;
        }

        return $data;
    }
}
