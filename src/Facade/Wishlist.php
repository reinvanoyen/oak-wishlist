<?php

namespace Tnt\Wishlist\Facade;

use Oak\Facade;
use Tnt\Wishlist\Contracts\WishlistInterface;

class Wishlist extends Facade
{
	protected static function getContract(): string
	{
		return WishlistInterface::class;
	}
}