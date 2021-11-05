<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    use HasFactory;

    protected $fillable = [
        'token', 'device', 'user_id'
    ];

    public function users(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
