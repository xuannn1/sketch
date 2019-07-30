<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class HistoricalEmailModification extends Model
{
    const UPDATED_AT = null;
    protected $guarded = [];
    protected $dates = ['created_at', 'old_email_verified_at', 'admin_revoked_at'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->withDefault();
    }
}
