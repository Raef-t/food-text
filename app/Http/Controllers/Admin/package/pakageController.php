<?php

namespace App\Http\Controllers\Admin\package;
use app\Models\package;
class pakageController extends BaseController{
    public function index(){
        dd(package::all());
    }
}
