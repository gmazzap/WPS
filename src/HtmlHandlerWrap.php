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
            $callback = function() use($provider) {
                return $provider->isAvailable() ? $provider->getInfo() : NULL;
            };
            $handler->addDataTableCallback( $provider->getName(), $callback );
            $providers->next();
        }
        $phpstorm = [ 'phpstorm', 'http://localhost:8091?message=%file:%line' ];
        $editor = apply_filters( 'woops_editor', $phpstorm );
        if ( in_array( $editor, [ 'sublime', 'emacs', 'textmate', 'macvim' ], TRUE ) ) {
            $handler->setEditor( $editor );
        }
        if ( is_array( $editor ) && count( $editor ) === 2 ) {
            $name = filter_var( array_shift( $editor ), FILTER_SANITIZE_STRING );
            $resolver = filter_var( array_shift( $editor ), FILTER_SANITIZE_URL );
            $handler->addEditor( $name, $resolver );
        }
        return $providers->count() > 0 ? $handler : NULL;
    }

}