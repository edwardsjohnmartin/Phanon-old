<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class ClearanceMiddleware {
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {        
        if (Auth::user()->hasPermissionTo('Administer roles & permissions')) {
            return $next($request);
        }

        if ($request->is('courses/create')) {
            if (!Auth::user()->hasPermissionTo('Create course')) {
                abort('401');
            } 
            else {
                return $next($request);
            }
        }

        if ($request->is('courses/*/edit')) {
            if (!Auth::user()->hasPermissionTo('Edit course')) {
                abort('401');
            } 
            else {
                return $next($request);
            }
        }

        if ($request->is('courses/*') && $request->isMethod('Delete')) {
            if (!Auth::user()->hasPermissionTo('Delete course')) {
                abort('401');
            } 
            else{
                return $next($request);
            }
        }

        if ($request->is('modules/create')) {
            if (!Auth::user()->hasPermissionTo('Create module')) {
                abort('401');
            } 
            else {
                return $next($request);
            }
        }

        if ($request->is('modules/*/edit')) {
            if (!Auth::user()->hasPermissionTo('Edit module')) {
                abort('401');
            } 
            else {
                return $next($request);
            }
        }

        if ($request->is('modules/*') && $request->isMethod('Delete')) {
            if (!Auth::user()->hasPermissionTo('Delete module')) {
                abort('401');
            } 
            else{
                return $next($request);
            }
        }

        return $next($request);
    }
}