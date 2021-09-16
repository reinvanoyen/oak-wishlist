<?php

namespace Tnt\Wishlist\Contracts;

/**
 * Interface WishlistInterface
 * @package Tnt\Wishlist\Contracts
 */
interface WishlistInterface
{
	/**
	 * Add an item
	 *
	 * @param WishlistItemInterface $item
	 * @return void
	 */
	public function add(WishlistItemInterface $item);

	/**
	 * Remove an item
	 *
	 * @param WishlistItemInterface $item
	 * @return void
	 */
	public function remove(WishlistItemInterface $item);

	/**
	 * Checks if the item is on the wishlist
	 *
	 * @param WishlistItemInterface $item
	 * @return bool
	 */
	public function has(WishlistItemInterface $item): bool;

	/**
	 * Removes all items
	 *
	 * @return void
	 */
	public function clear(): void;

	/**
	 * Gets all items
	 *
	 * @return array
	 */
	public function getItems(): array;
}