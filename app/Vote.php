<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    protected $fillable = [
        'title',
        'user_id'
    ];

    protected $table = 'questions';

    public function options() {
        return $this->hasMany('App\VoteOption', 'question_id', 'id');
    }

    public function user() {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }
}
