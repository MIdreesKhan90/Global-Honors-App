<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AwardList extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $fillable = [
        'list_id',
        'award_id',
        'created_at',
        'updated_at',
    ];
}
