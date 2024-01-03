<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

trait HasOwner
{
    protected static function bootHasOwner()
    {
        static::creating(function ($model) {
            if (Auth::check() && \Schema::hasColumn($model->getTable(), 'owner_id')) {
                $model->owner_id = Auth::id();
            }
        });
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
}
