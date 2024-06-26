<?php

namespace EdLugz\VAS\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method static create(array|string[] $array_merge)
 */
class VasSms extends Model
{
    use SoftDeletes;
    protected $guarded = [];
}
