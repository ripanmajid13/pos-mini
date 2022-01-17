<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory, SoftDeletes;

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('name', 'asc');
    }

    public function roleHasPermission()
    {
        return $this->hasMany(RoleHasPermission::class);
    }
}
