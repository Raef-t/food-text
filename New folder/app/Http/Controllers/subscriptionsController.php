<?php

namespace App\Http\Controllers;

use App\CentralLogics\Helpers;
use App\Models\package;
use App\Models\Subscriptions;
use App\Models\User;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class subscriptionsController extends Controller
{
    public function userSubscriptions(Request $request,package $package)
    {

        $validator = Validator::make($request->all(), [
            'payment_method' => 'required|in:cash_on_delivery,digital_payment,wallet',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        $allSubscriptions= Subscriptions::with('package',"user")->get();
        $subscriptionExists = $allSubscriptions->contains(function ($subscription) {
            return $subscription->user_id == auth()->user()->id;
        });
        if($subscriptionExists){
            return response()->json([
                'errors' => [
                    ['code' => 'unCompletableOrder', 'message' => "you have request already under review"]
                ]
            ], 403);
        }
        $Subscriptions = new Subscriptions();
        $Subscriptions->user_id=auth()->user()->id;
        $Subscriptions->package_id=$package->id;
        $digital_payment = Helpers::get_business_settings('digital_payment');
        if ($digital_payment['status'] == 0 && $request->payment_method == 'digital_payment') {
            return response()->json([
                'errors' => [
                    ['code' => 'digital_payment', 'message' => translate('messages.digital_payment_for_the_order_not_available_at_this_time')]
                ]
            ], 403);
        }
        $Subscriptions->payment_method="cash_on_delivery";
        $Subscriptions->status="pending";
        $Subscriptions->price=$package->price;
        $Subscriptions->save();
        return response()->json([
            "message" => "Successfully subscribed"
        ],200);
    }
    public function updateSubscriptionsStatus(Subscriptions $Subscriptions,String $status)
    {


        $Subscriptions->status=$status;
        if($status==="completed"){
            $user=User::where("id",$Subscriptions->user_id)->first();
            $user->package_id = $Subscriptions->package->id;
            $user->km = $Subscriptions->package->km;
            $user->save();
        }else{
            $user=User::where("id",$Subscriptions->user_id)->first();
            $user->package_id = $Subscriptions->package->id;
            $user->km = 0;
            $user->save();
        }
        $Subscriptions->save();
        Toastr::success(translate('messages.order_status_updated'));
        return back();
    }
    public function index()
    {
        $Subscriptions= Subscriptions::with('package',"user")->get();
        return view('admin-views.user-subscription.index',compact('Subscriptions'));
    }
    public function showInfo(Subscriptions $Subscriptions)
    {

        return view('admin-views.user-subscription.subscription',compact('Subscriptions'));
    }
}
