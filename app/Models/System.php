<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class System extends Model
{
    use HasFactory;
    protected $fillable = ['app_env', 'app_debug', 'app_url', 'authorization_parameter', 'authorization', 'login_route', 'db', 'db_host', 'db_port', 'db_database', 'db_username', 'db_password'];
}
