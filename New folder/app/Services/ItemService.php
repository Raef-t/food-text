<?php

namespace App\Services;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Rap2hpoutre\FastExcel\FastExcel;

class ItemService
{

    public function getAddData($request): array
    {
        $discount = 0;

        // Check if 'discount' key exists and is properly set
        if (isset($request["price"]["discount"]) && isset($request["price"]["discount"]["price"]) && $request["price"]["discount"]["price"] != null) {
            $discount = $request["price"]["price"] - $request["price"]["discount"]["price"];
        }

        return [
            'name' => $request["name"],
            'price' => $request["price"]["price"],
            'discount' => $discount,
            'store_id' => "210",
            'id' => $request["id"],
            'discount_type' => "amount",
            'translations' => [
                [
                    "locale" => "ar",
                    "key" => "name",
                    "value" => $request["name"],
                ]
            ],
            'current_stock' => 100,
            'unit' => 2,
            
            'organic' => 0,
        ];
    }

}
