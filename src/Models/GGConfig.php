<?php

namespace GGPHP\Config\Models;

use Illuminate\Database\Eloquent\Model;
use GGPHP\Config\Services\FirebaseService;

class GGConfig extends Model
{
    protected $table = 'gg_config';
    protected $fillable = ['code', 'value', 'type', 'default'];

    // Define API throttle
    public const MAX_ATTEMPTS_DEFAULT = 60;
    public const DECAY_MINUTES_DEFAULT = 1;

    // Define reference
    public const FIELD_REFERENCE = 'configuration/system/fields';
}
