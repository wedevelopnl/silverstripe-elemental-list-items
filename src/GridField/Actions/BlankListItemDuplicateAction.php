<?php

namespace WeDevelop\ElementalListItems\GridField\Actions;

use SilverStripe\Control\Controller;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\ORM\DataList;

class BlankListItemDuplicateAction extends AbstractDuplicateAction
{
    public function handleAction(GridField $gridField, $actionName, $arguments, $data)
    {
        if ($actionName == 'duplicateobject') {
            /** @var DataList $list */
            $list = $gridField->getList();
            $item = $list->byID($arguments['RecordID']);

            $clone = $item->duplicate(true, false);

            $clone->Title = sprintf('%s (%s)', $clone->Title, 'copy');
            $clone->write();

            Controller::curr()->getResponse()->setStatusCode(
                200,
                "{$item->Title} Duplicated"
            );
        }
    }
}