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
use Illuminate\Http\Request;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Response;

class pandaProductsController extends Controller
{
    public function __construct(
        protected CategoryRepositoryInterface    $categoryRepo,
        protected CategoryService                $categoryService,
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
$subCategory=$this->categoryRepo->getMainList(
    filters: ['position' => 1],
    relations: ['module'],
);
$stores=Store::where("module_id",3)->get();
    $language = getWebConfig('language');
    $defaultLang = str_replace('_', '-', app()->getLocale());
    return view('admin-views.panda-products.index',['language'=>$language,'defaultLang'=>$defaultLang,'categories'=>$categories,"subCategory"=>$subCategory,'stores'=>$stores]);
}
    protected function index(Request $request)
    {
        // Create a Guzzle client
        $client = new Client([
            'base_uri' => 'https://api.panda.sa/v3/',
            'timeout' => 2.0,
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
        // Get Panda products from the API using the index method
        $pandaProduct = $this->index($request);

        // Retrieve local products from the database based on the subcategory ID
        $subCategoryId = $request->input('subCategoryId');
        $products = Item::where('category_id', $subCategoryId)->get();
       
        // Iterate through Panda products and update local products if names match
        foreach ($pandaProduct as $item) {
            foreach ($products as $ShellaItem) {
                if ($ShellaItem->name === $item->name) {
                    $ShellaItem->price = $item->price;  // Update the price
                     $ShellaItem->discount = 0;
                    $ShellaItem->save();  // Save the updated product to the database
                }
            }
        }

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Updated successfully');
    }


}
/*[{"id":"1","position":1},{"id":"2","position":2}]*/
