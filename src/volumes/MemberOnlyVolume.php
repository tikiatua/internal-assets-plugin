<?php

namespace saccilottoconsulting\internalassets\volumes;

use Craft;

class MemberOnlyVolume extends \craft\volumes\Local
{
	public static function displayName(): string
	{
		return Craft::t('internal-assets', 'volume-name');
	}

	// public urls are checked for permissions before download is granted
	public $hasUrls = true;

	public function init()
	{
		parent::init();
	}

	public function getSettingsHtml()
	{
		return Craft::$app->getView()->renderTemplate('internal-assets/volumeSettings', [
			'volume' => $this
		]);
	}

	// prefix the root url with internal to make sure that the internal assets
	// plugin is used to check for permissions
	public function getRootUrl()
	{
		$userDefinedRootUrl = parent::getRootUrl();

		// Note: make url adaption more robust by parsing the url elements
		// and rebuilding it again. Should also correctly handle aliases such as
		// @web.
		// $url = parse_url($userDefinedRootUrl);

		// strip any prefix
		$userDefinedRootUrl = ltrim($userDefinedRootUrl, '/');
		$userDefinedRootUrl = ltrim($userDefinedRootUrl, '//');

		return "/internal/" . $userDefinedRootUrl;
	}

}