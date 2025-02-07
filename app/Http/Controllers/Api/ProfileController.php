<?php

namespace App\Http\Controllers\Api;

use Session;
use Storage;
use Response;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    //profile
    public function profile(Request $request)
    {
        $user = User::findOrFail($request->user()->id);

        return Response::json([
            'message' => 'success',
            'user' => $user
        ], 200);
    }

    // store
    public function update(Request $request)
    {
        $fields = $request->validate([
            'name' => 'required|string',
            'phone' => 'nullable|string|max:50',
            'address' => "nullable|string|max:100",
            'avator' => "nullable|image|mimes:png,jpg"
        ]);

        if ($request->hasFile('avator')) {
            if ($request->user()->avator) {
                Storage::disk('public')->delete($request->user()->avator);
            }
            $fields['avator'] = $request->file('avator')->store('avator', 'public');
        }

        $request->user()->update($fields);

        return response()->json([
            'message' => 'success',
            'user' => $request->user(),
        ], 200);
    }

    // password-change
    public function password_change(Request $request)
    {
        $fields = $request->validate([
            'email' => 'required|email|unique:users,email,' . request()->user()->id,
            "old_password" => 'required',
            "password" => 'required|confirmed',
            "password_confirmation" => 'required|same:password',
        ]);

        $user = User::where('email', $fields['email'])->first();

        if (!Hash::check($fields['old_password'], $user->password)) {
            return response()->json([
                'message' => 'old password do not match !'
            ]);
        }

        if ($fields['old_password'] === $fields['password']) {
            return response()->json([
                'message' => 'new password must be different with the old password!'
            ]);
        }

        $request->user()->update([
            'password' => $fields['password']
        ]);

        return response()->json([
            'message'=>'success',
        ]);

    }


}
