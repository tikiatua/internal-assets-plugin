<?php
namespace Craft;

class InternalAssetsService extends BaseApplicationComponent
{
	public function onBeforeSendFile(Event $event)
	{
		$this->raiseEvent('onBeforeSendFile', $event);
	}
}