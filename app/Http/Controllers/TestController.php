<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class TestController extends Controller {
	public function test_helper() {
		dd( getTimeTableVNU( '13020499', 'hhw95mrt' ) );
	}
}
