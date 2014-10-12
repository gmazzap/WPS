<?php namespace GM\WPS;

use Whoops\Handler\HandlerInterface;

class HtmlHandlerWrap implements HandlerWrapInterface, ProviderableInterface {

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
        $this->fireHooks();
        $handler = $this->getHandler();
        if ( $this->setupProviders( $handler ) > 0 ) {
            $this->setupEditor( $handler );
            return $handler;
        }
    }

    private function fireHooks() {
        // Hooks to be used to add providers by calling addProvider() method on passed object
        if ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) {
            do_action( 'wps_html_handler', $this );
            $side = is_admin() ? 'admin' : 'front';
            do_action( "wps_html_handler_{$side}", $this );
        }
    }

    private function setupProviders( HandlerInterface $handler ) {
        $providers = $this->getProviders();
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
        return $providers->count();
    }

    private function setupEditor( HandlerInterface $handler ) {
        $editor = apply_filters( 'woops_editor', NULL );
        if (
            is_callable( $editor )
            || in_array( $editor, [ 'sublime', 'emacs', 'textmate', 'macvim' ], TRUE )
        ) {
            $handler->setEditor( $editor );
        }
    }

}