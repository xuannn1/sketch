<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\PageObjects;
use App\Helpers\ConstantObjects;
use App\Http\Resources\QuoteResource;
use App\Http\Resources\StatusResource;
use App\Http\Resources\ThreadBriefResource;
use App\Http\Resources\TagResource;
use App\Http\Resources\ChannelResource;
use App\Http\Resources\TitleResource;
use App\Http\Resources\PostResource;
use App\Http\Resources\PostInfoResource;
use Cache;

class PageController extends Controller
{
    public function home()
    {
        return response()->success([
            'quotes' => QuoteResource::collection(PageObjects::recent_quotes()),
            'recent_short_recommendations' => PostResource::collection(PageObjects::recent_short_recommendations()),
            'recent_added_chapter_books' => ThreadBriefResource::collection(PageObjects::recent_added_chapter_books()),
            'recent_responded_books' => ThreadBriefResource::collection(PageObjects::recent_responded_books()),
            'recent_responded_threads' => ThreadBriefResource::collection(PageObjects::recent_responded_threads()),
            'digested_threads' => ThreadBriefResource::collection(PageObjects::digested_threads()),
            'recent_statuses' => StatusResource::collection(PageObjects::recent_statuses()),
            'recent_QAs' => PostResource::collection(PageObjects::recent_QAs()),

        ]);
    }

    public function homethread()
    {

        $channels = ConstantObjects::allChannels();
        $channel_threads = [];
        foreach($channels as $channel){
            if($channel->is_public){
                $channel_threads[$channel->id] = [
                    'channel' => new ChannelResource($channel),
                    'threads' => ThreadBriefResource::collection(PageObjects::channel_threads($channel->id)),
                ];
            }
        }
        return response()->success($channel_threads);
    }

    public function homebook()
    {
        return response()->success([
            'recent_long_recommendations' => PostResource::collection(PageObjects::recent_long_recommendations()),
            'recent_short_recommendations' => PostResource::collection(PageObjects::recent_short_recommendations()),
            'random_short_recommendations' => PostResource::collection(PageObjects::random_short_recommendations()),
            'recent_custom_short_recommendations' => PostResource::collection(PageObjects::recent_custom_short_recommendations()),
            'recent_custom_long_recommendations' => PostResource::collection(PageObjects::recent_custom_long_recommendations()),
            'recent_added_chapter_books' => ThreadBriefResource::collection(PageObjects::recent_added_chapter_books()),
            'recent_responded_books' => ThreadBriefResource::collection(PageObjects::recent_responded_books()),
            'highest_jifen_books' => ThreadBriefResource::collection(PageObjects::highest_jifen_books()),
            'most_collected_books' => ThreadBriefResource::collection(PageObjects::most_collected_books()),
        ]);
    }

    public function allTags()
    {
        return response()->success([
            'tags' => TagResource::collection(ConstantObjects::allTags()),
        ]);
    }

    public function noTongrenTags()
    {
        return response()->success([
            'tags' => TagResource::collection(ConstantObjects::noTongrenTags()),
        ]);
    }

    public function allChannels()
    {
        return response()->success([
            'channels' => ChannelResource::collection(ConstantObjects::allChannels()),
        ]);
    }

    public function titles()
    {
        return response()->success([
            'titles' => TitleResource::collection(ConstantObjects::titles()),
        ]);
    }
}
