<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class JWTMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $authHeader = $request->header('Authorization');


        if (!$authHeader || !preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
            return response()->json(['error' => 'Token não encontrado no cabeçalho Authorization'], 401);
        }

        $jwt = $matches[1];

        //dd($jwt);

        $key = new \Firebase\JWT\Key(env('JWT_SECRET'), 'HS256');


        try {
            $payload = \Firebase\JWT\JWT::decode($jwt, $key);
        } catch (Exception $e) {
            return response()->json(['error' => 'Token inválido'], 401);
        }
        //dd($payload);


        $userId = $payload->user_id;

        //$
        $user = \App\Models\UserSistema::find($userId);


        //dd($user);

        if (!$user) {
            return response()->json(['error' => 'Usuário não encontrado'], 401);
        }

        return $next($request);
    }
}
