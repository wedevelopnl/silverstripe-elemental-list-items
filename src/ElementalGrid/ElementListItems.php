<?php

declare(strict_types=1);

namespace WeDevelop\ElementalListItems\ElementalGrid;

use DNADesign\Elemental\Models\BaseElement;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldConfig_RelationEditor;
use SilverStripe\Forms\HTMLEditor\HTMLEditorField;
use SilverStripe\ORM\DataList;
use SilverStripe\ORM\HasManyList;
use Symbiote\GridFieldExtensions\GridFieldOrderableRows;
use UncleCheese\DisplayLogic\Forms\Wrapper;
use WeDevelop\ElementalListItems\Models\Collection;
use WeDevelop\ElementalListItems\Models\ListItem;

/**
 * @property int $CollectionID
 * @property string $Mode
 * @method ListItem|HasManyList ListItems()
 * @method Collection Collection()
 */
class ElementListItems extends BaseElement
{
    /** @config */
    private static string $table_name = 'WeDevelop_ElementalListItems_Element_ListItems';

    /** @config */
    private static string $singular_name = 'List items';

    /** @config */
    private static string $plural_name = 'List items';

    /** @config */
    private static string $description = 'Show an overview of list items in a grid element';

    /** @config */
    private static string $icon = 'font-icon-list';

    /**
     * @var array<string, string>
     * @config
     */
    private static array $db = [
        'Mode' => 'Enum(["Custom", "Collection"])',
    ];

    /**
     * @var array<string, string>
     * @config
     */
    private static array $has_one = [
        'Collection' => Collection::class,
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

    /**
     * @var array<string, mixed>
     * @config
     */
    private static array $defaults = [
        'MaxAmount' => 10,
        'Mode' => 'Collection',
    ];

    public function getCMSFields(): FieldList
    {
        $this->beforeUpdateCMSFields(function (FieldList $fields) {
            $gridConfig = new GridFieldConfig_RelationEditor();
            $gridConfig->addComponent(new GridFieldOrderableRows('ListItemsSort'));

            $fields->removeByName(
                [
                    'Mode',
                    'ListItems',
                    'CollectionID',
                ]
            );

            $fields->addFieldsToTab(
                'Root.Main',
                [
                    HTMLEditorField::create('Content', _t(__CLASS__ . '.CONTENT', 'Content')),
                    DropdownField::create('Mode', _t(__CLASS__ . '.SELECTION_MODE', 'List items selection mode'), [
                        'Collection' => _t(__CLASS__ . 'COLLECTION_SELECTION', 'Choose from collection'),
                        'Custom' => _t(__CLASS__ . 'LIST_ITEMS_SELECTION', 'Pick list items'),
                    ]),
                    Wrapper::create([
                        DropdownField::create('CollectionID', _t(Collection::class . '.SINGULARNAME', 'Collection'), Collection::get()->map()->toArray()),
                    ])->displayIf('Mode')->isEqualTo('Collection')->end(),
                    Wrapper::create([
                        GridField::create('ListItems', _t(ListItem::class . '.PLURALNAME', 'List items'), $this->ListItems(), $gridConfig)->addExtraClass('mt-5'),
                    ])->displayIf('Mode')->isEqualTo('Custom')->end(),
                ]
            );
        });

        return parent::getCMSFields();
    }

    public function getType(): string
    {
        return self::$singular_name;
    }

    public function getItems(): ?DataList
    {
        return match($this->Mode) {
            'collection' => $this->Collection()->exists() ? $this->Collection()->ListItems() : null,
            'custom' => $this->ListItems()->Sort('ListItemsSort'),
            default => null,
        };
    }
}
