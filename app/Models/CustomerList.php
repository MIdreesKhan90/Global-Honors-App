<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerList extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $casts = [
        'created_at' => 'date:m/d/Y',
        'updated_at' => 'date:m/d/Y',
    ];

    protected $fillable = [
        'name',
        'user_id',
        'created_at',
        'updated_at',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function awards()
    {
        return $this->belongsToMany(Award::class, 'award_lists', 'list_id', 'award_id')->withTimestamps();
    }
}
