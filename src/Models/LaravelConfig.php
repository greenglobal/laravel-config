<?php

namespace GGPHP\Config\Models;

use Illuminate\Database\Eloquent\Model;

class LaravelConfig extends Model
{
    protected $table = 'laravel_config';
    protected $fillable = ['code', 'value', 'default'];
}
