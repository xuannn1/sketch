<?php

namespace App\Models\Traits;

use Carbon\Carbon;
use Auth;

trait RegularTraits
{

    public function scopeWithOrder($query, $order)
    {
        switch ($order) {
            case 'recentresponded':
                return $query->recentResponded();
                break;
            case 'oldest':
                return $query->oldest();
                break;
            default:
                return $query->recent();
                break;
        }
    }

    public function scopeRecentResponded($query)
    {
        return $query->orderBy('lastresponded_at', 'desc');
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

}
