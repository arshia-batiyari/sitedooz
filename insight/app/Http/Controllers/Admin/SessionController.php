<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminLoginRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class SessionController extends Controller
{
    public function create(): View
    {
        return view('admin.login');
    }

    public function store(AdminLoginRequest $request): RedirectResponse
    {
        $user = User::query()->where('email', $request->string('email')->toString())->first();
        if ($user === null || ! Hash::check($request->string('password')->toString(), $user->password)) {
            throw ValidationException::withMessages([
                'email' => 'اطلاعات ورود درست نیست.',
            ]);
        }

        $request->session()->regenerate();
        $request->session()->put('insight_admin_id', $user->id);

        return redirect()->route('admin.audits.index');
    }

    public function destroy(): RedirectResponse
    {
        request()->session()->forget('insight_admin_id');
        request()->session()->regenerate();

        return redirect()->route('admin.login');
    }
}
