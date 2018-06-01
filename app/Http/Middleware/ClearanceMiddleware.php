<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Enums\Permissions;

class ClearanceMiddleware {
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {        
        if (Auth::user()->hasPermissionTo(Permissions::ADMIN)) {
            return $next($request);
        }

        if ($request->is('courses/create')) {
            if (!Auth::user()->hasPermissionTo(Permissions::COURSE_CREATE)) {
                abort('401');
            } 
            else {
                return $next($request);
            }
        }

        if ($request->is('courses/*/edit')) {
            if (!Auth::user()->hasPermissionTo(Permissions::COURSE_EDIT)) {
                abort('401');
            } 
            else {
                return $next($request);
            }
        }

        if ($request->is('courses/*') && $request->isMethod('Delete')) {
            if (!Auth::user()->hasPermissionTo(Permissions::COURSE_DELETE)) {
                abort('401');
            } 
            else{
                return $next($request);
            }
        }

        if ($request->is('modules/create')) {
            if (!Auth::user()->hasPermissionTo(Permissions::MODULE_CREATE)) {
                abort('401');
            } 
            else {
                return $next($request);
            }
        }

        if ($request->is('modules/*/edit')) {
            if (!Auth::user()->hasPermissionTo(Permissions::MODULE_EDIT)) {
                abort('401');
            } 
            else {
                return $next($request);
            }
        }

        if ($request->is('modules/*') && $request->isMethod('Delete')) {
            if (!Auth::user()->hasPermissionTo(Permissions::MODULE_DELETE)) {
                abort('401');
            } 
            else{
                return $next($request);
            }
        }

        return $next($request);
    }
}