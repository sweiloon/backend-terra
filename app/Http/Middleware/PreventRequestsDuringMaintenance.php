<?php

namespace App\Http\Middleware;

use App\Helpers\APIHelper;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance as Middleware;

class PreventRequestsDuringMaintenance extends Middleware
{

    public function handle($request, Closure $next) {

      if($this->app->isDownForMaintenance()) {

        if($request->is('maintenance')) {
          return $next($request);
        }else if($request->is('api/*')) {
          return APIHelper::returnJSON(false, 503, "Service Unavailable");
        }else{
          return new RedirectResponse(route('maintenance'));
        }

      }else{

        if($request->is('maintenance')) {
          return new RedirectResponse(url('/'));
        }
        return $next($request);

      }

    }

    /**
     * The URIs that should be reachable while maintenance mode is enabled.
     *
     * @var array<int, string>
     */
    protected $except = [
        //
    ];
}
