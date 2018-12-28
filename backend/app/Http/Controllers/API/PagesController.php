<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\PageObjects;
use App\Http\Resources\QuoteResource;
use App\Http\Resources\StatusResource;
use App\Http\Resources\ThreadResources\ThreadBriefResource;
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
            'recent_statuses' => StatusResource::collection(PageObjects::recent_statuses())
        ]);
    }
}
