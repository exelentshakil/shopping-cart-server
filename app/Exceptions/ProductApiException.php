<?php
namespace App\Exceptions;

use Exception;

class ProductApiException extends Exception {

    /**
     * Undocumented function
     *
     * @param [type] $request
     *
     * @return void
     */
    public function render( $request ) {
        return response(
            ['status' => 'error', 'message' => 'your dont have permission'] );
    }
}