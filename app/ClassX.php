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
	 * Get class name by id
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

	/**
	 * Get id class by class name
	 *
	 * @param $class_name
	 *
	 * @return bool|int
	 */
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

	/**
	 * Get number students of class
	 *
	 * @param $id
	 *
	 * @return int
	 */
	public static function getCountStudentByClassId( $id ) {
		$users = User::all()->where( 'class', $id );

		return $users->count();
	}
}
