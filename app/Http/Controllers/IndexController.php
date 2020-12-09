<?php

namespace App\Http\Controllers;

use App\Services\Constants\Task;
use Illuminate\Support\Facades\Lang;

class IndexController extends Controller
{
    public function index()
    {
        $translations = json_encode(Lang::get('tasks'));

        $pageSettings = [
            'statuses'   => Task::STATUSES,
            'priorities' => Task::PRIORITIES,
        ];

        return view('index', compact('translations', 'pageSettings'));
    }
}
