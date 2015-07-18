# Craft CMS: Internal Assets Plugin #

## Description
A simple plugin to restrict access to assets for permitted users only. Access to a given asset is only granted if the user has view-permissions for the given source (this can be set in the user- or group-settings).

The asset source folder should be moved out of the webroot (set it to ../files for example) so the files are never accessible without this plugin.

## Setup & Use
The plugin registers some additional routes to check the asset permissions before it is served to the user. You can serve the asset using its fileid or the url of the file (as defined in the asset-folder configuration)

	- internal/<fileid>
	- internal/<folder>/<filename>

I would recommend to create some [environmnentVariables](http://buildwithcraft.com/docs/multi-environment-configs) to store the basepath of your assets folder and the base url of the assets-url for convenient use in the admin-panel.

**Please note that the internal assets url should use an absolute url to the "internal" route**

<pre>
define('CRAFT_SITE_URL', "http://craft.dev:8888/");

return array(
	'.dev' => array(
		'siteUrl' => CRAFT_SITE_URL,
		'environmentVariables' => array(
			'internalAssetsPath' => CRAFT_BASE_PATH . "../_files/",
			'internalAssetsUrl' => CRAFT_SITE_URL . "internal/"
		)
	)
);
</pre>

You can then use placeholders in the assets configuration.

	i.e. for a asset folder called documents
	- directory: {internalAssetsPath}documents/
	- url: {internalAssetsUrl}documents/



## Attribution
This plugin is based on the Member Assets Plugin by Jeroen Kenters, but extends it's functionality.
