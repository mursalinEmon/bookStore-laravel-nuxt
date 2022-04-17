<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use GuzzleHttp\Handler\Proxy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;


class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Product::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request);
        $product = Product::create($request->only('title','desc','img','price'));

        return response($product, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return $product;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $product->update($request->only('title','desc','img','price'));

        return response($product, Response::HTTP_ACCEPTED);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return response(null, Response::HTTP_NO_CONTENT);

    }

    public function frontend()
    {
        if($products = Cache::get('products_fronend')){
            return $products;
        }

        sleep(2);

        $products = Product::all();

        Cache::set('product_frontend', $products, 86400);

        return $products;

    }

    public function backend(Request $request){

        $page = $request->input('page', 1);

        /**@var Collection $products */
        $products = Cache::remember('products_backend', 86400, fn () =>  Product::all());

        if($search = $request->input('search')){
            $products = $products->filter(
                fn(Product $product) => Str::contains($product->title, $search) || Str::contains($product->description, $search)
            );
        }

        if($sort =  $request->input('sort')){
            if($sort === 'asc'){
                $products = $products->sortBy([
                    fn($a, $b) => $a['price'] <=> $b['price']
                ]);
            }elseif($sort === 'desc'){
                $products = $products->sortBy([
                    fn($a, $b) => $b['price'] <=> $a['price']
                ]);
            }
        }

        $total = $products->count();

        return [
            'data' => $products->forPage($page,9)->values(),
            'meta' => [
                'total' => $total,
                'page' => $page,
                'last_page' => ceil($total/9)
            ]
        ];
    }
}
