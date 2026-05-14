<?php

namespace App\Models;

use App\Models\Pivots\UuidPivot;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens;

    protected $fillable = [
        'id',
        'name',
        'email',
        'password',
        'profile_picture_path',
        'role_id',
    ];

    public $timestamps = false;
    public $incrementing = false;
    protected $keyType = 'string';

    protected $hidden = [
        'password',
    ];

    public function role(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function children(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Child::class, 'teachers_has_children', 'teacher_id', 'child_id')
            ->using(UuidPivot::class)
            ->withTimestamps();
    }

    public function managedCenter(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Center::class, 'admin_id');
    }
}
