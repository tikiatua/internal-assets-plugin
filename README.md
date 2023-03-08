# Internal Assets plugin for Craft CMS 4.x

A simple plugin to restrict access to assets for permitted users only. Access to
 a given asset is only granted if the user has view-permissions for the given 
 source (this can be set in the user- or group-settings).

The asset source folder should be moved out of the web root folder so the files 
are never accessible without this plugin.

![Screenshot](resources/img/plugin-logo.png)

## Requirements

This plugin was tested with Craft CMS Version 4.4, but should work with any
Craft 4 installation.

## Installation

To install the plugin, follow these instructions.

1. Open your terminal and go to your Craft project:

        cd /path/to/project

2. Then tell Composer to load the plugin:

        composer require saccilottoconsulting/craft-internal-assets

3. In the Control Panel, go to Settings → Plugins and click the “Install” button
   for Internal Assets.

## Internal Assets Overview

The plugin installs a custom url matcher, which captures all requests on 
/internal/*. The request is then checked against all volumes to which the 
current user has view access. A 401 error (unauthorized) will be returned, if
the user is not logged in or does not have access to the given volume.

Matching of the volume is perfomed by comparing the url with the public path
of the file system of the volumes that the user has access to.

If the file is found, then the contents of the file are streamed to the client.
Otherwise a 404 error (not found) is returned.

## Configuring Internal Assets

There are currently no configuration options for the plugin. However, you need
to enable publicPaths on the filesystem that you want to use and prefix the 
url path with /internal, e.g. /internal/assets

## Internal Assets Roadmap

The following additional features are currently planned

* Allow the use of image transformations on assets (currently not supported)
* Enable custom path prefixes for internal assets

Brought to you by [Ramon Saccilotto](https://github.com/tikiatua)
