<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create( 'users', function ( Blueprint $table ) {
			$table->increments( 'id' );

			$table->string( 'name' );
			$table->string( 'email' )->unique();
			$table->string( 'msv' );
			$table->string( 'pass_uet' );
			$table->string( 'class' );
			$table->string( 'type' );
			$table->boolean( 'isOfficer' );
			$table->string( 'password', 60 );
			$table->rememberToken();

			$table->timestamps();
		} );
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::drop( 'users' );
	}
}
