<?php
/**
 * Rich Variables plugin for Craft CMS 3.x
 *
 * Allows you to easily use Craft Globals as variables in Rich Text fields
 *
 * @link      https://nystudio107.com
 * @copyright Copyright (c) 2017 nystudio107
 */

namespace nystudio107\richvariables\controllers;

use nystudio107\richvariables\RichVariables;
use nystudio107\richvariables\assetbundles\richvariables\RichVariablesAsset;

use Craft;
use craft\base\Field;
use craft\helpers\Json;
use craft\fields\PlainText as PlainTextField;
use craft\fields\Number as NumberField;
use craft\fields\Date as DateField;
use craft\fields\Dropdown as DropdownField;
use craft\web\Controller;

use /** @noinspection PhpUndefinedNamespaceInspection */
    craft\redactor\Field as RedactorField;
use /** @noinspection PhpUndefinedNamespaceInspection */
    craft\ckeditor\Field as CKEditorField;

use yii\base\InvalidConfigException;

/**
 * @author    nystudio107
 * @package   RichVariables
 * @since     1.0.0
 */
class DefaultController extends Controller
{
    // Constants
    // =========================================================================

    const VALID_FIELD_CLASSES = [
        PlainTextField::class,
        NumberField::class,
        DateField::class,
        DropdownField::class,
        RedactorField::class,
        CKEditorField::class,
    ];

    // Public Methods
    // =========================================================================

    /**
     * @return mixed
     */
    public function actionIndex()
    {
        $result = [];
        $variablesList = [];

        // Get the global set to use
        $settings = RichVariables::$plugin->getSettings();
        $globalsSet = Craft::$app->getGlobals()->getSetByHandle($settings['globalSetHandle']);
        // Grab the first global set if they haven't specified one yet
        if (!$globalsSet) {
            $allGlobalsSetIds = Craft::$app->getGlobals()->getAllSetIds();
            if (!empty($allGlobalsSetIds)) {
                $globalsSet = Craft::$app->globals->getSetById($allGlobalsSetIds[0]);
            }
        }
        if ($globalsSet) {
            // Get the field layout fields used for this global set
            $layout = $globalsSet->getFieldLayout();
            if ($layout) {
                $fieldLayoutFields = $layout->getFields();
                /** @var Field $field */
                foreach ($fieldLayoutFields as $field) {
                    foreach (self::VALID_FIELD_CLASSES as $fieldClass) {
                        if ($field instanceof $fieldClass) {
                            // Add the field title and Reference Tag as per https://craftcms.com/docs/reference-tags
                            $thisVar = [
                                'title' => $field->name,
                                'text' => '{globalset:'.$globalsSet->attributes['id'].':'.$field->handle.'}',
                            ];
                            $variablesList[] = $thisVar;
                        }
                    }
                }
            }
        }

        // Get the URL to our menu icon from our resource bundle
        try {
            Craft::$app->getView()->registerAssetBundle(RichVariablesAsset::class);
        } catch (InvalidConfigException $e) {
            Craft::error($e->getMessage(), __METHOD__);
        }
        $menuIconUrl = Craft::$app->assetManager->getPublishedUrl(
            '@nystudio107/richvariables/assetbundles/richvariables/dist',
            true
        ).'/img/RichVariables-menu-icon.svg';

        // Return everything to our JavaScript encoded as JSON
        $result['variablesList'] = $variablesList;
        $result['menuIconUrl'] = $menuIconUrl;
        $result['useIconForMenu'] = $settings['useIconForMenu'];

        return Json::encode($result);
    }
}
