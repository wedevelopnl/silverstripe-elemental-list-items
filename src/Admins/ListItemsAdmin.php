<?php

declare(strict_types=1);

namespace WeDevelop\ElementalListItems\Admins;

use App\Forms\GridFieldDuplicateAction;
use SilverStripe\Admin\ModelAdmin;
use SilverStripe\Forms\GridField\GridField;
use WeDevelop\ElementalListItems\GridField\Actions\BlankListItemDuplicateAction;
use WeDevelop\ElementalListItems\GridField\Actions\CollectionDuplicateAction;
use WeDevelop\ElementalListItems\Models\Collection;
use WeDevelop\ElementalListItems\Models\ListItem;

class ListItemsAdmin extends ModelAdmin
{
    /** @config */
    private static string $url_segment = 'list-items';

    /** @config */
    private static string $menu_title = 'List items';

    /** @config */
    private static string $menu_icon_class = 'font-icon-list';

    /**
     * @var array<string>
     * @config
     */
    private static array $managed_models = [
        Collection::class,
        ListItem::class,
    ];

    public function getEditForm($id = null, $fields = null)
    {
        $form = parent::getEditForm($id, $fields);

        $modelClass = $this->modelClass;

        if ($modelClass === Collection::class && Collection::config()->get(Collection::ENABLE_DUPLICATION_KEY)) {
            /** @var GridField $gridField */
            $gridField = $form->Fields()->dataFieldByName($this->sanitiseClassName($this->getOwner()->modelClass));
            $gridField->getConfig()->addComponent(new CollectionDuplicateAction());
        }

        if ($modelClass === ListItem::class && ListItem::config()->get(ListItem::ENABLE_DUPLICATION_KEY)) {
            /** @var GridField $gridField */
            $gridField = $form->Fields()->dataFieldByName($this->sanitiseClassName($this->getOwner()->modelClass));
            $gridField->getConfig()->addComponent(new BlankListItemDuplicateAction());
        }

        return $form;
    }
}
