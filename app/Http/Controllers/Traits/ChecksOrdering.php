<?php

namespace App\Http\Controllers\Traits;

trait ChecksOrdering
{
    protected string $orderBy = 'created_at';
    protected string $orderDir = 'desc';

    // Entities are only allowed to be ordered by specific columns and directions
    protected function checkOrdering(): void
    {
        $this->orderBy = in_array($this->orderBy, $this->allowedOrders, true) ? $this->orderBy : 'created_at';
        $this->orderDir = in_array($this->orderDir, ['asc', 'desc']) ? $this->orderDir : 'asc';
    }
}
