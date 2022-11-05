<?php

namespace App\Http\Controllers\Api;

use App\Helpers\SiteHelpers;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{

    private $rules = [
        'name' => ['required', 'max:255', 'regex:/^[a-zA-Z0-9\s]+$/'],
        'price' => 'required|numeric|min:1|not_in:0',
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $rules = [
            'page' => 'numeric|min:1|not_in:0',
            'page_size' => ['numeric', Rule::in(['10', '25', '50', '100'])],
            's' => 'max:255',
        ];

        $validateData = request()->validate($rules);

        // $page = (request()->get('page') != null) ? $validateData['page'] : 1;
        $page_size = (request()->get('page_size') != null) ? $validateData['page_size'] : 10;

        // raw data
        // $data = Product::paginate($page_size)->withquerystring();

        // using resource
        /* Change the api url to front end url */
        // $data = ProductResource::collection(Product::paginate($page_size))->setPath('http://simple-api.test/products');

        /* withQueryString method if you would like to append all of the current request's query string values to the pagination links */
        $data = ProductResource::collection(Product::filter(request(['s', 'sort_column', 'sort_order']))->paginate($page_size)->withQueryString());

        //api response
        // return response()->json($data);
        return response()->json([
            'message' => 'success',
            'data' => $data,
            'meta' => SiteHelpers::meta($data),
            // 'meta' => [
            //     'current_page' => $data->currentPage(),
            //     'first_page_url' => $data->url(1),
            //     'from' => $data->firstItem(),
            //     'last_page' => $data->lastPage(),
            //     'last_page_url' => $data->url($data->lastPage()),
            //     'links' => $data->linkCollection(),
            //     'next_page_url' => $data->nextPageUrl(),
            //     'path' => $data->path(),
            //     'per_page' => $data->perPage(),
            //     'prev_page_url' => $data->previousPageUrl(),
            //     'to' => $data->lastItem(),
            //     'total' => $data->total(),
            //     // 'range' => $data->getUrlRange($data->currentPage(), $data->currentPage()+5),
            // ]
        ]);
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
        // option 1
        // $request->validate($rules);

        // Product::create([
        //     'name' => $request['name'],
        //     'price' => $request['price'],
        // ]);
        // option 2
        $validateData = $request->validate($this->rules);
        // Product::create($validateData);

        // option 3
        $getId = Product::insertGetId([
            'name' => ucwords($validateData['name']),
            'price' => $validateData['price'],
            "created_at" => Carbon::now(), // datetime format
        ]);

        $data = Product::findOrFail($getId);

        return response()->json([
            'message' => 'success',
            'data' => $data,
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return response()->json([
            'message' => 'success',
            'data' => $product,
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
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
        $validateData = $request->validate($this->rules);

        $product->update($validateData);

        return response()->json([
            'message' => 'success',
            'data' => $product,
        ], 200);
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

        return response()->json([
            'message' => 'success',
            'data' => $product,
        ], 200);
    }
}
