<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Prize extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name','number','chance'];

 
}
