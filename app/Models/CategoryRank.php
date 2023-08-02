<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class CategoryRank extends Pivot
{
    use HasFactory;

    protected $table = "category_rank";
    
    public $timestamps = false;
    
    protected $fillable = [
        'category_id',
        'rank_id'
    ];
}
