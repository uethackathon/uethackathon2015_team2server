<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClassSubjectsTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create( 'class_subjects', function ( Blueprint $table ) {
			$table->increments( 'id' );

			$table->string( 'maLMH' );// INT2002 5
			$table->integer( 'teacher' );//id tới user teacher
			$table->string( 'address' );
			$table->integer( 'subject' );


			$table->timestamps();
		} );
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::drop( 'class_subjects' );
	}
}
