<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LocaleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $locale = null;
        if(Auth::check() and !Session::has('locale')){
            $locale = $request->user()->locale;
            // or $locale = Auth()->user()->locale;
            Session::put('locale' , $locale);
        }

        if($request->has('locale')){
            $locale = $request->get('locale');
            Session::put('locale' , $locale);
        }

        $locale = Session::get('locale');

        if(null === $locale){
            $locale = config('app.fallback_locale');
        }
        App::setLocale($locale);
        return $next($request);
    }
}
