<?php

namespace App\GraphQL\Mutations;

use App\Models\Account;
use App\Models\Follow;
use App\Models\Follower;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class UnFollowAccountResolver
{
    /**
     * @param $rootValue
     * @param array $args
     * @param GraphQLContext $context
     * @param ResolveInfo $resolveInfo
     * @return bool
     */
    public function resolve($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        /** @var Account $account */
        $account = auth()->user();

        if (!$this->unFollowAccount($account, $args)) {
            return false;
        }

        if (!$this->removeFromFollowers($account, $args)) {
            return false;
        }

        return true;
    }

    /**
     * @param Account $account
     * @param array $data
     * @return bool null
     */
    protected function unFollowAccount(Account $account, array $data)
    {
        return Follow::where([
            'account_id' => $account->id,
            'follow_account_id' => $data['id']
        ])->delete();
    }

    /**
     * @param Account $account
     * @param array $data
     * @return bool null
     */
    protected function removeFromFollowers(Account $account, array $data)
    {
        return Follower::where([
            'account_id' => $data['id'],
            'follower_account_id' => $account->id,
        ])->delete();
    }
}
