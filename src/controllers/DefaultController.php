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

use Craft;
use craft\web\Controller;
use craft\helpers\Json;
use craft\fields\PlainText;
use craft\fields\Number;
use craft\fields\Date;
use craft\fields\Dropdown;

/**
 * @author    nystudio107
 * @package   RichVariables
 * @since     1.0.0
 */
class DefaultController extends Controller
{

    // Protected Properties
    // =========================================================================

    /**
     * @var    bool|array Allows anonymous access to this controller's actions.
     *         The actions must be in 'kebab-case'
     * @access protected
     */
    protected $allowAnonymous = ['index'];

    // Public Methods
    // =========================================================================

    /**
     * @return mixed
     */
    public function actionIndex()
    {
        $result = array();

        // Get the global set to use
        $settings = Craft::$app->getPlugins()->getPlugin('richvariables')->getSettings();
        $globalsSet = Craft::$app->getGlobals()->getSetByHandle($settings['globalSetHandle']);
        // Grab the first global set if they haven't specified one yet
        if (!$globalsSet) {
            $allGlobalsSetIds = Craft::$app->getGlobals()->getAllSetIds();
            if (!empty($allGlobalsSetIds)) {
                //$globalsSet = Craft::$app->globals->getSetById($allGlobalsSetIds[0]);
            }
        }
        if ($globalsSet) {
            // Get the fieldlayout fields used for this global set
            $fieldLayoutFields = $globalsSet->getFieldLayout()->getFields();
            foreach ($fieldLayoutFields as $fieldLayoutField) {
                // Get the actual field, and check that it's type is something we support
                $field = Craft::$app->getFields()->getFieldById($fieldLayoutField->id);
                $fieldType = get_class($field);
                if (
                    ($field instanceof PlainText) || (is_subclass_of($field, PlainText::class)) ||
                    ($field instanceof Number) || (is_subclass_of($field, Number::class)) ||
                    ($field instanceof Date) || (is_subclass_of($field, Date::class)) ||
                    ($field instanceof Dropdown) || (is_subclass_of($field, Dropdown::class))
                    ) {
                    // Add the field title and Reference Tag as per https://craftcms.com/docs/reference-tags
                    $thisVar = array(
                        'title' => $field->name,
                        'text' => "{globalset:" . $globalsSet->attributes['id'] . ":" . $field->handle . "}",
                        );
                    array_push($result, $thisVar);
                }
            }
        }

        return Json::encode($result);
    }
}
