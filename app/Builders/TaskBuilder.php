<?php

namespace App\Builders;

use Illuminate\Database\Eloquent\Builder;

class TaskBuilder extends Builder
{
    public function onlyParents(): TaskBuilder
    {
        return $this->whereNull('parent_id');
    }

    public function forUser(int $userId): TaskBuilder
    {
        return $this->where('user_id', $userId);
    }

    public function filterByUser(?int $userId): static
    {
        if ($userId) {
            $this->where('user_id', $userId);
        }

        return $this;
    }

    public function filterByStatus(?string $status): static
    {
        if ($status) {
            $this->where('status', $status);
        }
        return $this;
    }

    public function filterByDateRange(?string $start, ?string $end): static
    {
        if ($start) {
            $this->whereDate('due_date', '>=', $start);
        }

        if ($end) {
            $this->whereDate('due_date', '<=', $end);
        }

        return $this;
    }
}
