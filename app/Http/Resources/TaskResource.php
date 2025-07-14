<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\UserResource;

class TaskResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'title'         => $this->title,
            'description'   => $this->description,
            'status'        => $this->status->value,
            'due_date'      => $this->due_date?->toDateString(),
            'parent_id'     => $this->parent_id,
            'assigned_user' => new UserResource($this->whenLoaded('user')),
            'subtasks'      => TaskResource::collection($this->whenLoaded('children')),
        ];
    }
}

