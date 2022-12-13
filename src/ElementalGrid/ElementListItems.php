<?php

declare(strict_types=1);

namespace WeDevelop\ElementalListItems\ElementalGrid;

use DNADesign\Elemental\Models\BaseElement;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldAddNewButton;
use SilverStripe\Forms\GridField\GridFieldConfig_RelationEditor;
use SilverStripe\Forms\GridField\GridFieldEditButton;
use SilverStripe\Forms\HTMLEditor\HTMLEditorField;
use SilverStripe\Forms\LiteralField;
use SilverStripe\ORM\DataList;
use SilverStripe\ORM\HasManyList;
use SilverStripe\Versioned\GridFieldArchiveAction;
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
    private static string $icon = 'font-icon-book-open';

    private const MODE_CUSTOM = 'custom';

    private const MODE_COLLECTION = 'collection';

    /** @config */
    private static array $db = [
        'Mode' => 'Varchar(255)',
    ];

    /** @config */
    private static array $has_one = [
        'Collection' => Collection::class,
    ];

    /** @config */
    private static array $many_many = [
        'ListItems' => ListItem::class,
    ];

    /** @config */
    private static array $many_many_extraFields = [
        'ListItems' => [
            'ListItemsSort' => 'Int',
        ],
    ];

    /** @config */
    private static array $defaults = [
        'MaxAmount' => 10,
    ];

    public function getCMSFields(): FieldList
    {
        $this->beforeUpdateCMSFields(function (FieldList $fields) {
            $gridConfig = new GridFieldConfig_RelationEditor();

            $gridConfig->removeComponentsByType([
                GridFieldAddNewButton::class,
                GridFieldArchiveAction::class,
                GridFieldEditButton::class,
            ]);

            $gridConfig->addComponent(new GridFieldOrderableRows('ListItemsSort'));

            $fields->removeByName(
                [
                    'Mode',
                    'ListItems',
                    'CollectionID',
                ]
            );

            if ($this->exists()) {
                $fields->addFieldsToTab(
                    'Root.Main',
                    [
                        HTMLEditorField::create('Content', 'Content'),
                        DropdownField::create('Mode', 'List items selection mode', [
                            self::MODE_COLLECTION => 'Choose from collection',
                            self::MODE_CUSTOM => 'Choose custom',
                        ]),
                        Wrapper::create([
                            DropdownField::create('CollectionID', 'Collection', Collection::get()->map()->toArray()),
                        ])->displayIf('Mode')->isEqualTo(self::MODE_COLLECTION)->end(),
                        Wrapper::create([
                            GridField::create('ListItems', 'List items', $this->ListItems(), $gridConfig)->addExtraClass('mt-5'),
                        ])->displayIf('Mode')->isEqualTo(self::MODE_CUSTOM)->end(),
                    ]
                );
            } else {
                $fields->addFieldsToTab('Root.Main', [
                    new LiteralField('', 'Save the element first, in order to be able to make changes to the contents of this collection.'),
                ]);
            }
        });

        return parent::getCMSFields();
    }

    public function getType(): string
    {
        return self::$singular_name;
    }

    public function getItems(): ?DataList
    {
        if ($this->Mode === self::MODE_CUSTOM && $this->ListItems()) {
            return $this->ListItems()->Sort('ListItemsSort');
        }

        if ($this->Mode === self::MODE_COLLECTION && $this->Collection()->exists()) {
            return $this->Collection()->ListItems()->Sort('ListItemsSort');
        }

        return null;
    }
}
