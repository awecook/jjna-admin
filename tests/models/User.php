<?php

namespace Tests\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'test_users';

    protected $appends = ['full_name', 'position'];

    public function profile()
    {
        return $this->hasOne(Profile::class, 'Waiter_ID');
    }

    public function getFullNameAttribute()
    {
        return "{$this->profile['first_name']} {$this->profile['last_name']}";
    }

    public function getPositionAttribute()
    {
        return "{$this->profile->latitude} {$this->profile->longitude}";
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'test_user_tags', 'Waiter_ID', 'tag_id');
    }
}
