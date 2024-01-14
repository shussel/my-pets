<?php

namespace App\Observers;

use MongoDB\Laravel\Eloquent\Model;

/**
 * https://hafiqiqmal93.medium.com/laravel-softdelete-avoid-unique-constraint-problem-83d18dc3621e
 */
class UniqueSoftDeleteObserver
{
    private const DELIMITER = '--';

    public function deleted(Model $model)
    {
        foreach ($model->getDuplicateAvoidColumns() as $column) {
            $newValue = time().self::DELIMITER.$model->{$column};
            $model->{$column} = $newValue;
        }
        $model->save();
    }

    public function restoring(Model $model)
    {
        if (!$model->trashed()) {
            return;
        }
        foreach ($model->getDuplicateAvoidColumns() as $column) {
            if ($value = (explode(self::DELIMITER, $model->{$column})[1] ?? null)) {
                $model->{$column} = $value;
            }
        }
    }
}
