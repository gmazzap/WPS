<?php namespace GM\WPS;

interface HandlerWrapInterface {

    /**
     * Setup the wrapper. Should be used to set configuration for inner handler.
     * Have to return the hanlder itself if everything is fine.
     *
     * @return \Whoops\Handler\HandlerInterface|void
     */
    public function setup();

    /**
     * Getter for the inner Whoops handler
     *
     * @return \Whoops\Handler\HandlerInterface
     */
    public function getHandler();
}