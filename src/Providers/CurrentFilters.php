<?php namespace GM\WPS\Providers;

class CurrentFilters implements ProviderInterface {

    public function getName() {
        return '$wp_current_filter';
    }

    public function getInfo() {
        return $this->isAvailable() ? (array) $GLOBALS[ 'wp_current_filter' ] : [ ];
    }

    public function isAvailable() {
        return isset( $GLOBALS[ 'wp_current_filter' ] );
    }

}