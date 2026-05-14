<?php

namespace App\Models\Pivots;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Str;

/*
  Базовый Pivot-класс для таблиц с UUID-первичным ключом
  Eloquent по умолчанию не генерирует UUID для pivot, поэтому делаем это вручную перед вставкой
*/
class UuidPivot extends Pivot
{
    public $incrementing = false;
    protected $keyType = 'string';

    protected static function booted(): void
    {
        static::creating(function (self $model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }
}
