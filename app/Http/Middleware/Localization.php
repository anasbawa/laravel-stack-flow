<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;

class Localization
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
        $default = config('app.name');
        // the language depends on brower's lang
        $accept_language = $request->header('accept-language');
        if ($accept_language) {
            // explode return many items and the first itrms will stored in $default variable
            list($default) = explode(',', $accept_language);
        }

        // try to get lan from the URL, if not exist then use the $default
        $lang = $request->route('lang', $default);

        App::setLocale($lang);

        URL::defaults([ // when using {lang} as paremeter set the default value for parameter
            'lang' => $lang,
        ]);
        Route::current()->forgetParameter('lang'); // لعدم تمرير البارميتر للكونترولر

        return $next($request);
    }
}
