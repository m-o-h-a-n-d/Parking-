<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class EnsureDbConnection
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            DB::connection()->getPdo();
        } catch (\Exception $e) {
            try {
                DB::reconnect();
            } catch (\Exception $ex) {
                Log::error("DB reconnect failed: " . $ex->getMessage());
                abort(500, 'Database connection failed.');
            }
        }

        return $next($request);
    }
}
