<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Member;

class BankName extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['name'];

    /**
     * Get all of the members for the BankName
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function members(): HasMany
    {
        return $this->hasMany(Member::class);
    }
}
