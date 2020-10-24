<?php
namespace App\Exceptions;

use App\Exceptions\PermissionException;
use App\Traits\ApiResponser;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler {
    use ApiResponser;

    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    public function register() {

        $this->reportable( function ( CustomException $e ) {
            //
        } );

        $this->renderable( function ( ModelNotFoundException $e, $request ) {
            dd( $e );
        } );
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */

    public function render( $request, Throwable $exception ) {

//dd( $exception );

        if ( $request->expectsJson() ) {
            return response()->json( [
                'status' => 'error',
                'error'  => 'Unauthenticated',
            ], 500 );
        }

        if ( $exception instanceof NotFoundHttpException ) {

            return response()->json( [
                'status'  => 'error',
                'message' => [
                    'errors' => $exception->getMessage(),
                ],
            ], JsonResponse::HTTP_NOT_FOUND );
        }

        if ( $exception instanceof MethodNotAllowedHttpException ) {
            abort( JsonResponse::HTTP_METHOD_NOT_ALLOWED, 'Method not allowed' );
        }

        if ( $exception instanceof ModelNotFoundException ) {
            return $this->errorResponse( $exception->getMessage(), 404 );

// return response()->json( [

//     'status'  => 'error',

//     'message' => [

//         'errors' => $exception->getMessage(),

//     ],
            // ], JsonResponse::HTTP_NOT_FOUND );

        }

        if ( $exception instanceof QueryException ) {

            return response()->json( [
                'status'  => 'error',
                'message' => [
                    'errors' => $exception->getMessage(),
                ],
            ], JsonResponse::HTTP_NOT_FOUND );

        }

        if ( $exception instanceof ValidationException ) {

            return response()->json( [

                'status'  => 'error',

                'message' => [

                    'errors' => $exception->getMessage(),

                    'fields' => $exception->validator->getMessageBag()->toArray(),

                ],
            ], JsonResponse::HTTP_PRECONDITION_FAILED );

        }

        if ( $exception instanceof PermissionException ) {
            return $exception->errorResponse();
        }

        return parent::render( $request, $exception );
    }

}