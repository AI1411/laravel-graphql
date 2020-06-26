<?php

namespace App\GraphQL\Mutations;

use Carbon\Carbon;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Auth\AuthManager;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class LoginResolver
{
    public function resolve($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $authManager = app(AuthManager::class);

        $guard = $authManager->guard('api');
        $token = $guard->attempt([
            'email' => $args['email'],
            'password' => $args['password']
        ]);

        if ($token) {
            $account = auth()->user();
            $account->logged_in_at = Carbon::now();
            $account->save();
        }

        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $guard->factory()->getTTL()  * 60
        ];
    }
}
