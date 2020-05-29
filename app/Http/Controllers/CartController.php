<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;
use MercurySeries\Flashy\Flashy;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('cart.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $product = Product::findOrFail($request->product_id);

        Cart::add($product->id, $product->title, 1, $product->price)
            ->associate('App\Product');

        Flashy::message('Le produit a bien été ajouté.');

        return redirect()->route('products.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $rowId)
    {
        $data = $request->json()->all();

        $Validator = Validator::make($request->all(), [
            'qty' => 'required|numeric|between:1,6'
        ]);

        if($Validator->fails()){
            Flashy::error('La quantité du produit doit être entre 1 et 6.');
            return response()->json(['error' => 'Cart Quantity Has Not Been Updated']);
        }

        Cart::update($rowId, $data['qty']);

        Flashy::message('La quantité du produit a bien été modifiée.');

        return response()->json(['success' => 'Cart Quantity Has Been Updated']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($rowId)
    {
        Cart::remove($rowId);
        Flashy::error('Le produit a été supprimé.');
        return redirect()->back();
    }
}
