<?php

namespace App\Http\Controllers\Pages\Auth;

use App\Http\Controllers\RateLimiterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends RateLimiterController {

    /* +++++++++++++++++++ HEADER +++++++++++++++++++ */
    public function __construct(
        protected \App\Services\RedisServices $redisServices,
        protected \App\Services\Partials\_PartialServices $respond,
    ) {}

    /* +++++++++++++++++++ PUBLIC SECTION +++++++++++++++++++ */
    /**
     * Handle an authentication attempt.
     */
    public function authenticate(Request $request) {
        // 1. Rate limiting (up to 5 attempts)
        $throttleKey = $this->generateThrottleKey("authenticate", "email", $request);
        $executed = $this->rateLimiter($throttleKey, "email", 5, 300);
        if ($executed) return $executed;

        // 2. Validation
        $remember = $request->get("remember-me", false);
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email'],
            'password' => ['required', 'min:8'],
        ]);
        if ($validator->fails()) return $this->respond->renderValidatorErrors("partials._input-error-message", $validator);

        // 3. Login attempt
        $guestId = session()->getId();
        if (Auth::attempt($validator->validated(), $remember)) {

            // 4. Check if user can access the dashboard
            $user = \App\Models\User::where('email', $request->input('email'))->first();
            if (!$user->is_subscribed || $user->days_left <= 0 || !$user->email_verified_at) {
                return $this->respond->renderErrors(["email" => __("login.invalid_credentials"), "password" => ""], "partials._input-error-message");
            }

            // Transfer guest preferences
            $this->redisServices->transferGuestPreferencesToUser($user->uuid, $guestId);

            $request->session()->regenerate();

            // Clear any existing rate limiting blocks
            $this->clearRateLimit($throttleKey);
            return redirect()->back();
        }
        // Default authentication error
        return $this->respond->renderErrors(["email" => __("login.invalid_credentials"), "password" => ""], "partials._input-error-message");
    }

    /* +++++++++++++++++++ INITIALIZATION +++++++++++++++++++ */
    public function __invoke() {
        return view("pages.auth.login.bundled");
    }
}
