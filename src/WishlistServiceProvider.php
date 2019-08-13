<?php

namespace Tnt\Wishlist;

use Oak\Contracts\Container\ContainerInterface;
use Oak\ServiceProvider;
use Tnt\Wishlist\Contracts\WishlistInterface;

class WishlistServiceProvider extends ServiceProvider
{
	public function boot(ContainerInterface $app)
	{
		//
	}

	public function register(ContainerInterface $app)
	{
		$app->singleton(WishlistInterface::class, SessionWishlist::class);
	}
}