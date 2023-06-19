<?php

namespace App\Http\Controllers\Cmstrhub;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function index(Request $r)
    {
    	return view('cmstrhub.testView');
    }
}
