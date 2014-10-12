<?php namespace GM\WPS;

use Whoops\Handler\HandlerInterface;

class HtmlHandlerWrap implements ProviderableHandlerWrapInteface {

    private $handler;
    private $providers;

    function __construct( HandlerInterface $handler ) {
        $this->handler = $handler;
        $this->providers = new \SplObjectStorage;
    }

    public function getHandler() {
        return $this->handler;
    }

    public function addProvider( Providers\ProviderInterface $provider ) {
        $this->providers->attach( $provider );
    }

    public function getProviders() {
        return $this->providers;
    }

    public function setup() {
        /**
         * Hook to be used to add providers by calling addProvider() method on passed object ($this)
         */
        do_action( 'wps_html_handler', $this );
        $providers = $this->getProviders();
        $handler = $this->getHandler();
        $providers->rewind();
        while ( $providers->valid() ) {
            /** @var \GM\WPS\ProviderInterface $provider */
            $provider = $providers->current();
            $handler->addDataTableCallback( $provider->getName(), [ $provider, 'getInfo' ] );
            $providers->next();
        }
        return $providers->count() > 0 ? $handler : NULL;
    }

}