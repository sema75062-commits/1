<?php

namespace App\Models;

use App\Models\Pivots\UuidPivot;
use Illuminate\Database\Eloquent\Model;

class CardSet extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'title',
    ];

    public function cards(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Card::class, 'card_sets_has_cards', 'card_set_id', 'card_id')
            ->using(UuidPivot::class)
            ->withTimestamps();
    }

    public function children(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Child::class, 'children_has_card_sets', 'card_set_id', 'child_id')
            ->using(UuidPivot::class)
            ->withTimestamps();
    }
}
