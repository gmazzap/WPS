<?php namespace GM\WPS\Providers;

class Post implements ProviderInterface {

    public function getName() {
        return '$post';
    }

    public function getInfo() {
        return is_object( get_post() ) ? get_object_vars( get_post() ) : [ ];
    }

    public function isAvailable() {
        return TRUE;
    }

}