<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;

    protected $table        = 'users';
    protected $fillable     = ['email', 'phone_number'];
    protected $primaryKey   = 'user_id';

    public function project()
    {
        return $this->hasMany('App\Models\Project');
    }
}
