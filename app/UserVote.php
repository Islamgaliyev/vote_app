<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserVote extends Model
{
    protected $table = 'user_votes';

    protected $fillable = [
        'user_id',
        'question_id',
        'option_id',
    ];

    public function user() {
        return $this->belongsTo('App\User');
    }
    

    public function option() {
        return $this->belongsTo('App\VoteOption');
    }
}
