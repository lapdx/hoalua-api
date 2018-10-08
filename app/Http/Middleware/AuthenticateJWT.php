<?php
/**
 * User: minhpv
 * Date: 5/27/18
 * Time: 9:02 AM
 */

namespace App\Http\Middleware;

use App\Models\ChiUser;
use Closure;
use \Firebase\JWT\JWT;

class AuthenticateJWT
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            return $next($request);
        }
        if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])){

            $username = $_SERVER['PHP_AUTH_USER'];
            $password = $_SERVER['PHP_AUTH_PW'];
            if ($username == 'api' && $password == 'tranduong@237'){
                return $next($request);
            }
        }
        return response('Unauthorized.', 401,["WWW-Authenticate"=>"Basic realm='hoalua'"]);
//        return $next($request);
    }
}
