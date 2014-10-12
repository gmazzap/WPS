<?php namespace GM\WPS;

use Whoops\Handler\HandlerInterface;

class WhoopsExtension {

    private $whoops;

    public function __construct( \Whoops\Run $whoops ) {
        $this->whoops = $whoops;
    }

    /**
     * Setup given HandlerWrap and add related handler to Whoops
     *
     * @param \GM\WPS\HandlerWrapInterface $wrap
     */
    public function addHandlerWrap( HandlerWrapInterface $wrap ) {
        if ( $wrap->setup() instanceof HandlerInterface ) {
            $this->whoops->pushHandler( $wrap->getHandler() );
        }
        return $this;
    }

    /**
     * @return Whoops\Run
     */
    public function getWhoops() {
        return $this->whoops;
    }

    /**
     * Register new handlers
     */
    public function run() {
        $this->getWhoops()->register();
        ob_start();
    }

}