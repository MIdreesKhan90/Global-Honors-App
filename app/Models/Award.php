<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Award extends Model
{
    use HasFactory;
    protected $guarded = ['id', 'created_at', 'updated_at'];
    public function industries(): BelongsTo
    {
        return $this->belongsTo(Industry::class, 'industry_id', 'id');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'award_category', 'award_id', 'category_id');
    }

    public function sponsors(): BelongsTo
    {
        return $this->belongsTo(Sponsor::class, 'sponsor_id', 'id');
    }

    public function businessFunctions(): BelongsTo
    {
        return $this->belongsTo(BusinessFunction::class, 'business_function_id', 'id');
    }

    public function lists()
    {
        return $this->belongsToMany(CustomerList::class, 'award_lists', 'award_id', 'list_id');
    }

    public function awardKeywords()
    {
        return $this->hasMany(AwardKeyword::class);
    }
}
