<?php

namespace App\Models;

use App\Enums\{UserStatus, UserType};
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;

    protected $table = 'users';

    protected $fillable = [
        'full_name',
        'document',
        'email',
        'password',
        'user_type',
        'user_status',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'user_type'   => UserType::class,
        'user_status' => UserStatus::class,
    ];
}
