<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registry extends Model
{
    use HasFactory;

    protected $table = 'registry';

    protected $fillable = [
        'inAt',
        'outAt',
        'carId',
        'userId',
    ];

    protected $dates = ['inAt', 'outAt'];

    public function car()
    {
        return $this->hasMany(Cars::class, 'id', 'carId');
    }
}
