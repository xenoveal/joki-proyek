<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $table        = 'projects';
    protected $fillable     = ['user_id', 'project_details', 'deadline', 'status', 'deal', 'payment_proof', 'tnc_accepted'];
    protected $primaryKey   = 'project_id';

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
}
