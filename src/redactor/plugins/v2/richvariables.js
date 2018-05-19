/**
 * Rich Variables plugin for Craft CMS
 *
 * Rich Variables JS
 *
 * @author    nystudio107
 * @copyright Copyright (c) 2017 nystudio107
 * @link      https://nystudio107.com
 * @package   RichVariables
 * @since     1.0.18
 */

(function($R)
{
    $R.add('plugin', 'richvariables', {
        translations: {
            en: {
                "variables": "Variables"
            }
        },
        init: function(app)
        {
            this.app = app;
            this.lang = app.lang;
            this.inline = app.inline;
            this.toolbar = app.toolbar;
            this.insertion = app.insertion;

            // Grab the globals set Reference Tags from our controller
            var request = new XMLHttpRequest();
            request.open('GET', Craft.getActionUrl('rich-variables'), false);
            request.onload = function() {
                if (request.status >= 200 && request.status < 400) {
                } else {
                }
            };
            request.send();
            this.request = request;
        },
        start: function()
        {
            var dropdown = {};
            var responseVars = JSON.parse(this.request.responseText);

            // Iterate through each menu item, adding them to our dropdown
            responseVars.variablesList.forEach(function(menuItem, index) {
                var key = 'point' + (index + 1);
                var refTag = '<ins>' + menuItem.text + '</ins>';
                dropdown[key] = {
                    title: menuItem.title,
                    api: 'plugin.richvariables.insert',
                    args: refTag
                };
            });
            // Handle empty menu items
            if (responseVars.variablesList.length === 0) {
                dropdown.point1 = {
                    title: "No Globals Found",
                    func: function(buttonName) {
                        // NOP
                    },
                };
            }
            // Add the button and dropdown
            var $button = this.toolbar.addButton('variables', { title: this.lang.get('variables') });
            $button.setDropdown(dropdown);
            if (responseVars.useIconForMenu) {
                $button.setIcon('<img src="' + responseVars.menuIconUrl + '" height="16" width="16" style="margin-top: -2px;">');
            }
        },
        insert: function(refTag)
        {
            this.insertion.insertRaw(refTag);
        }
    });
})(Redactor);
