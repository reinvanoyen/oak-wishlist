<?php

namespace Tnt\Wishlist;

use Oak\Contracts\Config\RepositoryInterface;
use Oak\Contracts\Container\ContainerInterface;
use Oak\Migration\MigrationManager;
use Oak\Migration\Migrator;
use Oak\ServiceProvider;
use Tnt\Account\Facade\Auth;
use Tnt\Account\SessionUserStorage;
use Tnt\InternalApi\Facade\Api;
use Tnt\Wishlist\Contracts\WishlistableInterface;
use Tnt\Wishlist\Contracts\WishlistInterface;
use Tnt\Wishlist\Exception\InvalidIdentifierException;
use Tnt\Wishlist\Revisions\CreateWishlistTable;

class WishlistServiceProvider extends ServiceProvider
{
	public function boot(ContainerInterface $app)
	{
		Api::get('wishlist/items/', '\\Tnt\\Wishlist\\Controller\\ApiController::items');
		Api::get('wishlist/toggle/', '\\Tnt\\Wishlist\\Controller\\ApiController::toggle');
		Api::get('wishlist/add/', '\\Tnt\\Wishlist\\Controller\\ApiController::add');
		Api::get('wishlist/remove/', '\\Tnt\\Wishlist\\Controller\\ApiController::remove');
		Api::get('wishlist/clear/', '\\Tnt\\Wishlist\\Controller\\ApiController::clear');

        if ($app->isRunningInConsole()) {
            $migrator = $app->getWith(Migrator::class, [
                'name' => 'wishlist'
            ]);

            $migrator->setRevisions([
                CreateWishlistTable::class,
            ]);

            $app->get(MigrationManager::class)->addMigrator($migrator);
        }
	}

	public function register(ContainerInterface $app)
	{
        $config = $app->get(RepositoryInterface::class);

        if ($config->get('wishlist.driver') === 'session') {
		    $app->singleton(WishlistInterface::class, SessionWishlist::class);
        }

        if ($config->get('wishlist.driver') === 'database') {
            $model = $config->get('wishlist.model', Model\Wishlist::class);
            $wishlistable = $config->get('wishlist.identifier');

            if (!$wishlistable) {
                throw new InvalidIdentifierException();
            }

            $app->singleton(WishlistInterface::class, DatabaseWishlist::class);

            $app->set(WishlistableInterface::class, $wishlistable);

            $app->whenAsksGive(DatabaseWishlist::class, 'model', $model);
            //$app->whenAsksGive(DatabaseWishlist::class, 'identifier', $identifier);
        }
	}
}