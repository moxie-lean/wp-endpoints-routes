# WP Endpoints: Routes

> Get the set of routes and exposes them via WP-API. This extension will create an endpoint (at ```/wp-json/lean/v1/routes``` by default).

## Current State

As of now the endpoint only adds pages automatically. You can manually add others using the ```ln_endpoints_data_routes``` filter (see below).

## Getting Started

The easiest way to install this package is by using composer from your terminal:

```bash
composer require moxie-lean/wp-endpoints-routes
```

Or by adding the following lines on your `composer.json` file

```json
"require": {
  "moxie-lean/wp-endpoints-routes": "dev-master"
}
```

This will download the files from the [packagist site](https://packagist.org/packages/moxie-lean/wp-endpoints-routes) 
and set you up with the latest version located on master branch of the repository. 

After that you can include the `autoload.php` file in order to
be able to autoload the class during the object creation.

```php
include '/vendor/autoload.php';
```

Finally you need to initialise the endpoint by adding this to your code:

```php
\Lean\Endpoints\Routes::init();
```

## Usage

The endpoint takes no inputs and returns data in the following format:

```json
[
  {
    "state": "home",
    "url": "/",
    "template": "home",
    "endpoint": "post",
    "params": {
      "id": 123
    }
  },
  {
    "state": "allPhotos",
    "url": "/photos",
    "template": "allPhotos",
    "endpoint": "collection",
    "params": {
      "type": "photo",
      "posts_per_page": 10
    }
  },
  {
    "state": "authorPhotos",
    "url": "/photos/:authorId",
    "template": "authorPhotos",
    "endpoint": "collection",
    "params": {
      "type": "photo",
      "posts_per_page": 10
    }
  },
  {
    "state": "photo",
    "url": "/photos/:authorId/:photoId",
    "template": "photo",
    "endpoint": "post",
    "params": {}
  }
]
```

### Adding Extra Routes

You can do this with the ```ln_endpoints_data_routes``` filter as follows:

```php
add_filter( 'ln_endpoints_data_routes', 'add_extra_routes' );

function add_extra_routes( $routes ) {
    $extra_routes = [
        [
            'state' => 'blog',
            'url' => '/blog/',
            'template' => 'blog',
            'endpoint' => 'collection',
            'params' => [
                'type' => 'post',
                'posts_per_page' => 5,
            ]
        ],
    ];

    return array_merge( $routes, $extra_routes );
}
```
