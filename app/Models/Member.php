<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Department;
use App\Models\Rank;

class Member extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'military_number',
        'seniority_number',
        'rank_id',
        'is_institute_graduate',
        'is_nco',
        'category_id',
        'is_general_staff',
        'name',
        'address',
        'home_phone_number',
        'mobile_phone_number',
        'beneficiary_name',
        'beneficiary_title',
        'class',
        'department_id',
        'graduation_date',
        'birth_date',
        'travel_date',
        'return_date',
        'national_id_number',
        'bank_account_number',
        'pension_date',
        'pension_reason',
        'death_date',
        'is_subscribed',
        'notes',
    ];

    /**
     * Get the category that owns the Member
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the department that owns the Member
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get the rank that owns the Member
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function rank(): BelongsTo
    {
        return $this->belongsTo(Rank::class);
    }
}
