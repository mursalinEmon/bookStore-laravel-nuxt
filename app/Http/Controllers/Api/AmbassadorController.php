<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class AmbassadorController extends Controller
{
    public function index(){
        return User::ambassadors()->get();
    }
}
