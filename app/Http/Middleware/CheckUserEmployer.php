<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Account;

class CheckUserEmployer
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = session('user');

        if (!$user || !isset($user['role']) || $user['role'] != 1) {
            return redirect()->route('login');
        }
        $account = Account::where('id', session('user.id'))->first();
        if($account->status == 'lock') return redirect()->route('admin.alertLockAccount');
        
        return $next($request);
    }
}
