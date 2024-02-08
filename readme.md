# Bundle Loader for Kirby CMS

> ⚠️ This is an **experimental** proof of concept. Fool around, but do not use it in production.

> 🍀 **Kirby 4** only!

----
## TL;DR
This plugin is for you, if you like to structure your project-specific code into different plugins, but are annoyed by the fact that it lives alongside third-party plugins in `/site/plugins`. Move your custom code to `/app`, make a minor adjustment to make it work, keep external plugins in `/site/plugins` and ignore it in your VCS.

----

## Installation

### 1a. ZIP archive
Download the archive and drop it into `site/plugins`.

### 1b. Composer

Add the repository to your `composer.json`:
```json
{
	"repositories": [
		{
			"type": "vcs",
			"url": "https://github.com/bezin/kirby-bundle-loader"
		}
	]
}
```

Add run `composer require`:
```bash
composer require bezin/kirby-bundle-loader:dev-main
```
### 2. After installing

Move your project-specific plugin to `/app/$plugin`. Do not register the plugin in your `index.php` but instead return the array with the extensions: 


```php
<?php // e.g. /app/blog/index.php

// Instead of:

// \Kirby\Cms\App::plugin('vendor/plugin', [
//  'snippets' => [ ],
//  'templates' => [],
//  // …
//]);

// do this:

return [
  'snippets' => [],
  'templates' => [],
  // …
];
```

## What does it do?

I like the possibility to easily structure my project-specific code into different folders. It just allows me to group things nicely, e.g. have all my blog related files in `/site/plugins/blog`, like blocks only used in posts, snippets to display a post collection, the `post` and `blog`/`posts` page templates – rather than having it spread out across all the subfolders in `/site/`. 

Kirby allows that because you can create plugins on the fly. Just drop a folder in `/site/plugins` and set up an `index.php` where you register your plugin with the Kirby core.   

The downside to the `/site/plugins` folder is, that third-party plugins live in the same space. This leads to *first-party* code being next to *third-party* code – code that can and should be edited alongside code that must not be edited. It does not matter, if you download a plugin as ZIP file or install it via Composer, because plugins installed by Composer will be installed to `/site/plugins` rather than `/vendor`, too.

This plugin is a test to move project- or app-specific code out of `/site/plugins` to `/app/`. After all the Kirby core plugin loading is completed (`systen.loadPlugins:after` hook), it looks up the folders in `/app/` and load the extensions from `/app/$bundle/index.php`.

```php
<?php // e.g. /app/blog/index.php

return [
	'snippets' => [
    // …
  ],
  'templates' => [
    // …  
  ],
  // …
];
```

It also clears up the relation between dependencies in the project `composer.json` and the code that uses these. Third-party plugins may bring their own `composer.json` and `vendor` folder. For project-specific plugins, this is IMO a mess. It is much clearer to have all your project-specific dependencies in your projects `composer.json`. You could do this while having your plugins in the intended folder, but than again things are vastly different between *first-* and *third-party* code. 

For the future I hope Kirby will update the plugin system and simply install *third-party* plugins to `vendor`; but I know there are many things to consider (non-composer installs, all plugins must be updated, …).

Thanks for coming to my TED talk 😉

----
Made with ♥️ and ☕
