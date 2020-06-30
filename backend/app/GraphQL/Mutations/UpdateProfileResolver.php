<?php

namespace App\GraphQL\Mutations;

use App\Models\Account;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class UpdateProfileResolver
{
    public function resolve($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        /** @var Account $account */
        $account = auth()->user();

        return $this->updateAccount($account, $args);
    }

    /**
     * @param Account $account
     * @param array $args
     * @return Account
     */
    protected function updateAccount(Account $account, array $args)
    {
        $account->name = $args['name'] ?? $account->name;

        if (isset($args['avatar'])) {
            $exploded = explode(';base64', $args['avatar']);
            $imageType = explode('image/', $exploded[0])[1];
            $imageName = Str::random() . ".($imageType)";

            Storage::put('public/images' . $imageName, base64_decode($exploded[1]));

            $account->avatar = $imageName;
        }

        $account->save();

        return $account;
    }
}
