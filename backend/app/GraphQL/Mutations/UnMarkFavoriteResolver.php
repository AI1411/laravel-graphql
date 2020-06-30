<?php

namespace App\GraphQL\Mutations;

use App\Models\Account;
use App\Models\Favorite;
use App\Models\Timeline;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class UnMarkFavoriteResolver
{
    public function resolve($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        /** @var Account $account */
        $account = auth()->user();

        $this->updateTimelineFavoriteId($account, $args['timeline_id']);

        return $this->deleteFavorite($account, $args['tweet_id']);
    }

    /**
     * @param Account $account
     * @param $timelineId
     * @return bool
     */
    protected function updateTimelineFavoriteId(Account $account, $timelineId)
    {
        return Timeline::where([
            'id' => $timelineId,
            'account_id' => $account->id,
        ])->update(['favorite_id' => null]);
    }

    /**
     * @param Account $account
     * @param $tweetId
     * @return bool null
     */
    protected function deleteFavorite(Account $account, $tweetId)
    {
        return Favorite::where([
            'tweet_id'=> $tweetId,
            'account_id' => $account->id,
        ])->delete();
    }
}
