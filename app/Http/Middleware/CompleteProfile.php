<?php

namespace App\Http\Middleware;

use Auth;
use Closure;

class CompleteProfile
{
    public function handle($request, Closure $next, $guard = null)
    {
        if (empty(trim(Auth::user()?->isProfileCompleted))) {

            return redirect()->route('profile.complete-profile', ['callback' => $request->getRequestUri()])->withErrors([
                'message' => __('message.Please complete your profile first'),
            ]);
        }

        return $next($request);
    }
}
