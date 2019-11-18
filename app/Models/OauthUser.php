<?php

namespace App\Models;

use Suitcore\Models\SuitModel;

class OauthUser extends SuitModel
{
    public $fillable = ['provider', 'oauth_id', 'user_id', 'graph'];

    protected $casts = ['graph' => 'array'];

    /**
     * @return User|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
