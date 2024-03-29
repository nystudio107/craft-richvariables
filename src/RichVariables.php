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

use Composer\Semver\Comparator;
use Craft;
use craft\base\Plugin;
use craft\events\PluginEvent;
use craft\events\RegisterUrlRulesEvent;
use craft\helpers\UrlHelper;
use craft\redactor\events\RegisterPluginPathsEvent;
use craft\redactor\Field as RichText;
use craft\services\Plugins;
use craft\web\twig\variables\CraftVariable;
use craft\web\UrlManager;
use Exception;
use nystudio107\pluginvite\services\VitePluginService;
use nystudio107\richvariables\assetbundles\richvariables\RichVariablesAsset;
use nystudio107\richvariables\models\Settings;
use nystudio107\richvariables\variables\RichVariablesVariable;
use Twig\Error\LoaderError;
use yii\base\Event;
use yii\base\InvalidConfigException;

/**
 * Class RichVariables
 *
 * @author    nystudio107
 * @package   RichVariables
 * @since     1.0.0
 *
 * @property VitePluginService $vite
 */
class RichVariables extends Plugin
{
    // Static Properties
    // =========================================================================

    /**
     * @var RichVariables
     */
    public static $plugin;

    // Static Methods
    // =========================================================================
    /**
     * @var string
     */
    public $schemaVersion = '1.0.0';

    // Public Properties
    // =========================================================================
    /**
     * @var bool
     */
    public $hasCpSection = false;
    /**
     * @var bool
     */
    public $hasCpSettings = true;

    /**
     * @inheritdoc
     */
    public function __construct($id, $parent = null, array $config = [])
    {
        $config['components'] = [
            // Register the vite service
            'vite' => [
                'class' => VitePluginService::class,
                'assetClass' => RichVariablesAsset::class,
                'useDevServer' => true,
                'devServerPublic' => 'http://localhost:3001',
                'serverPublic' => 'http://localhost:8000',
                'errorEntry' => 'src/js/app.ts',
                'devServerInternal' => 'http://craft-richvariables-buildchain:3001',
                'checkDevServer' => true,
            ],
        ];

        parent::__construct($id, $parent, $config);
    }


    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        self::$plugin = $this;

        // Add in our event listeners that are needed for every request
        $this->installEventListeners();
        // We're loaded!
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
     * Install our event listeners
     */
    protected function installEventListeners()
    {
        Event::on(
            CraftVariable::class,
            CraftVariable::EVENT_INIT,
            function (Event $event) {
                /** @var CraftVariable $variable */
                $variable = $event->sender;
                $variable->set('richVariables', [
                    'class' => RichVariablesVariable::class,
                    'viteService' => $this->vite,
                ]);
            }
        );
        // Handler: Plugins::EVENT_AFTER_INSTALL_PLUGIN
        Event::on(
            Plugins::class,
            Plugins::EVENT_AFTER_INSTALL_PLUGIN,
            function (PluginEvent $event) {
                if ($event->plugin === $this) {
                    $request = Craft::$app->getRequest();
                    if ($request->isCpRequest) {
                        Craft::$app->getResponse()->redirect(UrlHelper::cpUrl('rich-variables/welcome'))->send();
                    }
                }
            }
        );
        $request = Craft::$app->getRequest();
        // Install only for non-console site requests
        if ($request->getIsSiteRequest() && !$request->getIsConsoleRequest()) {
            $this->installSiteEventListeners();
        }
        // Install only for non-console Control Panel requests
        if ($request->getIsCpRequest() && !$request->getIsConsoleRequest()) {
            $this->installCpEventListeners();
        }
    }

    /**
     * Install site event listeners for site requests only
     */
    protected function installSiteEventListeners()
    {
        // Handler: UrlManager::EVENT_REGISTER_SITE_URL_RULES
        Event::on(
            UrlManager::class,
            UrlManager::EVENT_REGISTER_SITE_URL_RULES,
            function (RegisterUrlRulesEvent $event) {
                Craft::debug(
                    'UrlManager::EVENT_REGISTER_SITE_URL_RULES',
                    __METHOD__
                );
                // Register our Control Panel routes
                $event->rules = array_merge(
                    $event->rules,
                    $this->customFrontendRoutes()
                );
            }
        );
    }

    /**
     * Return the custom frontend routes
     *
     * @return array
     */
    protected function customFrontendRoutes(): array
    {
        return [
        ];
    }

    /**
     * Install site event listeners for Control Panel requests only
     */
    protected function installCpEventListeners()
    {
        // Handler: Plugins::EVENT_AFTER_LOAD_PLUGINS
        Event::on(
            Plugins::class,
            Plugins::EVENT_AFTER_LOAD_PLUGINS,
            function () {
                $this->installRedactorPlugin();
            }
        );
    }

    /**
     * Install our Redactor plugin
     */
    protected function installRedactorPlugin()
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
                    $src = Craft::getAlias('@nystudio107/richvariables/redactor/plugins/' . $versionDir);
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
        } catch (LoaderError $e) {
            Craft::error($e->getMessage(), __METHOD__);
        } catch (Exception $e) {
            Craft::error($e->getMessage(), __METHOD__);
        }

        return '';
    }
}
