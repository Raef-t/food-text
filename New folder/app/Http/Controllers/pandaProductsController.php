<?php

namespace App\Http\Controllers;

use App\CentralLogics\Helpers;
use App\Contracts\Repositories\CategoryRepositoryInterface;
use App\Contracts\Repositories\TranslationRepositoryInterface;
use App\Models\Category;
use App\Models\Item;
use App\Models\pandaModel;
use App\Models\Store;
use App\Services\CategoryService;
use App\Services\ItemService;
use Illuminate\Http\Request;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Client;


class pandaProductsController extends Controller
{
    public function __construct(
        protected CategoryRepositoryInterface    $categoryRepo,
        protected CategoryService                $categoryService,
        protected ItemService                    $itemService,
        protected TranslationRepositoryInterface $translationRepo
    )
    {
    }

    protected function page()
    {
        $categories = $this->categoryRepo->getMainList(
            filters: ['position' => 0],
            relations: ['module'],

        );
        $subCategory = $this->categoryRepo->getMainList(
            filters: ['position' => 1],
            relations: ['module'],
        );
        $stores = Store::where("module_id", 3)->get();
        $language = getWebConfig('language');
        $defaultLang = str_replace('_', '-', app()->getLocale());
        return view('admin-views.panda-products.index', ['language' => $language, 'defaultLang' => $defaultLang, 'categories' => $categories, "subCategory" => $subCategory, 'stores' => $stores]);
    }

    protected function index(Request $request)
    {
        // Create a Guzzle client
        $client = new Client([
            'base_uri' => 'https://api.panda.sa/v3/',
            'timeout' => 50.0,
        ]);

        // Get query parameters
        $page = 1;
        $categoryId = $request->input('PandaCategoryId');
        $brandId = $request->input('brand_id');
        $size = $request->input('size');
        $price = $request->input('price');
        $sort = $request->input('sort', 'relevance');

        // List to hold all products from all pages
        $allProducts = [];

        try {
            do {
                // Send a GET request to the Panda API
                $response = $client->request('GET', 'products', [
                    'query' => [
                        'page' => $page,
                        'category_id' => $categoryId,
                        'brand_id' => $brandId,
                        'size' => $size,
                        'price' => $price,
                        'sort' => $sort,
                    ],
                    'headers' => [
                        'Accept' => 'application/json',
                        'x-panda-source' => 'PandaClick',
                        'x-pandaclick-agent' => '4',
                        "x-language" => "ar"
                    ],
                ]);

                // Decode the response
                $data = json_decode($response->getBody()->getContents(), true);

                // Append the current page's products to the allProducts array
                $products = array_map(function ($item) {
                    return pandaModel::fromApiResponse($item);
                }, $data['data']['products'] ?? []);

                $allProducts = array_merge($allProducts, $products);

                // Check if there is another page
                $haveANextPage = $data['data']['next_page'] ?? false;

                // Increment the page for the next iteration
                $page++;

            } while ($haveANextPage); // Continue fetching until next_page is false

            return $allProducts;
            // Return a JSON response of all products

        } catch (RequestException $e) {
            // Handle the exception and return error message
            return response()->json([
                'error' => true,
                'message' => $e->getMessage(),
            ], 500); // Internal Server Error
        }
    }

