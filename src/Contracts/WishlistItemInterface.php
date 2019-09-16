<?php

namespace Tnt\Wishlist\Contracts;

interface WishlistItemInterface
{
	/**
	 * @param int $id
	 * @return null|WishlistItemInterface
	 */
	public static function getByWishlistId(int $id): ?WishlistItemInterface;

	/**
	 * @return int
	 */
	public function getWishlistId(): int;

	/**
	 * @return array
	 */
	public function serialize(): array;
}