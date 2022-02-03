<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $guarded = [

    ];

    public function user() {
        return $this->belongsTo('App\Models\User');
    }

    public function checkpoints() {
        return $this->hasMany('App\Models\Checkpoint');
    }

    public function skills() {
        return $this->hasMany('App\Models\Skill');
    }
}