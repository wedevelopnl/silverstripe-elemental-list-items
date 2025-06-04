<?php

namespace WeDevelop\ElementalListItems\GridField\Actions;

use SilverStripe\Forms\GridField\GridField;

interface DuplicateActionInterface
{
    public function handleAction(GridField $gridField, $actionName, $arguments, $data);
}