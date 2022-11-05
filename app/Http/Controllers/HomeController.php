<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    private $rest_api_url = 'http://simple-api.test/api';

    public function index()
    {
        /* Create url parameters */
        $url_param = http_build_query(request()->query());
        $api_url = $url_param ? "{$this->rest_api_url}/products?{$url_param}" : "{$this->rest_api_url}/products";

        /* Get data from REST API */
        $response = Http::acceptJson()->withToken(session()->get('user_token'))->get($api_url);


        if ($response->successful()) {
            $response = json_decode($response->getBody()->getContents());
            return view('home', [
                'response' => $response
            ]);
        }
        return redirect(request()->url())->with('error', 'something went wrong!')->withInput();
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
        $response = Http::acceptJson()
            ->withToken(session()->get('user_token'))
            ->post("{$this->rest_api_url}/products", $request->except('_token'));

        if ($response->successful()) {
            $response = json_decode($response->getBody()->getContents());

            return back()->with([
                'response' => $response,
                'success' => "{$request->name} has been added!",
            ])->withInput();
        }
        $response = json_decode($response->getBody()->getContents());
        return back()->with([
            'response' => $response,
            'model' => 'create'
        ])->withInput();
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
    public function update(Request $request, $id)
    {
        $response = Http::acceptJson()
            ->withToken(session()->get('user_token'))
            ->put("{$this->rest_api_url}/products/{$id}", $request->except('_token'));

        if ($response->successful()) {
            $response = json_decode($response->getBody()->getContents());
            return back()->with([
                'response' => $response,
                'success' => "{$response->data->name} has been updated!",
            ])->withInput();
        }
        $response = json_decode($response->getBody()->getContents());
        return back()->with([
            'response' => $response,
            'model' => 'edit'
        ])->withInput();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $response = Http::acceptJson()
            ->withToken(session()->get('user_token'))
            ->delete($this->rest_api_url . '/products/' . request()->id, request()->except('_token'));

        if ($response->successful()) {
            $response = json_decode($response->getBody()->getContents());
            return back()->with([
                'response' => $response,
                'success' => "{$response->data->name} has been deleted!",
            ])->withInput();
        }
        return redirect(request()->url())->with('error', 'something went wrong!')->withInput();
    }
}
