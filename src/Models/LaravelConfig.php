<?php

namespace GGPHP\Config\Models;

use Illuminate\Database\Eloquent\Model;

class LaravelConfig extends Model
{
    protected $table = 'laravel_config';
    protected $fillable = ['code', 'value', 'default'];

    // Define API throttle
    public const MAX_ATTEMPTS_DEFAULT = 60;
    public const DECAY_MINUTES_DEFAULT = 1;
}
