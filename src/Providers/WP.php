<?php namespace GM\WPS\Providers;

class WP implements ProviderInterface {

    public function getName() {
        return '$wp';
    }

    public function getInfo() {
        if ( ! $this->isAvailable() ) {
            return [ ];
        }
        $output = get_object_vars( $GLOBALS[ 'wp' ] );
        unset( $output[ 'private_query_vars' ] );
        unset( $output[ 'public_query_vars' ] );
        return array_filter( $output );
    }

    public function isAvailable() {
        return isset( $GLOBALS[ 'wp' ] ) && $GLOBALS[ 'wp' ] instanceof \WP;
    }

}