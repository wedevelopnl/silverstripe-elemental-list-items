<?php

namespace WeDevelop\ElementalListItems\GridField\Actions;

use SilverStripe\Control\Controller;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\ORM\DataList;
use WeDevelop\ElementalListItems\Models\Collection;
use WeDevelop\ElementalListItems\Models\ListItem;

class CollectionDuplicateAction extends AbstractDuplicateAction
{
//    On Duplicate Collection:
//
//    - Create new Collection
//    - Duplicate List Items
//    - Unlink all relations on ListItem
//    - Link to new Duplicate
    public function handleAction(GridField $gridField, $actionName, $arguments, $data)
    {
        if ($actionName == 'duplicateobject') {
            /** @var DataList $list */
            $list = $gridField->getList();

            /** @var Collection $originalCollection */
            $originalCollection = $list->byID($arguments['RecordID']);

            $clonedCollection = $originalCollection->duplicate(true, false);

            $clonedCollection->Title = sprintf('%s (%s)', $clonedCollection->Title, 'copy');

            $originalCollection->ListItems()->each(static function (ListItem $item) use ($clonedCollection) {
                $clonedItem = $item->duplicate(true, false);

                $clonedCollection->ListItems()->add($clonedItem);
            });

            $clonedCollection->write();

            Controller::curr()->getResponse()->setStatusCode(
                200,
                "{$originalCollection->Title} Duplicated"
            );
        }
    }
}
