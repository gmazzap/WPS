<?php namespace GM\WPS;

/*
  Plugin Name: WPS by G.M. (A fork of Rarst wps)
  Plugin URI: https://github.com/giuseppe-mazzapica/WPS
  Description: WordPress plugin for Whoops error handler.
  Author: Giuseppe Mazzapica
  Author URI: http://gm.zoomlab.it
  License: MIT

  Copyright (c) 2014 Giuseppe Mazzapica

  Part of the code in this package comes from from https://github.com/Rarst/wps
  Copyright (c) 2013 Andrey "Rarst" Savchenko
  released under MIT

  Permission is hereby granted, free of charge, to any person obtaining a copy of this
  software and associated documentation files (the "Software"), to deal in the Software
  without restriction, including without limitation the rights to use, copy, modify, merge,
  publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons
  to whom the Software is furnished to do so, subject to the following conditions:

  The above copyright notice and this permission notice shall be included in all copies
  or substantial portions of the Software.

  THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED,
  INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR
  PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE
  FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR
  OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
  DEALINGS IN THE SOFTWARE.
 */

if ( ! defined( 'WP_DEBUG' ) || ! WP_DEBUG || ! defined( 'WP_DEBUG_DISPLAY' ) || ! WP_DEBUG_DISPLAY ) {
    return;
}

if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
    require __DIR__ . '/vendor/autoload.php';
}

function getContainer() {
    static $container = NULL;
    if ( is_null( $container ) ) {
        $container = new Container;
    }
    return $container;
}

add_action( 'wps_html_handler', function( ProviderableInterface $wrap ) {
    $container = getContainer();
    foreach ( (array) $container[ 'base_providers' ] as $id ) {
        $wrap->addProvider( $container[ "providers.{$id}" ] );
    }
}, 0 );

add_action( 'wps_html_handler_admin', function( ProviderableInterface $wrap ) {
    $container = getContainer();
    $wrap->addProvider( $container[ "providers.screen" ] );
}, 0 );

$wps_container = getContainer();
$wps_container[ 'extension' ]
    ->addHandlerWrap( $wps_container[ 'wraps.html' ] )
    ->addHandlerWrap( $wps_container[ 'wraps.json' ] )
    ->run();
