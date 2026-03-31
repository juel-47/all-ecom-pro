<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EmployeeAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        $employee = $user->employee; 
        if (!$employee || $employee->role_name != 'employee') {
            abort(403, 'Unauthorized');
        }
        $request->attributes->add(['employee_id' => $employee->id]);

        return $next($request);
    }
}
