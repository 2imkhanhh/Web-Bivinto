<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Profile');
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'phone' => ['required', 'string', 'regex:/^0[0-9]{9}$/'],
            'password' => 'nullable|string|min:6|confirmed',
        ], [
            'phone.regex' => 'Số điện thoại phải bao gồm đúng 10 chữ số và bắt đầu bằng số 0.',
            'phone.required' => 'Vui lòng nhập số điện thoại.'
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        if (isset($validated['phone'])) {
            $user->phone = $validated['phone'];
        }

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->back();
    }
}
