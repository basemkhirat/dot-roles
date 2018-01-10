<?php

namespace Dot\Roles\Middlewares;

use Closure;
use Dot\Platform\Facades\Dot;
use Gate;

class PermissionMiddleware
{

    /*
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param  string|null $permission
     * @return mixed
     */
    public function handle($request, Closure $next, $permission = null)
    {

        if (!Gate::allows($permission)) {

            if ($request->is(API . "/*")) {

                return $next($request);

                $response = new DotResponse();
                return $response->json(NULL, "Authorization error", 403);

            } else {
                Dot::forbidden();
            }
        }

        return $next($request);
    }
}
