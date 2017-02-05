<?php
/**
 * Rich Variables plugin for Craft CMS 3.x
 *
 * Allows you to easily use Craft Globals as variables in Rich Text fields
 *
 * @link      https://nystudio107.com
 * @copyright Copyright (c) 2017 nystudio107
 */

namespace nystudio107\richvariables\models;

use nystudio107\richvariables\RichVariables;

use Craft;
use craft\base\Model;

/**
 * @author    nystudio107
 * @package   RichVariables
 * @since     1.0.0
 */
class Settings extends Model
{
    // Public Properties
    // =========================================================================

    /**
     * @var string
     */
    public $globalSetHandle = '';

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['globalSetHandle', 'string'],
            ['globalSetHandle', 'default', 'value' => ''],
        ];
    }
}
