<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create( 'comments', function ( Blueprint $table ) {
			$table->increments( 'id' );

			$table->string( 'content' );
			$table->integer( 'post' );
			$table->integer( 'author' );
			$table->boolean( 'confirmed' )->default( 0 );

			$table->timestamps();
		} );
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::drop( 'comments' );
	}
}
