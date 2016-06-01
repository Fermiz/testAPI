<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Prize extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name','number'];
    
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'user_id' => 'int',
    ];

     public function user()
    {
        return $this->belongsTo(User::class);
    }
}
