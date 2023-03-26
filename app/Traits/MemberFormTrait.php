<?php
namespace App\Traits;

use App\Models\MemberForm;
use Illuminate\Database\Eloquent\Factories\BelongsToRelationship;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Foundation\Application;

trait MemberFormTrait
{
    public static function bootMemberFormTrait()
    {
        static::created(function ($model) {
            MemberForm::create([
                'member_id' => $model->member_id,
                'formable_type' => get_class($model),
                'formable_id' => $model->id,
            ]);
        });
        
        static::deleted(function ($model) {
            MemberForm::where([
                'member_id' => $model->member_id,
                'formable_type' => get_class($model),
                'formable_id' => $model->id,
            ])->delete();
        });
    }
}