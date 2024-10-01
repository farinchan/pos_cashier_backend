<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function TransactionDetails()
    {
        return $this->hasMany(TransactionDetail::class);
    }

    public function TransactionPayment()
    {
        return $this->hasOne(Payment::class);
    }
}
