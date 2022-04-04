<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{

    public function index(){
        // dd('hit');
        return OrderResource::collection(Order::with('orderItems')->get());
    }
}
