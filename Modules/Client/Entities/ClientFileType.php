<?php

namespace Modules\Client\Entities;

use Illuminate\Database\Eloquent\Model;

class ClientFileType extends Model
{
    protected $table = 'client_file_types';
    public $timestamps = false;
    protected $fillable = [];
}
