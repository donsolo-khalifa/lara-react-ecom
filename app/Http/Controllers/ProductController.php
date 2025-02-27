<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ProductListResource;

class ProductController extends Controller
{
    function home()
    {
        $products = Product::query()
            ->published()
            ->paginate(13);

        return Inertia::render('Home', ['products' => ProductListResource::collection($products)]);
    }
    function show(Product $product){
        return Inertia::render('Product/Show', 
        ['product' => new ProductResource($product),
        'variationOptions' => request('options',[]),
    ]);

    }
}
