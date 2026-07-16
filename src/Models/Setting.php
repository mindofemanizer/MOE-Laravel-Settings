<?php

namespace Moe\Settings\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'settings';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $primaryKey = 'key';

    public $timestamps = true;

    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'description',
    ];
}
