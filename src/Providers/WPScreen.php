<?php namespace GM\WPS\Providers;

class WPScreen implements ProviderInterface {

    public function getName() {
        return 'WP_Screen';
    }

    public function getInfo() {
        return function_exists( 'get_current_screen' ) ? get_object_vars( get_current_screen() ) : [ ];
    }

    public function isAvailable() {
        return TRUE;
    }

}