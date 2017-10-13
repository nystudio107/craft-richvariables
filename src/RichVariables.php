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
use craft\services\Plugins;
use craft\events\PluginEvent;
use craft\helpers\FileHelper;

use yii\base\Event;

/**
 * Class RichVariables
 *
 * @author    nystudio107
 * @package   RichVariables
 * @since     1.0.0
 */
class RichVariables extends Plugin
{
    // Constants
    // =========================================================================

    const REDACTOR_PLUGIN_FILES = [
        'richvariables.css',
        'richvariables.js'
    ];

    // Static Properties
    // =========================================================================

    /**
     * @var RichVariables
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

        // Make sure the Redactor plugin is installed
        if (Craft::$app->getPlugins()->getPlugin('redactor')) {
            // Event handler: Plugins::EVENT_AFTER_INSTALL_PLUGIN
            Event::on(
                Plugins::className(),
                Plugins::EVENT_AFTER_INSTALL_PLUGIN,
                function (PluginEvent $event) {
                    if ($event->plugin === $this) {
                        // Copy our Redactor plugin into place
                        $this->installRedactorPlugin();
                    }
                }
            );

            // Event handler: Plugins::EVENT_AFTER_INSTALL_PLUGIN
            Event::on(
                Plugins::className(),
                Plugins::EVENT_AFTER_UNINSTALL_PLUGIN,
                function (PluginEvent $event) {
                    if ($event->plugin === $this) {
                        // Remove our Redactor plugin
                        $this->removeRedactorPlugin();
                    }
                }
            );
            // Register our asset bundle
            Craft::$app->getView()->registerAssetBundle(RichVariablesAsset::class);
        }

        Craft::info(
            Craft::t(
                'rich-variables',
                '{name} plugin loaded',
                ['name' => $this->name]
            ),
            __METHOD__
        );
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
            'rich-variables'
            .DIRECTORY_SEPARATOR
            .'settings',
            [
                'settings'    => $this->getSettings(),
                'globalsSets' => $globalsHandles,
            ]
        );
    }

    /**
     * Install the Redactor plugin files
     */
    private function installRedactorPlugin()
    {
        $src = Craft::getAlias('@nystudio107/richvariables')
            .DIRECTORY_SEPARATOR
            .'redactor'
            .DIRECTORY_SEPARATOR
            .'plugins'
            .DIRECTORY_SEPARATOR;
        $dest = Craft::getAlias('@config/redactor/plugins')
            .DIRECTORY_SEPARATOR;
        foreach (self::REDACTOR_PLUGIN_FILES as $file) {
            if (($contents = file_get_contents($src.$file)) !== false) {
                FileHelper::writeToFile($dest.$file, $contents);
            }
        }
    }

    /**
     * Remove the Redactor plugin files
     */
    private function removeRedactorPlugin()
    {
        $src = Craft::getAlias('@config/redactor/plugins')
            .DIRECTORY_SEPARATOR;
        foreach (self::REDACTOR_PLUGIN_FILES as $file) {
            unlink($src.$file);
        }
    }
}
