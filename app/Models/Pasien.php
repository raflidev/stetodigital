<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pasien extends Model
{
    use HasFactory;
    protected $table = 'data_pasien';

    protected $fillable = [
        'user_id',
        'name',
        'gender',
        'address',
        'email',
        'phonenumber',
        'dokter_id',
    ];

    public function user()
    {
        return $this->hasOne(User::class);
    }
}
