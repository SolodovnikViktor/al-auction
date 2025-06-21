<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PhotoPosition extends Model
{
    protected $table = 'photo_positions';
    protected $fillable = ['user_id', 'lot_id', 'position'];
}
