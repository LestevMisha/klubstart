<?php
namespace App\Http\Middleware\Pages;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class _Private {

    /* +++++++++++++++++++ HEADER +++++++++++++++++++ */
    public function __construct(
        protected \App\Services\TelegramServices $telegramServices,
    ) {}

    /* +++++++++++++++++++ PUBLIC SECTION +++++++++++++++++++ */
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response {
        $user = auth()->user();

        // telegram auto login system (refer to routes/api.php::/auth/email/link)
        if ($request->routeIs('private.dashboard')) {
            $response = $this->telegramServices->autoLogin($request);
            if ($response) return $next($request);
        }

        if (!$user) return redirect()->route("auth.login");
        // auto logout if user's subscription has expired in the current session
        if (!$user->is_subscribed || !$user->email_verified_at || !$user->password) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }
        if (!$user->is_subscribed) return redirect($this->telegramServices->_getRegisterLink());
        if (!$user->email_verified_at) {
            return redirect()->away($this->telegramServices->__getUnderscoreRestrictedTelegramLink("sign", $user->uuid));
        };
        if (!$user->password) return redirect()->route("public.password.forgot");

        return $next($request);
    }
}
