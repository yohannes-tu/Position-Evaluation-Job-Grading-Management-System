<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        return $this->redirectForRole($request->user()->role);
    }

    private function redirectForRole(?string $role): RedirectResponse
    {
        $normalizedRole = str_replace(
            [' ', '-'],
            '_',
            strtolower(trim($role ?? 'public_user'))
        );

        return match ($normalizedRole) {
            'admin', 'administrator' => redirect()->route('dashboard'),
            'hr_admin', 'hr_administrator', 'hr_manager' => redirect()->route('hr.dashboard'),
            'evaluator' => redirect()->route('evaluator.dashboard'),
            'committee_member', 'committee' => redirect()->route('committee.dashboard'),
            'recruiter' => redirect()->route('recruitment.dashboard'),
            default => redirect()->route('public.landing'),
        };
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
