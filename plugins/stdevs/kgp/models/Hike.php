<?php

namespace StDevs\Kgp\Models;

use Model;
use RainLab\User\Models\User;

/**
 * Hike Model
 *
 * @link https://docs.octobercms.com/3.x/extend/system/models.html
 */
class Hike extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use \October\Rain\Database\Traits\Sluggable;

    /**
     * @var string table name
     */
    public $table = 'stdevs_kgp_hikes';

    /**
     * @var array rules for validation
     */
    public $rules = [];

    /**
     * @var array fields for creating slug
     */
    public $slugs = [
        'name'
    ];

    public $belongsTo = [
        'user' => User::class,
    ];
}
