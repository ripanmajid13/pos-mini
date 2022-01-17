<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserHasPermission extends Model
{
    use HasFactory;

    protected $table = "model_has_permissions";

    public $timestamps = false;

    public function permission()
    {
        return $this->belongsTo(Permission::class);
    }
}
