<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{

    protected $fillable = [

        'user_id',
        'name',
        'description',

    ];

    public function tasks(){

        return $this->hasMany(Task::class)
                    ->orderBy('priority'); //a project has many tasks
    }

    public function user(){

        return $this->belongsTo(User::class); //each project belongs to an user
    }
}
