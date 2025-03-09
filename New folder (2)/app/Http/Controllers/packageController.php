<?php

namespace App\Http\Controllers;

use App\Models\package;
use Illuminate\Http\Request;

class packageController extends Controller
{
    public  function index()
    {
        return view('admin-views.parcel.package.index',["packages"=>Package::all()]);
    }
    public  function edit(package $package)
    {

        return view('admin-views.parcel.package.update',["package"=>$package]);
    }
    public function create(request $request){

        $validated = $request->validate([
            "name" => "required",
            "km" => "required|numeric",
            "price" => "required",
        ]);
        Package::create($validated);
        return redirect()->route('admin.parcel.index')->with("status","package created successfully");
    }
    public function update(request $request,package $package){

        $package->update($request->all());
        return redirect()->route('admin.parcel.index')->with("status","package created successfully");
    }

    public function destroy(package $package)
    {
        $package->delete();
        return redirect()->route('admin.parcel.index')->with("status", "package deleted successfully");
    }
    public function updateStatus(package $package,bool $status)
    {
        $package->active=$status;
        $package->save();
        return redirect()->route('admin.parcel.index')->with("status", "package deleted successfully");
    }
    public function userSubscription(Request $request,package  $package )
    {
        // Wrap validation in a try-catch block to ensure JSON response

        try {
            // Retrieve the authenticated user
            $user = $request->user();
            // Update the user's package_id with validated data from the body
            $user->package_id = $package->id;
            $user->km = $package->km;

            $user->save();

            return response()->json(['message' => "تم الاشتراك بنجاح"]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Return a custom JSON response for validation errors
            return response()->json([
                'errors' => $e->errors(),
                'message' => 'Validation failed.'
            ], 422);
        }

        ;
    }

public  function getpackages()
{

    return response()->json(Package::where('active',true)->get());

}


}
