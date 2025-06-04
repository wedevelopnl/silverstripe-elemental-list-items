<?php

namespace WeDevelop\ElementalListItems\GridField\Actions;

use SilverStripe\Forms\GridField\GridField_ActionProvider;
use SilverStripe\Forms\GridField\GridField_ColumnProvider;
use SilverStripe\Forms\GridField\GridField_FormAction;

abstract class AbstractDuplicateAction implements
    GridField_ColumnProvider,
    GridField_ActionProvider,
    DuplicateActionInterface
{
    private array $extraData;

    public function __construct(array $extraData = []) {
        $this->extraData = $extraData;
    }

    public function augmentColumns($gridField, &$columns)
    {
        if (!in_array('Actions', $columns, true)) {
            $columns[] = 'Actions';
        }
    }

    public function getExtraData(): array
    {
        return $this->extraData;
    }

    public function getColumnAttributes($gridField, $record, $columnNamme)
    {
        return ['class' => 'grid-field__col-compact'];
    }

    public function getColumnMetadata($gridField, $columnName)
    {
        if ($columnName == 'Actions') {
            return ['title' => ''];
        }

        return [];
    }

    public function getColumnsHandled($gridField)
    {
        return ['Actions'];
    }

    public function getColumnContent($gridField, $record, $columnName)
    {
        if (!$record->canEdit()) {
            return;
        }

        $field = GridField_FormAction::create(
            $gridField,
            'DuplicateAction' . $record->ID,
            false,
            "duplicateobject",
            ['RecordID' => $record->ID]
        )
            ->addExtraClass('gridfield-button-duplicate btn--icon-md font-icon-page-multiple btn--no-text grid-field__icon-action')
            ->setAttribute('title', 'Duplicate ' . $record->singular_name())
            ->setDescription('Duplicate ' . $record->singular_name());

        return $field->Field();
    }

    public function getActions($gridField)
    {
        return ['duplicateobject'];
    }
}
