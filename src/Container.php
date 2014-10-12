<?php namespace GM\WPS;

class Container extends \Pimple\Container {

    function __construct( Array $values = [ ] ) {

        $defaults = [
            'handlers.pretty' => function() {
                return new \Whoops\Handler\PrettyPageHandler;
            },
            'handlers.json' => function() {
                return new Handlers\AdminAjaxHandler;
            },
            'providers.wp' => function() {
                return new Providers\WP;
            },
            'providers.wp_query' => function() {
                return new Providers\WPQuery;
            },
            'providers.post' => function() {
                return new Providers\Post;
            },
            'providers.currentfilters' => function() {
                return new Providers\CurrentFilters;
            },
            'providers.screen' => function() {
                return new Providers\WPScreen;
            },
            'wraps.html' => function($c) {
                return new HtmlHandlerWrap( $c[ 'handlers.pretty' ] );
            },
            'wraps.json' => function($c) {
                return new JsonHandlerWrap( $c[ 'handlers.json' ] );
            },
            'whoops' => function() {
                return new \Whoops\Run;
            },
            'extension' => function($c) {
                return new WhoopsExtension( $c[ 'whoops' ] );
            },
            'base_providers' => [ 'wp', 'wp_query', 'post', 'currentfilters' ]
        ];

        parent::__construct( array_merge( $defaults, $values ) );
    }

}