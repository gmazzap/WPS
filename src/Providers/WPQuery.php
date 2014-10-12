<?php namespace GM\WPS\Providers;

class WPQuery implements ProviderInterface {

    public function getName() {
        return '$wp_query';
    }

    public function getInfo() {
        if ( ! $this->isAvailable() ) {
            return [ ];
        }
        $output = get_object_vars( $GLOBALS[ 'wp_query' ] );
        $output[ 'query_vars' ] = array_filter( $output[ 'query_vars' ] );
        unset( $output[ 'posts' ] );
        unset( $output[ 'post' ] );
        return array_filter( $output );
    }

    public function isAvailable() {
        return isset( $GLOBALS[ 'wp_query' ] ) && $GLOBALS[ 'wp_query' ] instanceof \WP_Query;
    }

}