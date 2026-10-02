<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class EnsureInsightAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $id = $request->session()->get('insight_admin_id');
        if (! is_numeric($id) || ! User::query()->whereKey((int) $id)->exists()) {
            return redirect()->guest(route('admin.login'));
        }

        return $next($request);
    }
}
