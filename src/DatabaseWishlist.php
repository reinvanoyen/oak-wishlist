<?php

namespace Tnt\Wishlist;

use dry\db\FetchException;
use dry\Debug;
use Tnt\Wishlist\Contracts\WishlistableInterface;
use Tnt\Wishlist\Contracts\WishlistInterface;
use Tnt\Wishlist\Contracts\WishlistItemInterface;

class DatabaseWishlist implements WishlistInterface
{
    public $model;

    private $identifier;

    private $items = [];

    public function __construct(WishlistableInterface $wishlistable, string $model)
    {
        $this->model = $model;
        $this->identifier = $wishlistable->getWishlistIdentifier();

        Debug::log($wishlistable->getWishlistIdentifier(), $wishlistable->getWishlistIdentifier());

        $this->restore();
    }

    public function add(WishlistItemInterface $item)
    {
        $classname = get_class($item);

        if ($this->has($item)) {
            return;
        }

        if (!isset($this->items[$classname])) {
            $this->items[$classname] = [];
        }

        $this->items[$classname][] = $item->getWishlistId();

        $this->save();
    }

    public function remove(WishlistItemInterface $item)
    {
        $classname = get_class($item);

        if (! $this->has($item)) {
            return;
        }

        $classNameArray =& $this->items[$classname];

        unset($classNameArray[array_search($item->getWishlistId(), $classNameArray)]);

        $this->save();
    }

    public function has(WishlistItemInterface $item): bool
    {
        $classname = get_class($item);

        return isset($this->items[$classname]) && in_array($item->getWishlistId(), $this->items[$classname]);
    }

    public function clear(): void
    {
        $this->items = [];

        $this->save();
    }

    public function getItems(): array
    {
        $items = [];

        foreach ($this->items as $classname => $ids) {

            if (! class_exists($classname)) {
                continue;
            }

            foreach ($ids as $id) {
                $item = $classname::getByWishlistId($id);

                if (! $item) {
                    continue;
                }

                $items[] = $item;
            }
        }

        return $items;
    }

    private function restore()
    {
        $items = $this->model::all('WHERE identifier = ?', $this->identifier);

        foreach ($items as $item) {
            $class = $item->wishlist_class;
            $id = $item->wishlist_id;

            $item = $class::getByWishlistId($id);

            if (! $item) {
                continue;
            }

            $this->items[$class][] = $id;
        }
    }

    private function save()
    {
        $items = $this->items;
        $identifier = $this->identifier;

        $saveIds = [];

        foreach ($items as $class => $groupedIds) {
            foreach ($groupedIds as $id) {
                $item = null;

                try {
                    $item = $this->model::load_by([
                        'wishlist_class' => $class,
                        'wishlist_id' =>  $id,
                        'identifier' => $identifier
                    ]);
                }
                catch (FetchException $exception) {
                    $item = new $this->model();
                    $item->created = time();
                    $item->updated = time();
                    $item->wishlist_class = $class;
                    $item->wishlist_id = $id;
                    $item->identifier = $identifier;
                    $item->save();
                }

                $saveIds[] = $item->id;
            }
        }

        if (count($saveIds)) {
            $itemsToDelete = $this->model::all('
                WHERE id NOT IN (' . implode(',', $saveIds) . ')
                AND identifier = ?
            ', $identifier);
        }

        else {
            $itemsToDelete = $this->model::all('WHERE identifier = ?', $identifier);
        }

        foreach ($itemsToDelete as $item) {
            $item->delete();
        }
    }
}