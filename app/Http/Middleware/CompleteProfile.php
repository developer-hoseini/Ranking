<?php

namespace App\Http\Middleware;

use Auth;
use Closure;

class CompleteProfile
{
    public function handle($request, Closure $next, $guard = null)
    {
        if (empty(trim(Auth::user()?->profile?->fname))) {

            return redirect()->route('edit_profile')->withErrors([
                'message' => __('message.Please complete your profile first'),
            ]);
        }

        return $next($request);
    }
}
