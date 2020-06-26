<?php

namespace App\GraphQL\Mutations;

use App\Models\Account;
use App\Models\Timeline;
use App\Models\Tweet;
use Carbon\Carbon;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class CreateTweetResolver
{
    public function resolve($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {

        /** @var \App\Models\Account $account */
        $account = auth()->user();

        $tweet = $this->createTweet($account, $args);

        $this->addTweetToTimeline($account, $tweet);

        return $tweet;
    }

    /**
     * @param \App\Models\Account $account
     * @param array $data
     * @return \App\Models\Tweet
     */
    protected function createTweet(Account $account, array $data)
    {
        return Tweet::create([
            'account_id' => $account->id,
            'content'    => $data['content'],
            'tweeted_at' => Carbon::now(),
        ]);

    }

    /**
     * @param \App\Models\Account $account
     * @param \App\Models\Tweet $tweet
     * @return \App\Models\Timeline
     */
    protected function addTweetToTimeline(Account $account, Tweet $tweet)
    {
        return Timeline::create([
            'account_id' => $account->id,
            'tweet_id'   => $tweet->id,
        ]);
    }
}
