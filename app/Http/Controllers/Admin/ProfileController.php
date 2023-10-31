<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use App\Actions\Fortify\UpdateUserPassword;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        return $request->user()->only(['name','email', 'role_name', 'avatar']);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required'],
            'email' => ['required','email', Rule::unique('users')->ignore($request->user()->id)],
        ]);

        $request->user()->update($validated);

        return response()->json(['success' => true]);
    }

    public function uploadImage(Request $request)
    {
        if($request->hasFile('profile_picture')) {

            $previousPath = $request->user()->getRawOriginal('avatar');

            $link = Storage::put('/photos', $request->file('profile_picture'));

            $request->user()->update(['avatar' => $link]); //dd($link);

            Storage::delete($previousPath);

            return response()->json(['message' => 'Profile picture uploaded successfully.']);
        }
    }

    public function changePassword(Request $request, UpdateUserPassword $updater)
    {
        $updater->update(
            auth()->user(),
            [
                'current_password' => $request->currentPassword,
                'password' => $request->password,
                'password_confirmation' => $request->passwordConfirmation,
            ]
        );

        return response()->json(['message' => 'Password changed successfully.']);
    }
}
