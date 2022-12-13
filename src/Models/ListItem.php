<?php

declare(strict_types=1);

namespace WeDevelop\ElementalListItems\Models;

use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldConfig_RecordViewer;
use SilverStripe\Forms\LiteralField;
use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\ManyManyList;
use WeDevelop\ElementalListItems\ElementalGrid\ElementListItems;

/**
 * @method ElementListItems|ManyManyList ElementListItems()
 * @method Collection|ManyManyList Collections()
 */
class ListItem extends DataObject
{
    /** @config */
    private static string $table_name = 'WeDevelop_ElementalListItems_Collection';

    /** @config */
    private static string $singular_name = 'Collection';

    /** @config */
    private static string $plural_name = 'Collections';

    /** @config */
    private static string $icon_class = 'font-icon-rocket';

    /** @config */
    private static array $db = [
        'Title' => 'Varchar(255)',
        'Content' => 'Varchar(255)',
    ];

    /** @config */
    private static array $summary_fields = [
        'Title',
    ];

    /** @config */
    private static array $belongs_many_many = [
        'Collections' => Collection::class,
        'ElementListItems' => ElementListItems::class,
    ];

    /** @config */
    private static array $many_many_extraFields = [
        'Collections' => [
            'CollectionsSort' => 'Int',
        ],
    ];

    public function getCMSFields(): FieldList
    {
        $fields = parent::getCMSFields();

        $fields->removeByName([
            'Collections',
            'ElementListItems',
            'Title',
            'Content',
        ]);

        if ($this->exists()) {
            $fields->addFieldsToTab('Root.Collections existing in', [
                GridField::create('Collections', 'Collections', $this->Collections(), new GridFieldConfig_RecordViewer()),
            ]);

            $fields->addFieldsToTab('Root.Grid elements used in', [
                GridField::create('ElementListItems', 'Grid elements', $this->ElementListItems(), new GridFieldConfig_RecordViewer()),
            ]);
        } else {
            $fields->addFieldsToTab('Root.Main', [
                new LiteralField('', 'Save the collection first, in order to be able to make changes to the contents of this collection.'),
            ]);
        }

        return $fields;
    }
}
