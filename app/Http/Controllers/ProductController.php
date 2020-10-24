<?php
namespace App\Http\Controllers;

use App\Exceptions\PermissionException;
use App\Http\Resources\Product as ProductResource;
use App\Http\Resources\ProductSingle;
use App\Models\Product;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller {
    use ApiResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        //return response()->json( Product::paginate(20), 200);
        return new ProductResource( Product::paginate( 20 ) );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store( Request $request ) {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show( Product $product ) {
        return new ProductSingle( $product );

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit( Product $product ) {

        //
    }

    /**
     * Update the specified resource in storage using PATCH.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update( Request $request, Product $product ) {

        if ( $request->user()->tokenCan( 'update' ) ) {

            $validation = $request->validate(
                [
                    'name'    => 'max:255|string',
                    'subText' => 'max:255|string',
                    'price'   => 'max:65',
                ]
            );

            if ( is_null( $validation ) ) {

                throw ValidationException::withMessages( $validation );
            } else {

                $product->update( $request->only( ['name', 'subText', 'price', 'qty'] ) );

                return response()->json( [
                    'status' => 'updated',
                    'data'   => $product,
                ] );
            }

        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy( Product $product ) {

        if ( request()->user()->tokenCan( 'delete' ) ) {

            $product->delete();

            return response()->json(
                [
                    'status' => 'success',
                    'data'   => null,
                ], 200 );
        } else {
            throw new PermissionException;
        }

    }

}