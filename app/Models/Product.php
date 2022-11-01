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
        $query->when($filters['s'] ?? false, function ($query, $search) {
            return $query->where('name', 'like', '%' . $search . '%');
                // ->orWhere('price', 'like', '%' . $search . '%');
        });
    }
}
