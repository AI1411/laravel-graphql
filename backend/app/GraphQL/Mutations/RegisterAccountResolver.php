<?php

namespace App\GraphQL\Mutations;

use App\Models\Account;
use Carbon\Carbon;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Auth\AuthManager;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class RegisterAccountResolver
{
    use RegistersUsers;

    /**
     * @param $rootValue
     * @param array $args
     * @param GraphQLContext $context
     * @param ResolveInfo $resolveInfo
     * @return array
     */
    public function resolve($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        event(new Registered($account = $this->create($args))); // ①
        /** @var \Illuminate\Auth\AuthManager $authManager */
        $authManager = app(AuthManager::class);
        /** @var \Tymon\JWTAuth\JWTGuard $guard */
        $guard = $authManager->guard('api');
        $token = $guard->login($account);
        return [
            'account' => $account,
            'token' => [
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => $guard->factory()->getTTL() * 60,
            ],
        ];
    }

    protected function create(array $data)
    {
        return Account::create([
            'name' => $data['name'],
            'twitter_id' => $data['twitter_id'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'logged_in_at' => Carbon::now(),
            'signed_up_at' => Carbon::now(),
        ]);
    }
}
