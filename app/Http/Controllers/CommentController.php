<?php

namespace App\Http\Controllers;

use App\Post;
use Illuminate\Http\Request;

use App\Http\Requests;

class CommentController extends Controller {
	public function commentToClassX( Request $request ) {
		onlyAllowPostRequest( $request );

		$all = $request->only( [
			'post_id',
			'author_id',
			'content',
		] );

	}
}
