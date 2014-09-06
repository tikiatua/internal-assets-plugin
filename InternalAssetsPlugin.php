<?php

namespace Craft;

class InternalAssetsPlugin extends BasePlugin
{
    public function getName()
    {
        return Craft::t('Internal Assets');
    }

    public function getVersion()
    {
        return '1.0';
    }

    public function getDeveloper()
    {
        return 'Dr. Ramon Saccilotto';
    }

    public function getDeveloperUrl()
    {
        return 'https://github.com/tikiatua';
    }

    public function hasCpSection()
    {
        return false;
    }

    /**
     * Register control panel routes
     */
    public function registerSiteRoutes()
    {
        // NOTE: the second route is used to capture files in subdirectories as well
        return array(
            'internal/(?P<id>.\d*)' => array('action' => 'InternalAssets/Assets/download'),
            'internal/(?P<directory>.*)/(?P<name>.*)' => array('action' => 'InternalAssets/Assets/view')
        );
    }
}
