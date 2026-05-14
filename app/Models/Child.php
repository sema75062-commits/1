<?php

namespace App\Models;

use App\Models\Pivots\UuidPivot;
use Illuminate\Database\Eloquent\Model;

class Child extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'full_name',
        'profile_picture_path',
        'description',
    ];

    public function cards(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Card::class, 'children_has_cards', 'child_id', 'card_id')
            ->using(UuidPivot::class)
            ->withTimestamps();
    }

    public function cardSets(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(CardSet::class, 'children_has_card_sets', 'child_id', 'card_set_id')
            ->using(UuidPivot::class)
            ->withTimestamps();
    }

    public function teachers(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class, 'teachers_has_children', 'child_id', 'teacher_id')
            ->using(UuidPivot::class)
            ->withTimestamps();
    }

    public function centers(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Center::class, 'centers_has_children', 'child_id', 'center_id')
            ->using(UuidPivot::class)
            ->withTimestamps();
    }

    public function familyAccounts(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(FamilyAccount::class, 'family_accounts_has_children', 'child_id', 'family_account_id')
            ->using(UuidPivot::class)
            ->withTimestamps();
    }
}
