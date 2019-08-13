<?php

namespace Tnt\Wishlist;

use app\Property;
use dry\route\Router;
use Oak\Contracts\Container\ContainerInterface;
use Oak\ServiceProvider;
use Tnt\Wishlist\Contracts\WishlistInterface;

class WishlistServiceProvider extends ServiceProvider
{
	public function boot(ContainerInterface $app)
	{
		Router::register([
			'wishlist/add/' => function() {

				$property = Property::one('WHERE is_published IS TRUE ORDER BY RAND()');

				echo $property->title;

				\Tnt\Wishlist\Facade\Wishlist::add($property);
			},
			'wishlist/index/' => function() {

				foreach (\Tnt\Wishlist\Facade\Wishlist::getItems() as $item) {
					echo $item->title . '<br />';
				}
			},
		]);
	}

	public function register(ContainerInterface $app)
	{
		$app->singleton(WishlistInterface::class, SessionWishlist::class);
	}
}