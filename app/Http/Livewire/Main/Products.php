<?php

namespace App\Http\Livewire\Main;

use Illuminate\Support\Facades\Http;
use Livewire\Component;

class Products extends Component
{
    private $rest_api_url = 'http://simple-api.test/api';
    // page settings
    public $page, $page_size, $search;
    public $sort_column, $sort_order;
    protected $queryString = [
        'page' => ['except' => '1'],
        'page_size' => ['except' => '10'],
        'search' => ['except' => '', 'as' => 's'],
        'sort_column',
        'sort_order',
    ];

    protected $listeners = [
        'clear', //function clear for livewire.emit
    ];

    // data form variable
    public $data_id, $name, $price;

    public function render()
    {
        // if the rules isn't in rest api 
        // $rules = [
        //     'page' => 'numeric|min:1|not_in:0',
        //     'page_size' => ['numeric', Rule::in(['10', '25', '50', '100'])],
        //     'sort_column' => 'required_with:sort_order',
        //     'sort_order' => 'required_with:sort_column',
        //     's' => 'max:255',
        // ];
        // $validator = Validator::make(request()->query(), $rules);
        // if ($validator->fails()) {
        //     redirect()->to(request()->url())->with('toastify_error', 'Something went wrong!');
        // }

        /* Create url parameters */
        $url_param = http_build_query([
            'page' => $this->page,
            'page_size' => $this->page_size,
            's' => $this->search,
            'sort_column' => $this->sort_column,
            'sort_order' => $this->sort_order
        ]);
        // $url_param = http_build_query(request()->query());
        $api_url = $url_param ? "{$this->rest_api_url}/products?{$url_param}" : "{$this->rest_api_url}/products";

        /* Get data from REST API */
        $response = Http::acceptJson()->withToken(session()->get('user_token'))->get($api_url);

        if ($response->failed()) {
            redirect()->to(request()->url())->with('toastify_error', 'Something went wrong!');
        }

        $response = json_decode($response->getBody()->getContents());

        return view('livewire.main.products', [
            'response' => $response,
        ])
            ->extends('layouts.master')
            ->section('content');
    }

    public function change_page($page)
    {
        $this->page = $page;
    }
    public function change_page_size($page_size)
    {
        $this->page_size = $page_size;
    }
    public function search()
    {
        $this->search = $this->search;
    }
    public function sort($sort_column, $sort_order)
    {
        if ($this->sort_column == $sort_column) {
            $this->sort_order = $sort_order == 'asc' ? 'desc' : 'asc';
        } else {
            $this->sort_order = 'asc';
        }
        $this->sort_column = $sort_column;
    }

    public function clear()
    {
        $this->resetExcept(['page', 'page_size', 'search', 'sort_column', 'sort_order']);
        $this->resetValidation();
    }

    public function display_modal($modal, $data = null)
    {
        if ($data) {
            $this->data_id = $data['id'] ?? null;
            $this->name = $data['name'] ?? null;
            $this->price = $data['price'] ?? null;
        }
        $this->dispatchBrowserEvent('show_modal', ['modal' => $modal]);
    }

    public function store($modal)
    {
        $response = Http::acceptJson()
            ->withToken(session()->get('user_token'))
            ->post("{$this->rest_api_url}/products", [
                'name' => $this->name,
                'price' => $this->price,
            ]);

        if ($response->successful()) {
            $response = json_decode($response->getBody()->getContents());
            $this->dispatchBrowserEvent('toastify_success', ['message' => "{$response->data->name} is added!"]);
            $this->dispatchBrowserEvent('close_modal', ['modal' => $modal]);
        } else {
            $response = json_decode($response->getBody()->getContents());
            $this->dispatchBrowserEvent('toastify_error', $response);
        }
    }

    public function update($modal)
    {
        $response = Http::acceptJson()
            ->withToken(session()->get('user_token'))
            ->put("{$this->rest_api_url}/products/{$this->data_id}", [
                'name' => $this->name,
                'price' => $this->price,
            ]);

        if ($response->successful()) {
            $response = json_decode($response->getBody()->getContents());
            $this->dispatchBrowserEvent('toastify_success', ['message' => "{$response->data->name} is updated!"]);
            $this->dispatchBrowserEvent('close_modal', ['modal' => $modal]);
        } else {
            $response = json_decode($response->getBody()->getContents());
            $this->dispatchBrowserEvent('toastify_error', $response);
        }
    }
    public function destroy($modal)
    {
        $response = Http::acceptJson()
            ->withToken(session()->get('user_token'))
            ->delete("{$this->rest_api_url}/products/{$this->data_id}", request()->except('_token'));
        if ($response->successful()) {
            $response = json_decode($response->getBody()->getContents());
            $this->dispatchBrowserEvent('toastify_success', ['message' => "{$response->data->name} is deleted!"]);
            $this->dispatchBrowserEvent('close_modal', ['modal' => $modal]);
        } else {
            $response = json_decode($response->getBody()->getContents());
            $this->dispatchBrowserEvent('toastify_error', $response);
        }
    }
}
