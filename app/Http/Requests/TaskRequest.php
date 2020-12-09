<?php

namespace App\Http\Requests;

use App\Services\Constants\Task;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'     => ['required'],
            'tags'     => ['required', 'array'],
            'priority' => ['required', Rule::in(Task::PRIORITIES)],
            'status'   => ['required', Rule::in(Task::STATUSES)],
        ];
    }
}
