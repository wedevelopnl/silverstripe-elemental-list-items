<?php

namespace WeDevelop\ElementalListItems\GridField\Actions;

use SilverStripe\Control\Controller;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridField_FormAction;
use SilverStripe\ORM\DataList;
use WeDevelop\ElementalListItems\Models\ListItem;

class CollectionListItemDuplicateAction extends AbstractDuplicateAction
{
    public const COLLECTION_ID_KEY = 'CollectionID';

    public function handleAction(GridField $gridField, $actionName, $arguments, $data)
    {
        if ($actionName == 'duplicateobject') {
            $extraData = $this->getExtraData();
            if (!array_key_exists(self::COLLECTION_ID_KEY, $extraData)) {
                throw new \RuntimeException(self::COLLECTION_ID_KEY . ' is not passed to constructor');
            }

            /** @var DataList $list */
            $list = $gridField->getList();
            /** @var ListItem $item */
            $item = $list->byID($arguments['RecordID']);

            $clonedItem = $item->duplicate(true, false);

            $clonedItem->Title = sprintf('%s (%s)', $clonedItem->Title, 'copy');
            $clonedItem->Collections()->add($extraData[self::COLLECTION_ID_KEY]);

            $clonedItem->write();

            Controller::curr()->getResponse()->setStatusCode(
                200,
                "{$item->Title} Duplicated"
            );
        }
    }
}