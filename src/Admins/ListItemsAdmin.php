<?php

declare(strict_types=1);

namespace WeDevelop\ElementalListItems\Admins;

use SilverStripe\Admin\ModelAdmin;
use WeDevelop\ElementalListItems\Models\Collection;
use WeDevelop\ElementalListItems\Models\ListItem;

class ListItemsAdmin extends ModelAdmin
{
    /** @config */
    private static string $url_segment = 'list-items';

    /** @config */
    private static string $menu_title = 'Lists';

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
}
