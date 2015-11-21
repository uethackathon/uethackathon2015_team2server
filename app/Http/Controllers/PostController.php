<?php

namespace App\Http\Controllers;

use App\ClassX;
use App\Post;
use App\User;
use DateTimeZone;
use Illuminate\Http\Request;

use App\Http\Requests;
use stdClass;

class PostController extends Controller {
	public function postToClassX( Request $request ) {
		onlyAllowPostRequest( $request );

		$all = $request->only( [
			'title',
			'content',
			'author',
		] );

		$base = 'class_xes';//ClassX

		/**
		 * Dữ liệu trả về
		 */
		$response = new stdClass();

		/**
		 * Kiểm tra user có tồn tại hay không?
		 */
		$users = User::all()->where( 'id', intval( $all['author'] ) );
		if ( $users->count() == 0 ) {//Không tồn tại người dùng
			$response->error     = true;
			$response->error_msg = 'Đã có lỗi gì đó xảy ra!';

			return response()->json( $response );
		}

		/**
		 * Author
		 */
		$user     = $users->first();
		$u        = new stdClass();
		$u->id    = $user->id;
		$u->name  = $user->name;
		$u->type  = $user->type;
		$u->email = $user->email;

		/**
		 * Tạo post mới
		 */
		$post = Post::create( [
			'title'   => $all['title'],
			'content' => $all['content'],
			'group'   => intval( $user->class ),
			'author'  => intval( $all['author'] ),
			'base'    => $base,
		] );

		/**
		 * Post
		 */
		$response->id          = $post->id;
		$response->title       = $post->title;
		$response->content     = $post->content;
		$response->base        = $post->base;
		$response->isIncognito = intval( $post->isIncognito );
		$response->create_at   = date_create( $post->created_at )
			->setTimezone( new DateTimeZone( 'Asia/Ho_Chi_Minh' ) )
			->format( 'Y-m-d H:m:i' );
		$response->updated_at  = date_create( $post->updated_at )
			->setTimezone( new DateTimeZone( 'Asia/Ho_Chi_Minh' ) )
			->format( 'Y-m-d H:m:i' );

		$response->author = $u;

		return response()->json( $response );
	}

	public function getFromClassX( Request $request ) {
		onlyAllowPostRequest( $request );

		$id_classX = $request->input( 'id' );
		$base      = 'class_xes';//ClassX

		/**
		 * Dữ liệu trả về
		 */
		$response = new stdClass();

		$classXes = ClassX::all()->where( 'id', $id_classX );
		if ( $classXes->count() == 0 ) {//Không tồn tại lớp học này
			$response->error     = true;
			$response->error_msg = 'Đã có lỗi gì đó xảy ra!';

			return response()->json( $response );
		}

		$postClassXes = Post::all()->where( 'base', $base );
		if ( $postClassXes->count() == 0 ) {//Chưa có bài viết nào
			$response->error     = true;
			$response->error_msg = 'Chưa có bài viết nào trong lớp!';

			return response()->json( $response );
		}
		dd( $postClassXes );

		/**
		 * Danh sách các bài viết
		 */
		$arrPost = [ ];
		foreach ( $postClassXes as $index => $post ) {

		}


		return response()->json( $response );
	}
}
