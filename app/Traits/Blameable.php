<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;

trait Blameable
{
    protected static function bootBlameable()
    {
        // When creating the model
        static::creating(function ($model) {
            if (Auth::check()) {
                $model->created_by = Auth::id();
                $model->updated_by = Auth::id();
            }
        });

        // When updating the model
        static::updating(function ($model) {
            if (Auth::check()) {
                $model->updated_by = Auth::id();
            }
        });

        // When soft deleting the model
        if (method_exists(static::class, 'restoring')) {
            static::deleting(function ($model) {
                if (Auth::check() && !$model->isForceDeleting()) {
                    $model->deleted_by = Auth::id();
                    $model->save(); // Save the deleted_by before the soft delete hits
                }
            });
        }
    }
}
