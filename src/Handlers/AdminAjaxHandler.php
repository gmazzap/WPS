<?php namespace GM\WPS\Handlers;

use Whoops\Exception\Formatter;
use Whoops\Handler\Handler;
use Whoops\Handler\JsonResponseHandler;
use Whoops\Util\Misc;

/**
 * WordPress-specific version of Json handler.
 *
 * @author Andrey Savchenko <contact@rarst.net>
 */
class AdminAjaxHandler extends JsonResponseHandler {

    /**
     * @return bool
     */
    private function isAjaxRequest() {
        return defined( 'DOING_AJAX' ) && DOING_AJAX;
    }

    /**
     * @return int
     */
    public function handle() {
        if ( $this->onlyForAjaxRequests() && ! $this->isAjaxRequest() ) {
            return Handler::DONE;
        }
        $response = [
            'success' => FALSE,
            'data'    => Formatter::formatExceptionAsDataArray(
                $this->getInspector(), $this->addTraceToOutput()
            ),
        ];
        if ( Misc::canSendHeaders() ) {
            header( 'Content-Type: application/json; charset=' . get_option( 'blog_charset' ) );
        }
        echo json_encode( $response );
        return Handler::QUIT;
    }

}