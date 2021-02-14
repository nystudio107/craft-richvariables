<?php
/**
 * Rich Variables plugin for Craft CMS 3.x
 *
 * Allows you to easily use Craft Globals as variables in Rich Text fields
 *
 * @link      https://nystudio107.com
 * @copyright Copyright (c) 2017 nystudio107
 */

namespace nystudio107\richvariables\assetbundles\richvariables;

use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset;
use craft\redactor\assets\redactor\RedactorAsset;
use craft\web\assets\vue\VueAsset;

/**
 * @author    nystudio107
 * @package   RichVariables
 * @since     1.0.0
 */
class RichVariablesAsset extends AssetBundle
{
    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->sourcePath = "@nystudio107/richvariables/assetbundles/richvariables/dist";

        $this->depends = [
            CpAsset::class,
            VueAsset::class,
            RedactorAsset::class,
        ];

        $this->js = [
        ];

        parent::init();
    }
}
