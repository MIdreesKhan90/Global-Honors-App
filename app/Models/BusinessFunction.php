<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BusinessFunction extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function awards(): HasMany
    {
        return $this->hasMany(Award::class, 'business_function_id', 'id');
    }
}
