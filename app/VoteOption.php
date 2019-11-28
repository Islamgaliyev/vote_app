<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VoteOption extends Model
{
    protected $fillable = [
        'question_id',
        'title',
        'votes'
    ];

    public function question() {
        return $this->belongsTo('App\Vote');
    }

    public function user() {
        return $this->hasOneThrough('App\User', 'App\UserVote', 'user_id', 'id', 'dsassd');
    }
}
