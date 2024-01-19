<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AwardKeyword extends Model
{
    use HasFactory;

    protected $table = 'award_keyword';
    public $timestamps = false;

    protected $fillable = [
        'award_id',
        'keyword'
    ];

    public function award()
    {
        return $this->belongsTo(Award::class);
    }
}
