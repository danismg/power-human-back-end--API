<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use HasFactory, SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'logo',
        'user_id'
    ];

    public function users(){
        return $this->belongsTo(User::class);
    }

    public function teams(){
        return $this->hasMany(Team::class);
    }

    public function roles(){
        return $this->hasMany(Role::class);
    }

}
