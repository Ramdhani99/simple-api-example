<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function scopeFilter($query, array $filters)
    {
        // Search filter
        $query->when(
            $filters['s'] ?? false,
            fn ($query, $filters) =>
            $query->where('name', 'like', '%' . $filters . '%')
        );

        // order by filter
        if (isset($filters['sort_column']) ? $filters['sort_column'] : false) {
            if (isset($filters['sort_order']) ? $filters['sort_order'] : false) {
                return $query->orderBy($filters['sort_column'], $filters['sort_order']);
            }
        } else {
            return $query->orderBy('id', 'desc');
        }
        // or
        // $query->when($filters['sort_column'] ?? false,fn ($query, $filters) => 
        //    $query->when($filters['sort_order'] ?? false,fn ($query, $filters) => 
        //    $query->orderBy($filters['sort_column'], $filters['sort_order'])
        // ));
    }
}
