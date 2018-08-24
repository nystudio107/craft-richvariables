<?php
/**
 * Image Optimize plugin for Craft CMS 3.x
 *
 * Automatically optimize images after they've been transformed
 *
 * @link      https://nystudio107.com
 * @copyright Copyright (c) 2017 nystudio107
 */

namespace nystudio107\richvariables\assetbundles\richvariables;

use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset;

/**
 * @author    nystudio107
 * @package   ImageOptimize
 * @since     1.2.0
 */
class RichVariablesWelcomeAsset extends AssetBundle
{
    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->sourcePath = '@nystudio107/richvariables/assetbundles/richvariables/dist';

        $this->depends = [
            CpAsset::class,
            RichVariablesAsset::class,
        ];

        parent::init();
    }
}
