<?php
/**
 * Internal Assets plugin for Craft CMS 4.x
 *
 * A simple plugin to restrict access to assets for defined users only. The 
 * asset source folder should be moved out of the web root folder so the files 
 * are never accessible without this plugin.
 *
 * @link      https://github.com/tikiatua
 * @copyright Copyright (c) 2023 Ramon Saccilotto
 */

namespace saccilottoconsulting\internalassets;

use yii\base\Event;

// add custom permissions (may not be necessary)
use craft\events\RegisterUserPermissionsEvent;
use craft\services\UserPermissions;

// add url handlers
use craft\web\UrlManager;
use craft\events\RegisterUrlRulesEvent;

// add a custom volume
// note: custom volume types do not seem to be supported yet
# use craft\events\RegisterComponentTypesEvent;
# use craft\services\Volumes;


class Plugin extends \craft\base\Plugin
{
    public function init()
    {
        parent::init();
        
        Event::on(
            UserPermissions::class, 
            UserPermissions::EVENT_REGISTER_PERMISSIONS, 
            function(RegisterUserPermissionsEvent $event) {
                $event->permissions[] = [
                    "heading" => "Volume Permissions",
                    "permissions" => [
                        "permissionName" => [
                            "label" => "Allow access to permissions"
                        ]
                    ]
                ];
            }
        );

        Event::on(
            UrlManager::class,
            UrlManager::EVENT_REGISTER_SITE_URL_RULES,
            function (RegisterUrlRulesEvent $event) {
                $event->rules['internal/<path:.*>'] = 'internal-assets/access/fetch';
            }
        );

        // Custom volumes types do not seem to be supported yet
        // Event::on(
        //     Volumes::class,
        //     Volumes::EVENT_REGISTER_VOLUME_TYPES,
        //         function(RegisterComponentTypesEvent $event) {
        //         $event->types[] = MyVolume::class;
        //     }
        // );

    }
}
