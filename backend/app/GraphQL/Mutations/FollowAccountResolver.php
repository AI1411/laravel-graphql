<?php

namespace App\GraphQL\Mutations;

use App\Models\Account;
use App\Models\Follow;
use App\Models\Follower;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class FollowAccountResolver
{
    public function resolve($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {

        /** @var Account $account */
        $account = auth()->user();

        $follow = $this->followAccount($account, $args);

        $this->addToFollowers($account, $args);

        return $follow;
    }

    /**
     * @param Account $account
     * @param array $data
     * @return Follow
     */
    protected function followAccount(Account $account, array $data): Follow
    {
        return Follow::create([
            'account_id' => $account->id,
            'follow_account_id' => $data['id'],
        ]);
    }

    protected function addToFollowers(Account $account, array $data)
    {
        return Follower::create([
            'account_id' => $data['id'],
            'follower_account_id' => $account->id
        ]);
    }
}
