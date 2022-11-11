<?php

namespace App\Helpers;


class ApiHelpers
{
    public static function meta(object $data, array $except = null)
    {
        /* Default display from laravel api */
        $options = [
            'current_page' => $data->currentPage(),
            'first_page_url' => $data->url(1),
            'from' => $data->firstItem(),
            'last_page' => $data->lastPage(),
            'last_page_url' => $data->url($data->lastPage()),
            'links' => $data->linkCollection(),
            'next_page_url' => $data->nextPageUrl(),
            'path' => $data->path(),
            'per_page' => $data->perPage(),
            'prev_page_url' => $data->previousPageUrl(),
            'to' => $data->lastItem(),
            'total' => $data->total(),
            // 'range' => $data->getUrlRange($data->currentPage(), $data->currentPage()+5),
        ];

        /* Remove the array from options */
        if ($except) {
            $results = [];
            foreach ($except as $except) {
                if (array_key_exists($except, $options)) {
                    $results[] = $except;
                }
                unset($options[$except]);
            }
        }
        /* Return the filter options */
        return $options;
    }
}