    protected function update(Request $request)
    {
        try{
            $pandaProduct = $this->index($request);
            // Get Panda products from the API using the index method

            $CategoryId = Category::where('panda_id', $request->input('PandaCategoryId'))->first();

            $itemCount = 0;
            foreach ($pandaProduct as $item) {
                $ShellaItem = Item::where("name", $item->name)->first();
                if ($ShellaItem ) {
                    if($ShellaItem->price != $item->price){
                        $images = [];
                        if (!empty($item->images_url) && $ShellaItem->images != null) {
                            foreach ($item->images_url as $imgUrl) {
                                // Validate image URL
                                if (filter_var($imgUrl, FILTER_VALIDATE_URL)) {
                                    // Get image content from URL
                                    $imageContent = file_get_contents($imgUrl);

                                    // Create a unique file name
                                    $image_name = \Carbon\Carbon::now()->toDateString() . "-" . uniqid() . '.png';

                                    // Define the path to store the image
                                    $imageFullPath = storage_path('app/public/product/' . $image_name);

                                    // Save the image to the desired location
                                    file_put_contents($imageFullPath, $imageContent);

                                    // Store the image info
                                    $images[] = ['img' => $image_name, 'storage' => Helpers::getDisk()];
                                }
                            }
                            $ShellaItem->images = $images;

                        }

                        $ShellaItem->status = 1;
                        $ShellaItem->panda_id = $item->id;
                        $ShellaItem->price = $item->price;  // Update the price
                        $ShellaItem->discount = 0;
                        $ShellaItem->save();  // Save the updated product to the database
                        $itemCount = $itemCount + 1;
                    }
                    $ShellaItem->panda_id = $item->id;

                    $ShellaItem->save();

                } else {
                    $images = [];
                    $image=null;
                    if (!empty($item->images_url)) {
                        foreach ($item->images_url as $imgUrl) {
                            // Validate image URL
                            if (filter_var($imgUrl, FILTER_VALIDATE_URL)) {
                                // Get image content from URL
                                $imageContent = file_get_contents($imgUrl);

                                // Create a unique file name
                                $image_name = \Carbon\Carbon::now()->toDateString() . "-" . uniqid() . '.png';

                                // Define the path to store the image
                                $imageFullPath = storage_path('app/public/product/' . $image_name);

                                // Save the image to the desired location
                                file_put_contents($imageFullPath, $imageContent);

                                // Store the image info
                                $images[] = ['img' => $image_name, 'storage' => Helpers::getDisk()];
                            }
                        }
                    }
                    if($item->image_url){
                        if (filter_var($item->image_url, FILTER_VALIDATE_URL)) {
                            // Get image content from URL
                            $imageContent = file_get_contents($item->image_url);

                            // Create a unique file name
                            $image_name = \Carbon\Carbon::now()->toDateString() . "-" . uniqid() . '.png';

                            // Define the path to store the image
                            $imageFullPath = storage_path('app/public/product/' . $image_name);

                            // Save the image to the desired location
                            file_put_contents($imageFullPath, $imageContent);

                            // Store the image info
                            $image = $image_name;
                        }
                    }

                    $newItem = new Item();
                    $newItem->images = $images;
                    $newItem->status = 1;
                    $newItem->description = "";
                    $newItem->image = $image;
                    $newItem->category_id = $CategoryId->id;
                    $newItem->category_ids =json_encode ([json_encode([
                        "id" => $CategoryId->id,
                        "position" => 2
                    ])]);
                    $newItem->variations = json_encode([

                    ]);
                    $newItem->add_ons = json_encode([

                    ]);
                    $newItem->choice_options = json_encode([

                    ]);
                    $newItem->food_variations = json_encode([

                    ]);
                    $newItem->name = $item->name;
                    $newItem->price = $item->price;
                    $newItem->discount = 0;
                    $newItem->tax_type = "percent";
                    $newItem->discount_type = "percent";
                    $newItem->store_id = 3;
                    $newItem->stock = 100;
                    $newItem->module_id = 3;
                    $newItem->unit_id = 1;
                    $newItem->panda_id = $item->id;
                    $newItem->save();
                    $itemCount = $itemCount + 1;
                }
            }

            return response()->json([
                "message"=>"succes"
            ]);

        }catch (\Exception $e){
            return response()->json([
                'error' => true,
                'message' => $e->getMessage(),
            ],400);
        }

    }
    protected function insertCarrefour(Request $request)

    {
       
        $allProducts = [];
        $itemCount = 0;
        try {

                $client = new Client([
                    'base_uri' => 'https://www.carrefourksa.com/api/v8/',
                    'timeout' => 50.0,
                ]);

                 $categoryId = $request->input('CategoryId');
                $page = $request->input('page', );
                 $category = Category::where("id", $categoryId)->first();
                $size = $request->input('size', 100);

                $response = $client->request('GET', 'categories/' . $category->carfour_id , [
                    'query' => [
                        'currentPage' => $page,
                        'pageSize' => $size,
                        "areaCode" => "Granada%20-%20Riyadh",
                        "lang" => "ar"
                    ],
                    'headers' => [
                        'Accept' => 'application/json',
                        'userid' => '193768A2-6ECE-2180-7961-99D694048D38',
                        'token' => 'undefined',
                        "storeid" => "mafsau"
                    ],
                    'http_errors' => true, // Enable HTTP error exceptions

                ]);
                $data = json_decode($response->getBody()->getContents(), true);

                $carrefourProducts = array_map(function ($item) {
                    $images = [];
                    $image = "bogy.png";
                    return $this->itemService->getAddData($item, $images, $image);
                }, $data['data']['products'] ?? []);
                $allProducts = array_merge($allProducts, $carrefourProducts);
                $haveANextPage = $page!=$data['data']['pagination']['totalPages'];


            foreach ($allProducts as $items) {
                $product = Item::where("name", $items["name"])->first();
                if($product){
                    if ($product->price != $items["price"]) {
                        $product->price = $items["price"];
                    }
                    if ($product->discount != $items["discount"]) {
                        $product->discount = $items["discount"];
                    }
                    $product->carfour_id = $items["id"];
                    $itemCount += 1;
                    $product->save();
                }

            }

return  response()->json(["message"=>"edit ".$itemCount ." items ","status"=>$haveANextPage?"again":"okay"],200);

            /*    Toastr::success("Successfully updated " . $itemCount . " items.");
                return redirect()->back()->with('success', 'Updated successfully');*/

        } catch (RequestException $e) {
            if ($e->getCode() == 28) { // Error 28 is timeout
                Log::error("Request timed out: " . $e->getMessage());

                // return response()->json(['error' => 'Request timed out. Please try again later.'], 504);
            }
               return response()->json([
                    'error' => true,
                    'message' => $e->getMessage(),
                ], 500);
        }

    }

