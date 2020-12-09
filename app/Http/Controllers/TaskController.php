<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Models\Task;

class TaskController extends Controller
{
    /**
     * @return Task[]
     */
    public function index()
    {
        return Task::orderByStatus()->orderByPriority()->get();
    }

    public function show(Task $task)
    {
        $tags = [
            'test',
            'test2',
        ];
        $task->syncTags($tags);
    }

    /**
     * @param TaskRequest $request
     */
    public function store(TaskRequest $request)
    {
        $task = Task::create($request->all());
        $task->syncTags($request->input('tags'));
    }

    /**
     * @param Task $task
     * @param TaskRequest $request
     */
    public function update(Task $task, TaskRequest $request)
    {
        $task->update($request->all());
        $task->syncTags($request->input('tags'));
    }

    /**
     * @param Task $task
     * @throws \Exception
     */
    public function destroy(Task $task)
    {
        $task->delete();
    }
}
