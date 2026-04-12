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

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . $user->getTable() . ',email,' . $user->id],
            'phone_number' => ['nullable', 'string', 'max:20', 'regex:/^[0-9+\-\s]+$/'],
            'signature' => ['nullable', 'string', 'max:100000'],
            'signature_file' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ]);

        // Upload avatar
        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::delete('public/avatars/' . $user->avatar);
            }
            $avatarName = time() . '.' . $request->avatar->extension();
            $request->avatar->storeAs('public/avatars', $avatarName);
            $user->avatar = $avatarName;
        }

        // Upload signature
        if ($request->hasFile('signature_file')) {
            $file = $request->file('signature_file');
            $fileData = base64_encode(file_get_contents($file));
            $mimeType = $file->getMimeType();
            $user->signature = 'data:' . $mimeType . ';base64,' . $fileData;
        } elseif ($request->filled('signature')) {
            $user->signature = $validated['signature'];
        }

        // Update data
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->phone_number = $validated['phone_number'] ?? $user->phone_number;

        if ($user->email !== $validated['email']) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }
    /**
     * Delete the user's account.
     */
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
