<?php

namespace App\Http\Controllers;

use App\Comment;
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

		$comment = Comment::create( [
			'post'    => intval( $all['post_id'] ),
			'author'  => intval( $all['author_id'] ),
			'content' => $all['content'],
		] );

		$c = Comment::getCommentInfoById( $comment->id );

		return response()->json( $c );
	}
}
