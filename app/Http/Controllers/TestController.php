<?php

namespace App\Http\Controllers;

use App\ClassX;
use Illuminate\Http\Request;

use App\Http\Requests;

class TestController extends Controller {
	public function test_helper() {
		dd( getTimeTableVNU( '13020499', 'hhw95mrt' ) );
	}

	public function seedDataClassX_es() {
		$ks = [
			'K60',
			'K59',
			'K58',
			'K57',
		];

		$ns = [
			'CA',
			'CAC',
			'CB',
			'CC',
			'CD',
			'CLC',
			'T',
			'N',
			'ĐA',
			'ĐB',
			'M',
			'V',
			'H'
		];

		foreach ( $ks as $i => $k ) {
			foreach ( $ns as $j => $n ) {
				$class_name = $k . $n;

				$class = ClassX::all()->where( 'name', $class_name );
				if ( $class->count() == 0 ) {
					$cl = ClassX::create( [
						'khoa' => $k,
						'lop'  => $n,
					] );
				}
			}
		}
	}
}
