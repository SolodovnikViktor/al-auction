<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TemporaryFile extends Model
{
    protected $table = 'temporary_files';
    protected $fillable = ['id', 'folder', 'filename', 'id_user', 'path', 'size'];
}
