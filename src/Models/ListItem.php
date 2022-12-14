<?php

declare(strict_types=1);

namespace WeDevelop\ElementalListItems\Models;

use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldConfig_RecordViewer;
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
    private static string $table_name = 'WeDevelop_ElementalListItems_ListItem';

    /** @config */
    private static string $singular_name = 'List item';

    /** @config */
    private static string $plural_name = 'List items';

    /** @config */
    private static string $icon_class = 'font-icon-rocket';

    /**
     * @var array<string, string>
     * @config
     */
    private static array $db = [
        'Title' => 'Varchar(255)',
        'Content' => 'Varchar(255)',
    ];

    /**
     * @var array<string>
     * @config
     */
    private static array $summary_fields = [
        'Title',
    ];

    /**
     * @var array<string, string>
     * @config
     */
    private static array $belongs_many_many = [
        'Collections' => Collection::class,
        'ElementListItems' => ElementListItems::class,
    ];

    public function getCMSFields(): FieldList
    {
        $fields = parent::getCMSFields();

        $fields->removeByName([
            'Collections',
            'ElementListItems',
        ]);

        $fields->addFieldsToTab('Root.Collections existing in', [
            GridField::create('Collections', _t(__CLASS__ . '.COLLECTIONS', 'Collections'), $this->Collections(), new GridFieldConfig_RecordViewer()),
        ]);

        $fields->addFieldsToTab('Root.Grid elements used in', [
            GridField::create('ElementListItems', _t(__CLASS__ . '.GRID_ELEMENTS', 'Grid elements'), $this->ElementListItems(), new GridFieldConfig_RecordViewer()),
        ]);

        return $fields;
    }
}
