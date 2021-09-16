<?php

namespace Tnt\Wishlist\Admin;

use dry\admin\Module;
use dry\orm\Manager;

class WishlistManager extends Manager
{
    public function __construct()
    {
        // TODO: inject config model
        $model = null;

        parent::__construct($model, [
            'icon' => Module::ICON_GENRE,
            'singular' => 'Wishlist',
            'plural' => 'Wishlist',
        ]);

        // TODO: create
        // TODO: edit
        // TODO: delete
        // TODO: paginator
        // TODO: sorter
        // TODO: filter
    }
}