<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class user_verification_tokens extends Model
{
    use HasFactory;
    protected $fillable = [
        // 'name',
        // 'email',
        'verifyNumber',
        'updated_at',
        'created_at'
    ];
}
