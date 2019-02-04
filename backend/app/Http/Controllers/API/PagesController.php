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
use App\Http\Resources\PostResource;
use Cache;

class PagesController extends Controller
{
    public function home()
    {
        return response()->success([
            'quotes' => QuoteResource::collection(PageObjects::recent_quotes()),
            'recent_added_chapter_books' => ThreadBriefResource::collection(PageObjects::recent_added_chapter_books()),
            'recent_responded_books' => ThreadBriefResource::collection(PageObjects::recent_responded_books()),
            'recent_responded_threads' => ThreadBriefResource::collection(PageObjects::recent_responded_threads()),
            'recent_statuses' => StatusResource::collection(PageObjects::recent_statuses()),
            'short_recommendations' => PostResource::collection(PageObjects::recent_short_recommendations()),
            'long_recommendations' => PostResource::collection(PageObjects::recent_long_recommendations()),
        ]);
    }

    public function homethread()
    {
        return response()->success([
            'recent_responded_threads' => ThreadBriefResource::collection(PageObjects::recent_responded_threads()),
        ]);
    }

    public function homebook()
    {
        return response()->success([
            'recent_added_chapter_books' => ThreadBriefResource::collection(PageObjects::recent_added_chapter_books()),
            'recent_responded_books' => ThreadBriefResource::collection(PageObjects::recent_responded_books()),
            'short_recommendations' => PostResource::collection(PageObjects::recent_short_recommendations()),
            'long_recommendations' => PostResource::collection(PageObjects::recent_long_recommendations()),
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
}
