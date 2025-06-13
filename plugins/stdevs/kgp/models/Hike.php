<?php

namespace StDevs\Kgp\Models;

use Model;

/**
 * Hike Model
 *
 * @link https://docs.octobercms.com/3.x/extend/system/models.html
 */
class Hike extends Model
{
    use \October\Rain\Database\Traits\Validation;

    /**
     * @var string table name
     */
    public $table = 'stdevs_kgp_hikes';

    /**
     * @var array rules for validation
     */
    public $rules = [];
}
