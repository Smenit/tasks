<?php

namespace App\Models\Concerns;

trait UuidTrait
{
    public function getIncrementing()
    {
        return false;
    }

    public function getKeyType()
    {
        return 'string';
    }
}