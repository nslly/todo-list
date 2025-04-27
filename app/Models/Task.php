<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasApiTokens;

    protected $fillable = [
        'todos',
        'user_id',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
