<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'postal_code',
        'address',
        'building',
    ];

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }
}
