<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Wishlist Driver
    |--------------------------------------------------------------------------
    |
    | Possible options: session, database
    |
    */
    'driver' => 'database',

    /*
    |--------------------------------------------------------------------------
    | Wishlist Model
    |--------------------------------------------------------------------------
    |
    | This option allows for the extension of the wishlist Model.
    | Only available when using database storage.
    |
    */
    'model' => Tnt\Wishlist\Model\Wishlist::class,

    /*
    |--------------------------------------------------------------------------
    | Wishlist Identifier
    |--------------------------------------------------------------------------
    |
    | When the database option is selected, we need an identifier to attach the
    | Wishlist items to.
    |
    | You can specify the Model that implements WishlistableInterface here.
    |
    */
    'identifier' => app\model\User::class,
];