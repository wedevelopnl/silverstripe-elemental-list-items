<?php

declare(strict_types=1);

namespace WeDevelop\ElementalListItems\Models;

use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldConfig_RecordViewer;
use SilverStripe\Forms\GridField\GridFieldConfig_RelationEditor;
use SilverStripe\Forms\LiteralField;
use SilverStripe\Forms\TextField;
use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\HasManyList;
use SilverStripe\ORM\ManyManyList;
use Symbiote\GridFieldExtensions\GridFieldOrderableRows;
use WeDevelop\ElementalListItems\ElementalGrid\ElementListItems;

/**
 * @property string $Title
 * @method ListItem|ManyManyList ListItems()
 * @method ElementListItems|HasManyList ElementListItems()
 */
class Collection extends DataObject
{
    /** @config */
    private static string $table_name = 'WeDevelop_ElementalListItems_Collection';

    /** @config */
    private static string $singular_name = 'Collection';

    /** @config */
    private static string $plural_name = 'Collections';

    /** @config */
    private static string $icon_class = 'font-icon-rocket';

    /**
     * @var array<string, string>
     * @config
     */
    private static array $db = [
        'Title' => 'Varchar(255)',
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
    private static array $has_many = [
        'ElementListItems' => ElementListItems::class,
    ];

    /**
     * @var array<string, string>
     * @config
     */
    private static array $many_many = [
        'ListItems' => ListItem::class,
    ];

    /**
     * @var array<string, array<string, string>>
     * @config
     */
    private static array $many_many_extraFields = [
        'ListItems' => [
            'ListItemsSort' => 'Int',
        ],
    ];

    public function getCMSFields(): FieldList
    {
        $fields = parent::getCMSFields();

        $listItemsGridConfig = new GridFieldConfig_RelationEditor();
        $listItemsGridConfig->addComponent(new GridFieldOrderableRows('ListItemsSort'));

        $elementalGridConfig = new GridFieldConfig_RecordViewer();

        $fields->removeByName([
            'ListItems',
            'ElementListItems',
        ]);

        if ($this->exists()) {
            $fields->addFieldsToTab('Root.Main', [
                TextField::create('Title', _t(__CLASS__ . '.TITLE', 'Title')),
                GridField::create(
                    'ListItems',
                    _t(__CLASS__ . '.LIST_ITEMS', 'List items'),
                    $this->ListItems(),
                    $listItemsGridConfig
                ),
            ]);

            $fields->addFieldsToTab('Root.Grid elements used in', [
                GridField::create(
                    'ElementListItems',
                    _t(__CLASS__ . '.GRID_ELEMENTS', 'Grid elements'),
                    $this->ElementListItems()->filter([
                        'Mode' => 'Collection',
                    ]),
                    $elementalGridConfig
                ),
            ]);
        } else {
            $fields->addFieldsToTab('Root.Main', [
                new LiteralField('', _t(
                    __CLASS__ . '.SAVE_FIRST_WARNING',
                    'Save the collection first, in order to be able to make changes to the contents of this collection.'
                )),
            ]);
        }

        return $fields;
    }
}
