<?php

namespace GGPHP\Config\Models;

use Illuminate\Database\Eloquent\Model;

class GGFirebaseStorage extends Model
{
    protected $table = 'gg_firebase_storage';
    protected $fillable = ['name', 'url', 'destination', 'type', 'expires'];
}
