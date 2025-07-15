<?php

namespace App\Http\Requests;

use App\Enums\TaskStatusEnum;
use App\Models\Task;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class UpdateTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        if ($this->user()->hasRole('manager')) {
            return [
                'title'       => ['sometimes', 'string', 'max:255'],
                'description' => ['sometimes', 'nullable', 'string'],
                'due_date'    => ['sometimes', 'date'],
                'user_id'     => ['sometimes', 'exists:users,id'],
                'parent_id'   => ['sometimes', 'exists:tasks,id'],
                'status'      => ['sometimes', 'in:pending,completed,canceled'],
            ];
        }

        return [
            'status' => ['required', 'in:pending,completed,canceled'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function (Validator $validator) {
            if (
                $this->status === TaskStatusEnum::Completed->value &&
                $this->route('task') instanceof Task &&
                !$this->route('task')->canBeMarkedCompleted()
            ) {
                $validator->errors()->add('status', 'Cannot complete this task until all dependencies are completed.');
            }
        });
    }

}
