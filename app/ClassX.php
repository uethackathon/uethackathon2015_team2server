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

	/**
	 * Get Class name by id
	 *
	 * @param $id
	 *
	 * @return bool|string
	 */
	public static function getClassName( $id ) {
		$classes = ClassX::all()->where( 'id', $id );
		if ( $classes->count() == 0 ) {
			return false;
		}

		$class = $classes->first();

		return $class->khoa . $class->lop;
	}

	public static function getIdByClassName( $class_name ) {
		$classXes = ClassX::all();

		foreach ( $classXes as $classX ) {
			$name = $classX->khoa . $classX->lop;
			$name = mb_strtolower( $name );

			if ( mb_strtolower( $class_name ) == $name ) {

				return intval( $classX->id );
			}
		}

		return false;
	}
}
