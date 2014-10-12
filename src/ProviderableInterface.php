<?php namespace GM\WPS;

interface ProviderableInterface {

    /**
     * @param \GM\WPS\Providers\ProviderInterface $provider
     */
    public function addProvider( Providers\ProviderInterface $provider );

    /**
     * @return \Iterator
     */
    public function getProviders();
}