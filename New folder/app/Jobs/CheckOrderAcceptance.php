<?php

namespace App\Jobs;

use App\CentralLogics\Helpers;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckOrderAcceptance implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $order;
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function handle()
    {
        log::info("i work succfulyy");

        $order = Order::find($this->order->id);
        log::info("i work succfulyy",["order"=>$order,"orderId"=>$this->order->id]);

        // Check if the order is still pending
        if ($order && $order->order_status === 'pending') {
            log::info('order is pending '." ".$order->id);
            // Send the first notification
            $data = [
                'title' => "نحن نأسف علي التاخير",
                'description' => "كل المناديب الخاصه بنا مشغولين الان سيتم  قبول طلبك ف خلال 13 دقائق من الان",
                'type' => 'new_order',
                "image"=>"",
                "order_id"=>1,
                "module_id"=>3,
                "order_type"=>"ecommerce",
                "zone_id"=>"1",
                "conversation_id"=>0
            ];
            DB::table('user_notifications')->insert([
                'data' => json_encode($data),
                'user_id' => $order->customer->id,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            $notfi= Helpers::send_push_notif_to_device($order->customer->cm_firebase_token,$data);
      /*      if ($order->delivery_man_id===null) {
                $durationInMinutes = $order->createdAt->diffInMinutes(Carbon::now());
                if($durationInMinutes==5){
                    $data = [
                        'title' => "نحن نأسف علي التاخير",
                        'description' => "كل المناديب الخاصه بنا مشغولين الان سيتم  قبول طلبك ف خلال 10 دقائق من الان",
                        'type' => 'new_order',
                        "image"=>"",
                        "order_id"=>1,
                        "module_id"=>3,
                        "order_type"=>"ecommerce",
                        "zone_id"=>"1",
                        "conversation_id"=>0
                    ];
                    DB::table('user_notifications')->insert([
                        'data' => json_encode($data),
                        'user_id' => $order->customer->id,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                    $notfi= Helpers::send_push_notif_to_device($order->customer->cm_firebase_token,$data);

                }else{
                    $data = [
                        'title' => "نحن نأسف علي التاخير",
                        'description' => "كل المناديب الخاصه بنا مشغولين الان سيتم  قبول طلبك ف خلال 13 دقائق من الان",
                        'type' => 'new_order',
                        "image"=>"",
                        "order_id"=>1,
                        "module_id"=>3,
                        "order_type"=>"ecommerce",
                        "zone_id"=>"1",
                        "conversation_id"=>0
                    ];
                    DB::table('user_notifications')->insert([
                        'data' => json_encode($data),
                        'user_id' => $order->customer->id,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                    $notfi= Helpers::send_push_notif_to_device($order->customer->cm_firebase_token,$data);
                }
                    $data = [
                        'title' => "نحن نأسف علي التاخير",
                        'description' => "كل المناديب الخاصه بنا مشغولين الان سيتم  قبول طلبك ف خلال 13 دقائق من الان",
                        'type' => 'new_order',
                        "image"=>"",
                        "order_id"=>1,
                        "module_id"=>3,
                        "order_type"=>"ecommerce",
                        "zone_id"=>"1",
                        "conversation_id"=>0
                    ];
                    DB::table('user_notifications')->insert([
                        'data' => json_encode($data),
                        'user_id' => $order->customer->id,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                    $notfi= Helpers::send_push_notif_to_device($order->customer->cm_firebase_token,$data);

                // Queue the next check after another 5 minutes
                CheckOrderAcceptance::dispatch($order)->delay(now()->addMinutes(10));
            }*/
        }
    }
}
