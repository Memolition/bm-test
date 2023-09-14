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

    protected $casts = [
        'inAt'  => 'datetime:c',
        'outAt' => 'datetime:c',
    ];

    public function car()
    {
        return $this->hasMany(Cars::class, 'id', 'carId');
    }
}
