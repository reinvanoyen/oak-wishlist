# oak-wishlist
### A simple wishlist implementation for DRY applications

#### Installation

```ssh
composer require reinvanoyen/oak-wishlist
```

#### Example usage

##### Prepare your item

```php
<?php

use Tnt\Wishlist\Contracts\WishlistItemInterface;

class Product implements WishlistItemInterface
{
    public static function getByWishlistId(int $id): ?WishlistItemInterface
    {
        // get the product by id
    }
    
    public function getWishlistId(): int
    {
        return 1;
    }
    
    public function serialize(): array
    {
        return [
            'id' => $this->getWishListId(),
            'title' => 'Your wishlistable product #1',
        ];
    }
}

```
##### Use of the facade

```php
<?php

use Tnt\Wishlist\Facade\Wishlist;

$product = new Product();

// Add an item
Wishlist::add($product);

// Remove an item
Wishlist::remove($product);

// Check if an item is wishlisted
if (Wishlist::has($product)) {
    echo 'Yes, it is a wishlisted item!';
}

// Retrieve all wishlisted items
Wishlist::getItems();

// Clear all items from the wishlist
Wishlist::clear();
```