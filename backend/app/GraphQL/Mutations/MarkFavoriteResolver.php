<?php

namespace App\GraphQL\Mutations;

use App\Models\Account;
use App\Models\Favorite;
use App\Models\Timeline;
use Carbon\Carbon;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class MarkFavoriteResolver
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

        $favorite = null;

        $favorite = $this->createFavorite($account, $args['tweet_id']);

        $this->updateTimelineFavoriteId($account, $args['timeline_id'], $favorite->id);

        $this->addTweetToFollowersTimeline($account, $args['tweet_id'], $favorite->id);

        return !!$favorite;
    }

    /**
     * @param Account $account
     * @param $tweetId
     * @return mixed
     */
    protected function createFavorite(Account $account, $tweetId)
    {
        return Favorite::create([
            'account_id' => $account->id,
            'tweet_id' => $tweetId,
            'favorite_at' => Carbon::now(),
        ]);
    }

    /**
     * @param Account $account
     * @param $timelineId
     * @param $favoriteId
     * @return mixed
     */
    protected function updateTimelineFavoriteId(Account $account, $timelineId, $favoriteId)
    {
        return Timeline::where([
            'id' => $timelineId,
            'account_id' => $account->id,
        ])->update(['favorite_id' => $favoriteId]);
    }

    protected function addTweetToFollowersTimeline(Account $account ,$tweetId, $favoriteId)
    {
        foreach ($account->followers as $follower) {
            Timeline::create([
                'account_id' => $follower->follower_account_id,
                'tweet_id' => $tweetId,
                'original_favorite_id' => $favoriteId,
            ]);
        }
    }
}
