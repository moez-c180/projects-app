<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role as BaseRole;

class Role extends BaseRole
{
    const SUPER_ADMIN = 'super-admin';
    const ROOT_ADMIN = 'root-admin';
}