    protected function viewCarrefour()
    {
        $language = getWebConfig('language');
        $defaultLang = str_replace('_', '-', app()->getLocale());
        $categories = Category::where('CarrefourId', '!=', "none")->get();

        return view('admin-views.panda-products.carfor', ['language' => $language, 'defaultLang' => $defaultLang, 'categories' => $categories,]);
    }

    function fetchRamezProducts(Request $request)
    {
        $shellaCategoryId = $request->input('shellaCategoryId');


        $client = new Client([
            'base_uri' => 'https://risteh.com/SA/GroceryStoreApi/api/v9/Products/',
            'timeout' => 50.0,
        ]);

        $allProducts = [];
        $itemCount = 0;
        try {
           $categoryId = $request->input('CategoryId');
           $page_number = $request->input('page_number');
           $country = "SA";
           $city = "1";
            $response = $client->request('POST', 'productList', [
                'json' => [
                "page_number"=>$page_number,
                    "category_id"=>$categoryId,
                    "country"=>$country,
                    "country_shortname"=>$country,
                    "country_id"=>"191",
                    "city"=>$city,
                    "city_id"=>$city,
                    "sotre_id"=>$city,
                    "store_ID"=>$city,
                    "store_id"=>$city,
                    "prefix"=>"966",
                ],
                'headers' => [
                    'Accept' => 'application/json',
                    'apikey' => '^~>h2q=m[h=>3?bU/!M\'X!m~?4GjKJP{Q@y;~fa3Vjs/M#`8FuB;x[LKwJ&>gNrxBt8!5PZ:9QLuHBUtu{TPc2s]k74]Br?PGe6+NcFUT-8',
                ],
            ]);
            $data = json_decode($response->getBody()->getContents(), true);
            if(empty($data['data'])){
                return response()->json(["message"=>"end Of Category"]);

            }
           $products = array_map(function ($item) {
                return pandaModel::fromRameszApiResponse($item);
            }, $data['data']?? []);

           foreach ($products as $item) {
               $ShellaItem = Item::where("name", $item->name)->first();
if($ShellaItem){
    $ShellaItem->ramez_id=$item->id;
    $ShellaItem->save();
    $itemCount = $itemCount + 1;

}else{
    $category = [];

    array_push($category, [
        'id' => $request->shellaCategoryId,
        'position' => 1,
    ]);
   $images = [];
    $image=null;
    if (!empty($item->images_url)) {
        foreach ($item->images_url as $imgUrl) {
            // Validate image URL
            if (filter_var($imgUrl, FILTER_VALIDATE_URL)) {
                // Get image content from URL
                $imageContent = file_get_contents($imgUrl);

                // Create a unique file name
                $image_name = \Carbon\Carbon::now()->toDateString() . "-" . uniqid() . '.png';

                // Define the path to store the image
                $imageFullPath = storage_path('app/public/product/' . $image_name);

                // Save the image to the desired location
                file_put_contents($imageFullPath, $imageContent);

                // Store the image info
                $images[] = ['img' => $image_name, 'storage' => Helpers::getDisk()];
            }
        }
    }
    if($item->image_url){
        if (filter_var($item->image_url, FILTER_VALIDATE_URL)) {
            // Get image content from URL
            $imageContent = file_get_contents($item->image_url);

            // Create a unique file name
            $image_name = \Carbon\Carbon::now()->toDateString() . "-" . uniqid() . '.png';

            // Define the path to store the image
            $imageFullPath = storage_path('app/public/product/' . $image_name);

            // Save the image to the desired location
            file_put_contents($imageFullPath, $imageContent);

            // Store the image info
            $image = $image_name;
        }
    }

    $newItem = new Item();
   $newItem->images = $images;
    $newItem->status = 1;
    $newItem->description = "";
   $newItem->image = $image;
    $newItem->category_id = $request->shellaCategoryId;
    $newItem->category_ids =json_encode($category);
    $newItem->variations = json_encode([

    ]);
    $newItem->add_ons = json_encode([

    ]);
    $newItem->choice_options = json_encode([

    ]);
    $newItem->food_variations = json_encode([

    ]);
    $newItem->name = $item->name;
    $newItem->price = $item->price;
    $newItem->discount = 0;
    $newItem->tax_type = "percent";
    $newItem->discount_type = "percent";
    $newItem->store_id = 3;
    $newItem->stock = 100;
    $newItem->module_id = 3;
    $newItem->unit_id = 1;
    $newItem->ramez_id = $item->id;
    $allProducts[]=$newItem;
    $newItem->save();
    $itemCount = $itemCount + 1;
}
           }
return response()->json(["message"=>"succes","itemCount"=>$itemCount]);
        }catch (\Exception $e){
            return response()->json(["error"=>$e->getLine()]);
        }
    }
}
