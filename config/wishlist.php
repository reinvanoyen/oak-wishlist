<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Wishlist Storage
    |--------------------------------------------------------------------------
    |
    | Possible options: session, database
    |
    */
    'storage' => 'database',

    /*
    |--------------------------------------------------------------------------
    | Wishlist Table
    |--------------------------------------------------------------------------
    |
    | Provide a different table name if needed.
    |
    */
    'table' => 'wishlist',

    /*
    |--------------------------------------------------------------------------
    | Wishlist Model
    |--------------------------------------------------------------------------
    |
    | This option allows for the extension of the wishlist Model.
    | Only available when using database storage.
    |
    */
    'model' => Tnt\Wishlist\Model\Wishlist::class
];