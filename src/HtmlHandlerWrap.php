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
        $this->fireHooks();
        $handler = $this->getHandler();
        if ( ! $this->setupProviders( $handler ) > 0 ) {
            return NULL;
        }
        $this->setupEditor( $handler );
        return $handler;
    }

    private function fireHooks() {
        if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
            return;
        }
        // Hooks to be used to add providers by calling addProvider() method on passed object
        do_action( 'wps_html_handler', $this );
        $side = is_admin() ? 'admin' : 'front';
        do_action( "wps_html_handler_{$side}", $this );
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
    }

}