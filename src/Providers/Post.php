<?php namespace GM\WPS\Providers;

class Post implements ProviderInterface {

    public function getName() {
        return '$post';
    }

    public function getInfo() {
        return $this->isAvailable() ? get_object_vars( get_post() ) : [ ];
    }

    public function isAvailable() {
        return get_post() instanceof \WP_Post;
    }

}