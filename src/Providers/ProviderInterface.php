<?php namespace GM\WPS\Providers;

interface ProviderInterface {

    /**
     * Return the name of the provider
     *
     * @return string
     */
    public function getName();

    /**
     * Return provider information
     *
     * @return array
     */
    public function getInfo();

    /**
     * Return true if the provaider is able to return data
     *
     * @return bool
     */
    public function isAvailable();
}