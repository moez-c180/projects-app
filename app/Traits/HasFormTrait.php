<?php
namespace App\Traits;

use App\Models\MemberForm;

trait HasFormTrait
{
    public static function bootHasFormTrait()
    {
        static::created(function ($model) {
            MemberForm::create([
                'member_id' => $model->member_id,
                'form_type' => get_class($model),
                'form_id' => $model->id,
            ]);
        });
        
        static::deleted(function ($model) {
            MemberForm::where([
                'member_id' => $model->member_id,
                'form_type' => get_class($model),
                'form_id' => $model->id,
            ])->delete();
        });
    }
}