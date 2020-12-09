<?php

namespace App\Observers;

use App\Models\Concerns\UuidCreatingTrait;
use App\Models\Task;

class TaskObserver
{
    use UuidCreatingTrait;

    /**
     * @param Task $task
     */
    public function deleting(Task $task): void
    {
        $task->tags()->detach();
    }
}
