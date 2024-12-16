<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $table = 'transactions';

    protected $fillable = [
        'payer',
        'payee',
        'value',
    ];

    public function payer()
    {
        return $this->belongsTo(User::class, 'payer');
    }

    public function payee()
    {
        return $this->belongsTo(User::class, 'payee');
    }
}
