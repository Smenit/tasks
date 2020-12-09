<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

trait UuidCreatingTrait
{
    public function creating(Model $model)
    {
        if (!$model->getKey()) {
            $model->{$model->getKeyName()} = (string)Str::uuid();
        }
    }
}