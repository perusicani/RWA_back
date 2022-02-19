<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    use HasFactory;

    protected $guarded = [

    ];

    public function users() {
        return $this->belongsToMany('App\Models\User');
    }

    public function tasks() {
        return $this->belongsToMany('App\Models\Task');
    }

}