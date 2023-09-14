<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cars extends Model
{
    use HasFactory;

    protected $fillable = [
        'plate',
        'categoryId',
        'userId',
    ];

    public function category()
    {
        return $this->hasMany(CarCategory::class, 'id', 'categoryId');
    }
}
