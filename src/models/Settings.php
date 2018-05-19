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

    /**
     * @var bool
     */
    public $useIconForMenu = true;

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            ['globalSetHandle', 'string'],
            ['useIconForMenu', 'boolean'],
            ['globalSetHandle', 'default', 'value' => ''],
        ];
    }
}
