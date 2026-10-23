<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();
        
        if (!$user || !in_array($user->role, $roles, true)) {
            // Ghi nhat ky canh bao bao mat (Bat buoc theo Muc 9.2)
            Log::warning('phan_quyen.tu_choi', [
                'user_id' => $user?->id,
                'role' => $user?->role,
                'path' => $request->path(),
                'ip' => $request->ip(),
            ]);
            abort(403, 'Khong du quyen truy cap chuc nang nay.');
        }
        
        return $next($request);
    }
}