<?php

namespace App\Http\Controllers\Api\V2;

use App\CentralLogics\Helpers;
use App\Contracts\Repositories\CategoryRepositoryInterface;
use App\Contracts\Repositories\TranslationRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Translation;
use App\Services\CategoryService;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Client;

class LsLibController extends Controller
{
    public function __construct(
        protected CategoryRepositoryInterface    $categoryRepo,
        protected CategoryService                $categoryService,
        protected TranslationRepositoryInterface $translationRepo
    )
    {
    }

    public function lib_update(Request $request)
    {
        return response()->json([
            'message' => 'thanks'
        ], 200);
    }

    public function setCarrefourCategories()
    {
        $client = new Client([
            'base_uri' => 'https://www.carrefourksa.com/api/v1/',
            'timeout' => 2.0,
        ]);
        $response = $client->request('GET', 'menu', [
            'query' => [
                'latitude' => 24.7136,
                'longitude' => 46.6753,
                'lang' => "ar",
            ],
            'headers' => [
                'Accept' => 'application/json',
                'storeid' => 'mafsau',
            ],
        ]);
        $data = json_decode($response->getBody()->getContents(), true);
        $categorys = array_map(function ($item) {
            $image=null;
            if($item["thumbnail"]["url"]!=null){
                $imageContent = file_get_contents($item["thumbnail"]["url"]);
                $image_name =  \Carbon\Carbon::now()->toDateString() . "-" . uniqid() .  '.png';
                $imageFullPath = storage_path('app/public/category/' . $image_name);
                file_put_contents($imageFullPath, $imageContent);
                $image=$image_name;
            }

            return [
                "name"=>[
                    "default"=>$item["name"],
                    "en"=>$item["title"],
                ],
               "lang"=>[
                   "default",
                   "en"
               ],
                "parent_id" => 0,
                "position" => "0",
                "module_id" => 3,
                "images"=>$image,
                "CarrefourId"=>$item["id"],
                "isCarrefour"=>true,
            ];
        }, $data['foodCategories'] ?? []);
     /*   array_map(function ($category) {
            $parentCategory = $this->categoryRepo->getFirstWhere(params: ['id' => $category['parent_id']]);
            $categorys = $this->categoryRepo->add(
                data: $this->categoryService->getAddData(
                    request: $category,
                    parentCategory: $parentCategory
                )
            );
            $this->translationRepo->addByModel(request: $category, model: $categorys, modelPath: 'App\Models\Category', attribute: 'name');
        },$categorys);*/
        $parentCategory = $this->categoryRepo->getFirstWhere(params: ['id' => $categorys[0]['parent_id']]);
        $array = [$categorys[0]];
        $categorysw = Category::create($categorys[0]);

       // $this->translationRepo->addByModel(request: $categorys[0], model: $categorysw, modelPath: 'App\Models\Category', attribute: 'name');
        $defaultLang = str_replace('_', '-', app()->getLocale());
        $data = [];
        foreach ($categorys[0]["lang"] as $index => $key) {
            if ($defaultLang == $key && !($categorys["name"][$index])) {
                if ($key != 'default') {
                    $data[] = array(
                        'translationable_type' => 'App\Models\Category',
                        'translationable_id' => $categorysw->id,
                        'locale' => $key,
                        'key' => "name",
                        'value' => $categorysw["name"],
                    );
                }
            } else {
                if ($categorys["name"][$index] && $key != 'default') {
                    $data[] = array(
                        'translationable_type' => 'App\Models\Category',
                        'translationable_id' => $categorysw->id,
                        'locale' => $key,
                        'key' => "name",
                        'value' => $categorys["name"][$index],
                    );
                }
            }
        }
        if (count($data)) {
            Translation::create($data);
        }
        $categorysw->image->$categorys[0]["images"];
        $categorysw->save();
        return Response()->json($categorysw);


    }

}
