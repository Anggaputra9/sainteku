<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function updatePassword(Request $request)
    {
        // Validasi khusus buat ganti password di profil (pakai error bag 'updatePassword')
        $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'min:8', 'confirmed'],
        ]);

        // Update password user yang lagi login
        $user = $request->user();
        $user->password = Hash::make($request->password);
        $user->save();

        return back()->with('status', 'password-updated');
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        // Validasi ditambahin untuk nangkep kolom baru dari form modal edit
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . $user->getTable() . ',email,' . $user->id],
            'phone_number' => ['nullable', 'string', 'max:20', 'regex:/^[0-9+\-\s]+$/'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'address' => ['nullable', 'string', 'max:500'],
            'gender' => ['nullable', 'in:L,P'],
            'birth_date' => ['nullable', 'date'],
            'signature' => ['nullable', 'string', 'max:100000'],
            'signature_file' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ]);

        // Upload avatar
        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete('avatars/' . $user->avatar);
            }
            $avatarName = time() . '.' . $request->avatar->extension();
            $request->avatar->storeAs('avatars', $avatarName, 'public');
            $user->avatar = $avatarName;
        }

        // Upload signature file
        if ($request->hasFile('signature_file')) {
            $file = $request->file('signature_file');
            $fileData = base64_encode(file_get_contents($file));
            $mimeType = $file->getMimeType();
            $user->signature = 'data:' . $mimeType . ';base64,' . $fileData;
        } elseif ($request->filled('signature')) {
            $user->signature = $validated['signature'];
        }

        // Update data utama dan data tambahan
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->phone_number = $validated['phone_number'] ?? $user->phone_number;
        $user->bio = $validated['bio'] ?? null;
        $user->address = $validated['address'] ?? null;
        $user->gender = $validated['gender'] ?? null;
        $user->birth_date = $validated['birth_date'] ?? null;

        // Reset verifikasi email jika email diubah
        if ($user->email !== $validated['email']) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
