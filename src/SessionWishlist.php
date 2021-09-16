<?php

namespace Tnt\Wishlist;

use Oak\Session\Facade\Session;
use Tnt\Wishlist\Contracts\WishlistInterface;
use Tnt\Wishlist\Contracts\WishlistItemInterface;

class SessionWishlist implements WishlistInterface
{
	/**
	 * @var array $items
	 */
	private $items = [];

	/**
	 * Wishlist constructor.
	 */
	public function __construct()
	{
		$this->restore();
	}

	/**
	 * @param WishlistItemInterface $item
	 */
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

	/**
	 * @param WishlistItemInterface $item
	 * @return void
	 */
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

	/**
	 * @param WishlistItemInterface $item
	 * @return bool
	 */
	public function has(WishlistItemInterface $item): bool
	{
		$classname = get_class($item);

		return isset($this->items[$classname]) && in_array($item->getWishlistId(), $this->items[$classname]);
	}

	/**
	 * @return void
	 */
	public function clear(): void
	{
		$this->items = [];
		$this->save();
	}

	/**
	 * @return array
	 */
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

	/**
	 * Restores the items from the session
	 */
	private function restore()
	{
		$items = Session::get('wishlist');
		$this->items = $items ? $items : [];
	}

	/**
	 * Saves the items to the session
	 */
	private function save()
	{
		Session::set('wishlist', $this->items);
		Session::save();
	}
}