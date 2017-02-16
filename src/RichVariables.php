<?php
/**
 * Rich Variables plugin for Craft CMS 3.x
 *
 * Allows you to easily use Craft Globals as variables in Rich Text fields
 *
 * @link      https://nystudio107.com
 * @copyright Copyright (c) 2017 nystudio107
 */

namespace nystudio107\richvariables;

use nystudio107\richvariables\models\Settings;
use nystudio107\richvariables\assetbundles\richvariables\RichVariablesAsset;

use Craft;
use craft\base\Plugin;
use craft\web\UrlManager;
use craft\events\RegisterUrlRulesEvent;
use yii\base\Event;

/**
 * @author    nystudio107
 * @package   RichVariables
 * @since     1.0.0
 */
class RichVariables extends Plugin
{
    // Static Properties
    // =========================================================================

    /**
     * @var static
     */
    public static $plugin;

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        self::$plugin = $this;

        if (!Craft::$app->getRequest()->getIsConsoleRequest()) {
            if (Craft::$app->getRequest()->getIsCpRequest() && !Craft::$app->getUser()->getIsGuest()) {
                Craft::$app->getView()->registerAssetBundle(RichVariablesAsset::class);
            }
        }

        Craft::info('RichVariables '.Craft::t('richVariables', 'plugin loaded'), __METHOD__);
    }

    // Protected Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    protected function createSettingsModel()
    {
        return new Settings();
    }

    /**
     * @inheritdoc
     */
    protected function settingsHtml(): string
    {
        // Get all of the globals sets
        $globalsHandles = [];
        $allGlobalsSets = Craft::$app->getGlobals()->getAllSets();
        foreach ($allGlobalsSets as $globalsSet) {
            $globalsHandles[$globalsSet->handle] = $globalsSet->name;
        }

        // Render our settings template
        return Craft::$app->view->renderTemplate(
            'richvariables'
            .DIRECTORY_SEPARATOR
            .'settings',
            [
                'settings' => $this->getSettings(),
                'globalsSets' => $globalsHandles,
            ]
        );
    }
}
