<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\AiSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AiSettingController extends Controller
{
    private function guardAdmin(): void
    {
        abort_unless(
            Auth::user()?->roles()->where('role_code', 'ADM')->exists(),
            403,
            'Hanya administrator yang boleh mengakses pengaturan AI.'
        );
    }

    public function index(Request $request)
    {
        $this->guardAdmin();

        $settings = AiSetting::query()
            ->when($request->filled('search'), function ($q) use ($request) {
                $s = $request->string('search');
                $q->where(function ($qq) use ($s) {
                    $qq->where('name', 'like', "%{$s}%")
                       ->orWhere('provider', 'like', "%{$s}%")
                       ->orWhere('model', 'like', "%{$s}%");
                });
            })
            ->when($request->filled('provider'), fn ($q) => $q->where('provider', $request->provider))
            ->when($request->filled('status'), fn ($q) => $q->where('is_active', $request->status === 'active'))
            ->orderByDesc('is_default')
            ->orderBy('priority')
            ->orderBy('id')
            ->paginate(10)
            ->withQueryString();

        $providers = AiSetting::providers();

        if ($request->expectsJson()) {
            return response()->json($settings);
        }

        return view('settings.ai.index', compact('settings', 'providers'))
            ->with('title', 'Pengaturan AI');
    }

    public function store(Request $request)
    {
        $this->guardAdmin();
        $data = $this->validated($request);

        DB::transaction(function () use ($data) {
            if (!empty($data['is_default'])) {
                AiSetting::query()->update(['is_default' => false]);
            }
            AiSetting::create($data);
        });

        return back()->with('success', 'Konfigurasi AI berhasil ditambahkan.');
    }

    public function update(Request $request, AiSetting $aiSetting)
    {
        $this->guardAdmin();
        $data = $this->validated($request, $aiSetting->id);

        // Jika field api_key kosong, jangan timpa nilai lama
        if (empty($data['api_key'])) {
            unset($data['api_key']);
        }

        DB::transaction(function () use ($data, $aiSetting) {
            if (!empty($data['is_default'])) {
                AiSetting::where('id', '!=', $aiSetting->id)->update(['is_default' => false]);
            }
            $aiSetting->update($data);
        });

        return back()->with('success', 'Konfigurasi AI berhasil diperbarui.');
    }

    public function destroy(AiSetting $aiSetting)
    {
        $this->guardAdmin();
        $aiSetting->delete();
        return back()->with('success', 'Konfigurasi AI dihapus.');
    }

    public function setDefault(AiSetting $aiSetting)
    {
        $this->guardAdmin();
        DB::transaction(function () use ($aiSetting) {
            AiSetting::query()->update(['is_default' => false]);
            $aiSetting->update(['is_default' => true, 'is_active' => true]);
        });
        return back()->with('success', "[{$aiSetting->name}] dijadikan default.");
    }

    /**
     * Test AI connection dengan prompt sederhana.
     */
    public function test(Request $request, AiSetting $aiSetting)
    {
        $this->guardAdmin();
        $request->validate(['prompt' => 'nullable|string|max:500']);

        $prompt = $request->input('prompt', 'Respond with: Connection successful');

        try {
            $aiService = app(\App\Services\AiService::class);
            $result = $aiService->sendPrompt($prompt, $aiSetting, ['max_tokens' => 50]);

            if ($result['success']) {
                $message = "Test koneksi berhasil! Provider: {$aiSetting->provider}, Model: {$aiSetting->model}";
                if ($result['tokens'] > 0) {
                    $message .= " | Tokens: {$result['tokens']}";
                }
                if ($result['cost'] > 0) {
                    $message .= " | Cost: $" . number_format($result['cost'], 4);
                }
                return back()->with('success', $message);
            } else {
                return back()->with('error', 'Test koneksi gagal: ' . $result['error']);
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Test koneksi gagal: ' . $e->getMessage());
        }
    }

    /**
     * Reset daily usage counter.
     */
    public function resetUsage(AiSetting $aiSetting)
    {
        $this->guardAdmin();
        $aiSetting->update([
            'daily_used' => 0,
            'last_reset_date' => now()->toDateString(),
        ]);
        return back()->with('success', 'Counter usage berhasil direset.');
    }

    private function validated(Request $request, ?int $id = null): array
    {
        $providers = AiSetting::providers();

        $rules = [
            'name'                => 'required|string|max:100',
            'provider'            => 'required|string|in:' . implode(',', array_keys($providers)),
            'api_key'             => $id ? 'nullable|string' : 'required|string',
            'api_endpoint'        => 'nullable|url|max:255',
            'model'               => 'required|string|max:100',
            'temperature'         => 'nullable|numeric|min:0|max:2',
            'max_tokens'          => 'nullable|integer|min:1|max:100000',
            'top_p'               => 'nullable|numeric|min:0|max:1',
            'frequency_penalty'   => 'nullable|integer|min:-2|max:2',
            'presence_penalty'    => 'nullable|integer|min:-2|max:2',
            'daily_limit'         => 'nullable|integer|min:0',
            'cost_per_1k_tokens'  => 'nullable|numeric|min:0',
            'priority'            => 'nullable|integer|min:0',
            'notes'               => 'nullable|string|max:1000',
            'is_active'           => 'nullable|boolean',
            'is_default'          => 'nullable|boolean',
        ];

        $data = $request->validate($rules);

        // Set defaults
        $data['is_active']          = $request->boolean('is_active');
        $data['is_default']         = $request->boolean('is_default');
        $data['temperature']        = $data['temperature'] ?? 0.7;
        $data['max_tokens']         = $data['max_tokens'] ?? 2000;
        $data['top_p']              = $data['top_p'] ?? 1.0;
        $data['frequency_penalty']  = $data['frequency_penalty'] ?? 0;
        $data['presence_penalty']   = $data['presence_penalty'] ?? 0;
        $data['daily_limit']        = $data['daily_limit'] ?? 0;
        $data['cost_per_1k_tokens'] = $data['cost_per_1k_tokens'] ?? 0;
        $data['priority']           = $data['priority'] ?? 0;

        // Set default endpoint jika kosong
        $providerKey = $data['provider'];
        if (empty($data['api_endpoint']) && isset($providers[$providerKey]['endpoint'])) {
            $data['api_endpoint'] = $providers[$providerKey]['endpoint'];
        }

        return $data;
    }
}
