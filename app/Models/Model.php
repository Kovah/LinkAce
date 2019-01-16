<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Watson\Rememberable\Rememberable;

/**
 * Class Model
 *
 * @package App\Models
 */
abstract class Model extends Eloquent
{
    use Rememberable;

    /**
     * @var int Duration in minutes how long model queries should be cached
     */
    public $rememberFor = 60;
}
