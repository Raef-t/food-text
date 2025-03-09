<?php

namespace App\Http\Controllers\Api\V1;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\Zone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class ModuleController extends Controller
{

    public function index(Request $request)
    {
        if ($request->hasHeader('zoneId')) {
            $zone_id=$request->header('zoneId');
            $modules = Module::with('zones')->withCount('items')->whereHas('zones',function($query) use ($zone_id){
                $query->whereIn('zone_id',json_decode($zone_id, true))->where("collection_id",null);
            })->active()->get();
        }else{
            $modules = Module::withCount('items')->when($request->zone_id, function($query)use($request){
                $query->whereHas('zones',function($query) use ($request){
                    $query->where('zone_id',$request->zone_id);
                })->notParcel();
            })->active()->get();
        }

        $modules = array_map(function($item){

            return $item;
        },$modules->toArray());
        return response()->json($modules);
    }
    public function indexSubModule(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'module_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
            $zone_id = $request->header('zoneId');
            $modules = Module::with('zones')->withCount('items')->whereHas('zones', function ($query) use ($zone_id, $request) {
                $query->whereIn('zone_id', json_decode($zone_id, true))->where("collection_id",$request->module_id );
            })->active()->get();
            $modules = array_map(function($item){

                return $item;
            },$modules->toArray());

        return response()->json($modules);

    }

}
