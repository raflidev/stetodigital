<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MachineLearning extends Model
{
    use HasFactory;

    protected $table = 'data_ml';

    protected $fillable = [
        'pasien_id',
        'name',
        'file_csv',
        'result',
    ];

    public function user()
    {
        return $this->hasOne(User::class);
    }
}
