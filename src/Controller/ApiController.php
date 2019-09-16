<?php

namespace Tnt\Wishlist\Controller;

use Tnt\InternalApi\Exception\ApiException;
use Tnt\InternalApi\Http\Request;
use Tnt\Wishlist\Contracts\WishlistItemInterface;
use Tnt\Wishlist\Facade\Wishlist;

class ApiController
{
	private static function validateClass(string $classname)
	{
		if (! class_exists($classname)) {
			throw new ApiException('class_not_found');
		}

		$implements = class_implements($classname);

		if (! in_array(WishlistItemInterface::class, $implements)) {
			throw new ApiException('invalid_class');
		}
	}

	public static function items(Request $request)
	{
		$wishlistItems = array_map(function($item) {
			return $item->serialize();
		}, Wishlist::getItems());

		return json_encode($wishlistItems);
	}

	public static function toggle(Request $request)
	{
		self::validateClass($request->data->string('class'));

		$class = $request->data->string('class');

		$wishlistItem = $class::getByWishlistId($request->data->integer('id'));

		if (! $wishlistItem) {
			throw new ApiException('item_not_found');
		}

		if (Wishlist::has($wishlistItem)) {
			Wishlist::remove($wishlistItem);
		} else {
			Wishlist::add($wishlistItem);
		}

		return true;
	}

	public static function add(Request $request)
	{
		self::validateClass($request->data->string('class'));

		$class = $request->data->string('class');

		$wishlistItem = $class::getByWishlistId($request->data->integer('id'));

		if (! $wishlistItem) {
			throw new ApiException('item_not_found');
		}

		Wishlist::add($wishlistItem);

		return true;
	}

	public static function remove(Request $request)
	{
		self::validateClass($request->data->string('class'));

		$class = $request->data->string('class');

		$wishlistItem = $class::getByWishlistId($request->data->integer('id'));

		if (! $wishlistItem) {
			throw new ApiException('item_not_found');
		}

		Wishlist::remove($wishlistItem);

		return true;
	}

	public static function clear(Request $request)
	{
		Wishlist::clear();

		return true;
	}
}