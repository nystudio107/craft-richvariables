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
    function setWithExpiry(key, value, ttl) {
        const now = new Date()
        // `item` is an object which contains the original value
        // as well as the time when it's supposed to expire
        const item = {
            value: value,
            expiry: now.getTime() + ttl,
        }
        localStorage.setItem(key, JSON.stringify(item))
    }

    function getWithExpiry(key) {
        const itemStr = localStorage.getItem(key)
        // if the item doesn't exist, return null
        if (!itemStr) {
            return null
        }
        const item = JSON.parse(itemStr)
        const now = new Date()
        // compare the expiry time of the item with the current time
        if (now.getTime() > item.expiry) {
            // If the item is expired, delete the item from storage
            // and return null
            localStorage.removeItem(key)
            return null
        }
        return item.value
    }

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

            // Try to grab the menu from our local storage cache if possible
            var cachedResponseVars = getWithExpiry('rich-variables-menu-cache');
            if (cachedResponseVars === null) {
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
            }
        },
        start: function()
        {
            var dropdown = {};
            // Try to grab the menu from our local storage cache if possible
            var responseVars = getWithExpiry('rich-variables-menu-cache');
            if (responseVars === null && this.request) {
                responseVars = JSON.parse(this.request.responseText);
                setWithExpiry('rich-variables-menu-cache', responseVars, 60 * 1000)
            }
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
