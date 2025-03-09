<?php

namespace App\Http\Controllers;

use App\CentralLogics\Helpers;
use App\Models\Order;
use App\Models\package;
use App\Models\PaymentInvoice;
use App\Models\Subscriptions;
use App\Models\User;
use App\Models\WalletPayment;
use App\Models\WalletTransaction;
use Basel\MyFatoorah\MyFatoorah;
use Exception;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class MyFatoorahController extends Controller
{
    public $myfatoorah;

    public function __construct()
    {
        $this->myfatoorah = MyFatoorah::getInstance(true);
    }
    public function index(Request  $request)
    {
        $order=Order::find($request->order_id);
        try {

            $result = $this->myfatoorah->sendPayment(
                $order->customer->f_name.$order->customer->l_name,
                $order->order_amount,
                [
                    //     'MobileCountryCode',
                    'CustomerMobile' =>   '56562123544',
/*optional($order->customer)->phone ? str_replace("966","",optional($order->customer)->phone) :*/
                    'CustomerReference' => $order->id,  //orderID
                    'UserDefinedField' => $order->customer->id, //clientID
                    "InvoiceItems" => [
                        [
                            "ItemName" => "Order 123",
                            "Quantity" => 1,
                            "UnitPrice" => $order->order_amount
                        ]
                    ],
                    'CallBackUrl'=>"https://shalafood.net/payment-success",
                    'ErrorUrl'=>"https://shalafood.net/payment-fail",
                ],

            );
            if ($result && $result['IsSuccess'] == true) {
                return redirect($result['Data']['InvoiceURL']);
            }

        } catch (Exception $e) {
            echo $e->getMessage();
            echo $e->getResponse()->getBody()->getContents();

            //    dd($e  ,$e->getResponse()->getBody()->getContents() );
        }
    }
    public function successCallback(Request $request)
    {
        if (array_key_exists('paymentId', $request->all())) {
            $result = $this->myfatoorah->getPaymentStatus('paymentId', $request->paymentId);

            if ($result && $result['IsSuccess'] == true && $result['Data']['InvoiceStatus'] == "Paid") {
                $this->createInvoice($result['Data']);
                $order = Order::where("id",$result['Data']['CustomerReference'])->first();
                $order->payment_method="digital_payment";
                $order->transaction_reference=$result['Data']['InvoiceId'];
                $order->payment_status="paid";
                $order->order_status="confirmed";
                $order->save();
                Helpers::send_order_notification($order);
                return response()->json(['success' => true], 200);

            }

        }
    }
    public function failCallback(Request $request)
    {

        if (array_key_exists('paymentId', $request->all())) {
            $result = $this->myfatoorah->getPaymentStatus('paymentId', $request->paymentId);

            if ($result && $result['IsSuccess'] == true && $result['Data']['InvoiceStatus'] == "Pending") {

                // Logic after fail
                $error = end($result['Data']['InvoiceTransactions'])['Error'];
                return response()->json(['message' => $error,'success' => false], 400);
            }
        }
    }
    public function createInvoice($request)
    {
        $paymentarray = array_merge($request, end($request['InvoiceTransactions']));
        $paymentarray['order_id'] = $paymentarray['CustomerReference'];
        $paymentarray['client_id'] = $paymentarray['UserDefinedField'];

        $PaymentInvoice = PaymentInvoice::create($paymentarray);
    }
    public function indexWalletPay(Request  $request)
    {
        $order=WalletPayment::where(["user_id"=>$request->payment_id,"payment_status"=>'pending'])->first();
        $customer=User::find($order->user_id);
        try {

            $result = $this->myfatoorah->sendPayment(
               $customer->f_name.$customer->l_name,
                $order->amount,
                [
                    //     'MobileCountryCode',
                    'CustomerMobile' =>   '56562123544',
                    /*optional($order->customer)->phone ? str_replace("966","",optional($order->customer)->phone) :*/
                    //     'CustomerEmail',
                    //     'Language' =>"AR",
                    'CustomerReference' => $order->id,  //orderID
                    // 'CustomerCivilId' => "321",
                    'UserDefinedField' => $customer->id, //clientID
                    //     'ExpireDate',
                    //     'CustomerAddress',
                    "InvoiceItems" => [
                        [
                            "ItemName" => "Order 123",
                            "Quantity" => 1,
                            "UnitPrice" => $order->amount
                        ]
                    ],
                    'CallBackUrl'=>"https://shalafood.net/payment/myfatoorah/success",
                    'ErrorUrl'=>"https://shalafood.net/payment/myfatoorah/failed",
                ]

            );
            if ($result && $result['IsSuccess'] == true) {
                return redirect($result['Data']['InvoiceURL']);
            }

        } catch (Exception $e) {
            echo $e->getMessage();
            echo $e->getResponse()->getBody()->getContents();

            //    dd($e  ,$e->getResponse()->getBody()->getContents() );
        }
    }
    public function successCallbackWallet(Request $request)
    {
        if (array_key_exists('paymentId', $request->all())) {
            $result = $this->myfatoorah->getPaymentStatus('paymentId', $request->paymentId);

            if ($result && $result['IsSuccess'] == true && $result['Data']['InvoiceStatus'] == "Paid") {
                $this->createInvoice($result['Data']);
                $order = WalletPayment::where("id",$result['Data']['CustomerReference'])->first();
                $order->transaction_ref=$result['Data']['InvoiceId'];
                $order->payment_status="completed";
                $order->save();
                $customer=User::find($order->user_id);
$customer->wallet_balance=$customer->wallet_balance+$order->amount;
$customer->save();
               $walletTrans=new WalletTransaction();
               $walletTrans->user_id=$customer->id;
               $walletTrans->transaction_id=$result['Data']['InvoiceId'];
               $walletTrans->credit=$order->amount;
               $walletTrans->balance=$customer->wallet_balance;
               $walletTrans->transaction_type="add_fund";
               $walletTrans->reference=$result['Data']['InvoiceId'];
               $walletTrans->created_at=Carbon::now();
               $walletTrans->updated_at=Carbon::now();
$walletTrans->save();
                $data = [
                    'title' => translate('تم شحن المبلغ بنجاح'),
                    'description' => " الي محفظتك"." ".$order->amount." "."تم شحن مبلغ قيمته ",
                    "image"=>"",
                    "order_id"=>1,
                    "module_id"=>3,
                    'type'=>'wallet_payment',
                    "order_type"=>"ecommerce",
                    "zone_id"=>"1",
                    "conversation_id"=>0
                ];
                Helpers::send_push_notif_to_device($customer->cm_firebase_token,$data);
                return response()->json(['success' => true], 200);

            }

        }
    }
    public function failCallbackWallet(Request $request)
    {

        if (array_key_exists('paymentId', $request->all())) {
            $result = $this->myfatoorah->getPaymentStatus('paymentId', $request->paymentId);

            if ($result && $result['IsSuccess'] == true && $result['Data']['InvoiceStatus'] == "Pending") {
                $order = WalletPayment::where("id",$result['Data']['CustomerReference'])->first();
                $order->transaction_ref=$result['Data']['InvoiceId'];
                $order->payment_status="canceled";
                $order->save();
                // Logic after fail
                $error = end($result['Data']['InvoiceTransactions'])['Error'];
                return response()->json(['message' => $error,'success' => false], 400);
            }
        }
    }
    public function indexPackagePay(Request  $request,package $packageId)
    {
        $customer=User::find($request->user_id);
        Log::info($customer);
        $order=$packageId;
        Log::info($order,$request->all());

        try {

            $result = $this->myfatoorah->sendPayment(
                $customer->f_name.$customer->l_name,
                $order->price,
                [
                    //     'MobileCountryCode',
                    'CustomerMobile' =>   '56562123544',
                    /*optional($order->customer)->phone ? str_replace("966","",optional($order->customer)->phone) :*/
                    //     'CustomerEmail',
                    //     'Language' =>"AR",
                    'CustomerReference' => rand(1,1000000),  //orderID
                    // 'CustomerCivilId' => "321",
                    'UserDefinedField' => $customer->id, //clientID
                    //     'ExpireDate',
                    //     'CustomerAddress',
                    "InvoiceItems" => [
                        [
                            "ItemName" => $order->id,
                            "Quantity" => 1,
                            "UnitPrice" => $order->price
                        ]
                    ],
                    'CallBackUrl'=>"https://shalafood.net/payment-mobile/success",
                    'ErrorUrl'=>"https://shalafood.net/payment-mobile/fail",
                ]

            );
            if ($result && $result['IsSuccess'] == true) {
                return redirect($result['Data']['InvoiceURL']);
            }

        } catch (Exception $e) {
            echo $e->getMessage();
            echo $e->getResponse()->getBody()->getContents();

            //    dd($e  ,$e->getResponse()->getBody()->getContents() );
        }
    }
    public function successPackagePay(Request $request)
    {
        if (array_key_exists('paymentId', $request->all())) {
            $result = $this->myfatoorah->getPaymentStatus('paymentId', $request->paymentId);

            if ($result && $result['IsSuccess'] == true && $result['Data']['InvoiceStatus'] == "Paid") {
                $this->createInvoice($result['Data']);
                $order = package::where("id",$result['Data']['InvoiceItems'][0]["ItemName"])->first();
                $customer=User::find($result['Data']["UserDefinedField"]);
                $Subscriptions = new Subscriptions();
                $Subscriptions->user_id=$customer->id;
                $Subscriptions->package_id=$order->id;
                $Subscriptions->payment_method="digital_payment";
                $Subscriptions->status="completed";
                $Subscriptions->price=$order->price;
                $Subscriptions->save();
                $customer->package_id = $Subscriptions->package->id;
                $customer->km = $Subscriptions->package->km;
                $customer->save();
                $data = [
                    'title' => translate('لقد اشتركت بنجاح'),
                    'description' => "انت الان تتمتع بامكانيه التسلم والتسليم بحد اقصي {$order->km} ك/م ",
                    "image"=>"",
                    "order_id"=>1,
                    "module_id"=>3,
                    'type'=>'wallet_payment',
                    "order_type"=>"ecommerce",
                    "zone_id"=>"1",
                    "conversation_id"=>0
                ];
                Helpers::send_push_notif_to_device($customer->cm_firebase_token,$data);
                return response()->json(['success' => true], 200);

            }

        }
    }
    public function failCallbackPackagePay(Request $request)
    {

        if (array_key_exists('paymentId', $request->all())) {
            $result = $this->myfatoorah->getPaymentStatus('paymentId', $request->paymentId);

            if ($result && $result['IsSuccess'] == true && $result['Data']['InvoiceStatus'] == "Pending") {
                $customer=User::find($result['Data']["UserDefinedField"]);

                $data = [
                    'title' => translate('لم يتم الاشتراك بنجاح'),
                    'description' => "لم يتم الاشتراك في الباقه بسبب مشكله في دفع الطلب يمكنك المحاوله مره اخري ",
                    "image"=>"",
                    "order_id"=>1,
                    "module_id"=>3,
                    'type'=>'wallet_payment',
                    "order_type"=>"ecommerce",
                    "zone_id"=>"1",
                    "conversation_id"=>0
                ];
                Helpers::send_push_notif_to_device($customer->cm_firebase_token,$data);
                // Logic after fail
                $error = end($result['Data']['InvoiceTransactions'])['Error'];
                return response()->json(['message' => $error,'success' => false], 400);
            }
        }
    }
}
