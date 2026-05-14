<?php

namespace App\Models;

use App\Models\Pivots\UuidPivot;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';

    // card_module
    protected $fillable = [
        'id',
        'title',
        'picture_path',
        'audio_path',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    public function children(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Child::class, 'children_has_cards', 'card_id', 'child_id')
            ->using(UuidPivot::class)
            ->withTimestamps();
    }

    public function cardSets(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(CardSet::class, 'card_sets_has_cards', 'card_id', 'card_set_id')
            ->using(UuidPivot::class)
            ->withTimestamps();
    }
}
