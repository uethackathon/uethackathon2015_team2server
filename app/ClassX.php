<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClassX extends Model {
	protected $table = 'class_xes';
	protected $fillable
		= [
			'khoa',
			'lop',
		];
}
