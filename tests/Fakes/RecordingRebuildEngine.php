<?php

namespace Tests\Fakes;

class RecordingRebuildEngine extends RecordingIndexSettingsEngine
{
    public array $flushedModels = [];
    public array $updatedModels = [];

    public function update($models): void
    {
        $models->each(function ($model): void {
            $this->updatedModels[] = $model::class;
        });
    }

    public function flush($model): void
    {
        $this->flushedModels[] = $model::class;
    }
}
