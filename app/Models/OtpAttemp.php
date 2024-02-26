<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtpAttemp extends Model
{
    use HasFactory;

    protected $fillable = ['email', 'otp'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
