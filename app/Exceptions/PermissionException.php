<?php
namespace App\Exceptions;

use Exception;

class PermissionException extends Exception {

    public function errorResponse() {

        return response(
            [
                'status'  => 'error',
                'message' => 'You dont have permission to delete',
            ], 500
        );
    }
}