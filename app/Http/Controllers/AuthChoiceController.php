<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthChoiceController extends Controller
{
    public function create(): View
    {
        $users = User::query()->orderBy('role')->orderBy('name')->get();

        return view('auth.login', [
            'users' => $users,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id'],
        ]);

        Auth::loginUsingId($validated['user_id']);
        $request->session()->regenerate();

        $user = $request->user();

        if ($user?->role === User::ROLE_DISPATCHER) {
            return redirect()->route('dispatcher.index');
        }

        return redirect()->route('master.index');
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
