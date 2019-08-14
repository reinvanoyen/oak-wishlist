<?php

namespace Tnt\Wishlist;

use Oak\Contracts\Container\ContainerInterface;
use Oak\ServiceProvider;
use Tnt\InternalApi\Facade\Api;
use Tnt\Wishlist\Contracts\WishlistInterface;

class WishlistServiceProvider extends ServiceProvider
{
	public function boot(ContainerInterface $app)
	{
		Api::get('wishlist/toggle/', '\\Tnt\\Wishlist\\Controller\\ApiController::toggle');
		Api::get('wishlist/add/', '\\Tnt\\Wishlist\\Controller\\ApiController::add');
		Api::get('wishlist/remove/', '\\Tnt\\Wishlist\\Controller\\ApiController::remove');
		Api::get('wishlist/clear/', '\\Tnt\\Wishlist\\Controller\\ApiController::clear');
	}

	public function register(ContainerInterface $app)
	{
		$app->singleton(WishlistInterface::class, SessionWishlist::class);
	}
}