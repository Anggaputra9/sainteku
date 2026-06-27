<?php

namespace App\Support;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class AuthRateLimiter
{
    private const LOGIN_MAX_ATTEMPTS = 5;

    private const LOGIN_DECAY_SECONDS = 300;

    private const LOGIN_IP_MAX_ATTEMPTS = 20;

    private const LOGIN_IP_DECAY_SECONDS = 900;

    private const FORGOT_PASSWORD_MAX_ATTEMPTS = 3;

    private const FORGOT_PASSWORD_DECAY_SECONDS = 1800;

    /**
     * @return array{message: string, retry_after: int, status: int}|null
     */
    public static function ensureLoginAllowed(Request $request, string $credential): ?array
    {
        $ipKey = self::loginIpKey($request);

        if (RateLimiter::tooManyAttempts($ipKey, self::LOGIN_IP_MAX_ATTEMPTS)) {
            return self::lockoutResponse(
                $request,
                $ipKey,
                __('messages.login_ip_throttle'),
            );
        }

        $key = self::loginKey($request, $credential);

        if (RateLimiter::tooManyAttempts($key, self::LOGIN_MAX_ATTEMPTS)) {
            return self::lockoutResponse(
                $request,
                $key,
                __('messages.login_throttle'),
            );
        }

        return null;
    }

    public static function recordLoginFailure(Request $request, string $credential): void
    {
        RateLimiter::hit(self::loginKey($request, $credential), self::LOGIN_DECAY_SECONDS);
        RateLimiter::hit(self::loginIpKey($request), self::LOGIN_IP_DECAY_SECONDS);
    }

    public static function clearLoginSuccess(Request $request, string $credential): void
    {
        RateLimiter::clear(self::loginKey($request, $credential));
    }

    /**
     * @return array{message: string, retry_after: int, status: int}|null
     */
    public static function ensureForgotPasswordAllowed(Request $request, string $credential): ?array
    {
        $key = self::forgotPasswordKey($request, $credential);

        if (RateLimiter::tooManyAttempts($key, self::FORGOT_PASSWORD_MAX_ATTEMPTS)) {
            return self::lockoutResponse(
                $request,
                $key,
                __('messages.forgot_password_throttle'),
            );
        }

        return null;
    }

    public static function recordForgotPasswordAttempt(Request $request, string $credential): void
    {
        RateLimiter::hit(self::forgotPasswordKey($request, $credential), self::FORGOT_PASSWORD_DECAY_SECONDS);
    }

    private static function loginKey(Request $request, string $credential): string
    {
        return 'login|'.Str::transliterate(Str::lower(trim($credential))).'|'.$request->ip();
    }

    private static function loginIpKey(Request $request): string
    {
        return 'login-ip|'.$request->ip();
    }

    private static function forgotPasswordKey(Request $request, string $credential): string
    {
        return 'forgot-password|'.Str::transliterate(Str::lower(trim($credential))).'|'.$request->ip();
    }

    /**
     * @return array{message: string, retry_after: int, status: int}
     */
    private static function lockoutResponse(Request $request, string $key, string $messageTemplate): array
    {
        event(new Lockout($request));

        $seconds = RateLimiter::availableIn($key);
        $minutes = max(1, (int) ceil($seconds / 60));

        return [
            'message' => str_replace(':minutes', (string) $minutes, $messageTemplate),
            'retry_after' => $seconds,
            'status' => 429,
        ];
    }
}