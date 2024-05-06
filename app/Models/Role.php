<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    const COMPANY_ROLE = 1;

    const DRIVER_ROLE = 3;

    const ADMIN_ROLE = 2;
}
