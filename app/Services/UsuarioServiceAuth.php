<?php

namespace App\Services;

use App\Models\UserSistema;
use App\Models\UsuarioAcesso;

class UsuarioServiceAuth
{
    public static function authenticateUser($request)
    {

        $authHeader = $request->header('Authorization');

        if (!$authHeader || !preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
            return response()->json(['error' => 'Token não encontrado no cabeçalho Authorization'], 401);
        }

        $jwt = $matches[1];
        $key = new \Firebase\JWT\Key(env('JWT_SECRET'), 'HS256');

        try {
            $payload = \Firebase\JWT\JWT::decode($jwt, $key);
        } catch (ExpiredException $e) {
            return response()->json(['error' => 'Token expirado'], 401);
        } catch (Exception $e) {
            return response()->json(['error' => 'Token inválido'], 401);
        }

        $userId = $payload->user_id;
        $user = UserSistema::find($userId);

        if (!$user) {
            return response()->json(['error' => 'Usuário não encontrado'], 401);
        }

        return $user;
    }
}
