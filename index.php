<?php declare(strict_types=1);

use Kirby\Cms\App;
use Kirby\Filesystem\Dir;

App::plugin('presprog/bundle-loader', [
	'hooks' => [
		/**
		 * Loads bundles from /app like plugins from /site/plugins.
		 * The /app/$bundle/index.php must return only the extension array.
		 */
		'system.loadPlugins:after' => function () {
			$appDir = kirby()->root() . '/../app';

			if (!Dir::exists($appDir)) {
				return;
			}

			$bundles = Dir::dirs($appDir);

			foreach ($bundles as $bundle) {
				$extensions = require $appDir . '/' . $bundle . '/index.php';

				$plugin = kirby()->plugin('app/' . $bundle, [
					'root' => $appDir . '/' . $bundle,
				]);

				kirby()->extend($extensions, $plugin);
			}
		},
	],
]);
