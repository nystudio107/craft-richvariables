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
use craft\redactor\events\RegisterPluginPathsEvent;
use craft\redactor\Field as RichText;
use craft\services\Plugins;

use yii\base\Event;

use Composer\Semver\Comparator;
use yii\base\Exception;
use yii\base\InvalidConfigException;

/**
 * Class RichVariables
 *
 * @author    nystudio107
 * @package   RichVariables
 * @since     1.0.0
 */
class RichVariables extends Plugin
{
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

        // Only load for AdminCP, non-console requests
        $request = Craft::$app->getRequest();
        if ($request->getIsCpRequest()) {
            // Handler: Plugins::EVENT_AFTER_LOAD_PLUGINS
            Event::on(
                Plugins::class,
                Plugins::EVENT_AFTER_LOAD_PLUGINS,
                function () {
                    // Add in our event listeners that are needed for every request
                    $this->installEventListeners();
                }
            );
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

    protected function installEventListeners()
    {
        // Make sure the Redactor plugin is installed
        $redactor = Craft::$app->getPlugins()->getPlugin('redactor');
        if ($redactor) {
            // Event handler: RichText::EVENT_REGISTER_PLUGIN_PATHS
            Event::on(
                RichText::class,
                RichText::EVENT_REGISTER_PLUGIN_PATHS,
                function (RegisterPluginPathsEvent $event) {
                    /** @var Plugin $redactor */
                    $redactor = Craft::$app->getPlugins()->getPlugin('redactor');
                    $versionDir = 'v1/';
                    if (Comparator::greaterThanOrEqualTo($redactor->version, '2.0.0')) {
                        $versionDir = 'v2/';
                    }
                    // Add the path to our Redactor plugin
                    $src = Craft::getAlias('@nystudio107/richvariables/redactor/plugins/'.$versionDir);
                    $event->paths[] = $src;
                }
            );
            // Register our asset bundle
            try {
                Craft::$app->getView()->registerAssetBundle(RichVariablesAsset::class);
            } catch (InvalidConfigException $e) {
                Craft::error($e->getMessage(), __METHOD__);
            }
        }
    }

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
        try {
            return Craft::$app->view->renderTemplate(
                'rich-variables/settings',
                [
                    'settings' => $this->getSettings(),
                    'globalsSets' => $globalsHandles,
                ]
            );
        } catch (\Twig_Error_Loader $e) {
            Craft::error($e->getMessage(), __METHOD__);
        } catch (Exception $e) {
            Craft::error($e->getMessage(), __METHOD__);
        }

        return '';
    }
}
