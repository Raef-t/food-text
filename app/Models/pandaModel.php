<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pandaModel extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',             // Add the relevant fields from the API response
        'name',
        'description',
        'price',
        'category_id',
        'brand_id',
        'size',
        'image_url',      // If there's an image URL, include it
        // Add other fields as needed
    ];

    // If you're not using a database, you can use static methods
    public static function fromApiResponse(array $data)
    {
        $variety = isset($data['varieties'][0]) ? $data['varieties'][0] : [];

        return new self([
            'id' => $variety['id'],
            'name' => $data['name'],
            'description' => $variety['description'] ?? '',  // Safely access description
            'price' => $variety['price'] ?? '',              // Safely access price
            'category_id' => $data['category_id'] ?? null,   // Safely handle missing category_id
            'brand_id' => $data['brand_id'] ?? null,         // Safely handle missing brand_id
            'size' => $variety['size'] ?? '',                // Safely access size
            'unit' => $variety['unit'] ?? '',                // Safely access unit
            'image_url' => $variety['imageURL'] ?? null, // Handle optional image
        ]);
    }
}
