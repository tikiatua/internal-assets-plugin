<?php
/**
 * Internal Assets plugin for Craft CMS 3.x
 *
 * A simple plugin to restrict access to assets for permitted users only. Access to a given asset is only granted if the user has view-permissions for the given source (this can be set in the user- or group-settings).
 *
 * The asset source folder should be moved out of the web root folder so the files are never accessible without this plugin.
 *
 * @link      https://github.com/tikiatua
 * @copyright Copyright (c) 2018 Ramon Saccilotto
 */

namespace saccilottoconsulting\internalassets;

use Craft;
use craft\services\Plugins;

# classes for custom volume registration
use craft\services\Volumes;
use craft\events\RegisterComponentTypesEvent;
use saccilottoconsulting\internalassets\volumes\MemberOnlyVolume;

# classes for custom route handler registration
use craft\events\PluginEvent;
use craft\web\UrlManager;
use craft\events\RegisterUrlRulesEvent;

use yii\base\Event;

/**
 * @author    Ramon Saccilotto
 * @package   InternalAssets
 * @since     2.0.0
 */
class InternalAssets extends \craft\base\Plugin
{
    // Static Properties
    // =========================================================================

    /**
     * Static property that is an instance of this plugin class so that it can be accessed via
     * InternalAssets::$plugin
     *
     * @var InternalAssets
     */
    public static $plugin;

    // Public Properties
    // =========================================================================

    /**
     * To execute your plugin’s migrations, you’ll need to increase its schema version.
     *
     * @var string
     */
    public $schemaVersion = '2.0.0';

    // Public Methods
    // =========================================================================

    /**
     * Set our $plugin static property to this class so that it can be accessed via
     * InternalAssets::$plugin
     *
     * Called after the plugin class is instantiated; do any one-time initialization
     * here such as hooks and events.
     *
     * If you have a '/vendor/autoload.php' file, it will be loaded for you automatically;
     * you do not need to load it in your init() method.
     *
     */
    public function init()
    {
        parent::init();
        self::$plugin = $this;

        // Register a custom volume type
        Event::on(
            Volumes::class, 
            Volumes::EVENT_REGISTER_VOLUME_TYPES, function(RegisterComponentTypesEvent $event) {
            $event->types[] = MemberOnlyVolume::class;
        });

        // Register specific routes to handle internal assets
        Event::on(
            UrlManager::class,
            UrlManager::EVENT_REGISTER_SITE_URL_RULES,
            function (RegisterUrlRulesEvent $event) {
                $event->rules['internal/<path:.*>'] = 'internal-assets/default/fetch';
            }
        );

/**
 * Logging in Craft involves using one of the following methods:
 *
 * Craft::trace(): record a message to trace how a piece of code runs. This is mainly for development use.
 * Craft::info(): record a message that conveys some useful information.
 * Craft::warning(): record a warning message that indicates something unexpected has happened.
 * Craft::error(): record a fatal error that should be investigated as soon as possible.
 *
 * Unless `devMode` is on, only Craft::warning() & Craft::error() will log to `craft/storage/logs/web.log`
 *
 * It's recommended that you pass in the magic constant `__METHOD__` as the second parameter, which sets
 * the category to the method (prefixed with the fully qualified class name) where the constant appears.
 *
 * To enable the Yii debug toolbar, go to your user account in the AdminCP and check the
 * [] Show the debug toolbar on the front end & [] Show the debug toolbar on the Control Panel
 *
 * http://www.yiiframework.com/doc-2.0/guide-runtime-logging.html
 */
        Craft::info(
            Craft::t(
                'internal-assets',
                '{name} plugin loaded',
                ['name' => $this->name]
            ),
            __METHOD__
        );
    }

    // Protected Methods
    // =========================================================================

}
