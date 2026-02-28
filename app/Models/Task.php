<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [

        'user_id',
        'project_id',
        'name',
        'priority',
    
    ];

    public function user(){

        return $this->belongsTo(User::class); //each task belongs to a project created by an user
    }

    public function project(){

        return $this->belongsTo(Project::class); //each task belongs to a project
    }

}
