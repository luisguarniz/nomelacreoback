<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    use HasFactory;

    //relacion de uno a muchos
  public function votingSessions(){
    return $this->HasMany('App\Models\Votingsession');
}

    //relacion de uno a muchos (inversa)
    public function user(){
        return $this->belongsTo('App\Models\User');
    }

}