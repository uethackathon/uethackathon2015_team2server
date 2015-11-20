<?php

namespace App\Http\Controllers;

use App\User;
use DateTimeZone;
use Illuminate\Http\Request;

use App\Http\Requests;
use stdClass;

class UserController extends Controller {
	public function register( Request $request ) {
		onlyAllowPostRequest( $request );

		$all = $request->only( [
			'email',
			'password',
			'mssv',
			'lop',
		] );

//		if ( $all['type'] == '' || ! isset( $all['type'] ) ) {
//			$all['type'] = 'student';
//		}

		/**
		 * Dữ liệu trả về
		 */
		$response = new stdClass();

		$user = User::all()->where( 'email', $all['email'] );

		if ( $user->count() > 0 ) {
			$response->error     = true;
			$response->error_msg = 'Đã tồn tại người dùng với email '
			                       . $all['email'];

			return response()->json( $response );
		}

		$isOfficer = 0;//Mặc định là sinh viên không phải là cán bộ lớp
		$type      = 'student';//Mặc định người dùng đăng ký là sinh viên
		$user      = User::create( [
			'email'     => $all['email'],
			'password'  => md5( $all['password'] ),
			'msv'       => $all['mssv'],
			'class'     => $all['lop'],
			'type'      => $type,
			'isOfficer' => $isOfficer,
		] );

		$response->error    = false;
		$response->uid      = $user->getAttribute( 'id' );
		$user_x             = new stdClass();
		$user_x->name       = $user->getAttribute( 'name' );
		$user_x->email      = $user->getAttribute( 'email' );
		$user_x->type       = $user->getAttribute( 'type' );
		$user_x->lop        = $user->getAttribute( 'class' );
		$user_x->mssv       = $user->getAttribute( 'msv' );
		$user_x->created_at = $user->getAttribute( 'created_at' )
		                           ->setTimezone( new DateTimeZone( 'Asia/Ho_Chi_Minh' ) )
		                           ->format( 'Y-m-d H:m:i' );
		$user_x->updated_at = $user->getAttribute( 'updated_at' )
		                           ->setTimezone( new DateTimeZone( 'Asia/Ho_Chi_Minh' ) )
		                           ->format( 'Y-m-d H:m:i' );

		$response->user = $user_x;

		return response()->json( $response );
	}

	public function login( Request $request ) {
		onlyAllowPostRequest( $request );

		$all = $request->only( [
			'email',
			'password',
		] );

		/**
		 * Dữ liệu trả về
		 */
		$response = new stdClass();

		$users = User::all()->where( 'email', $all['email'] );
		if ( $users->count() < 0 ) {//Không tồn tại người dùng
			$response->error     = true;
			$response->error_msg = 'Không tồn tại người dùng này';

			return response()->json( $response );
		}

		$user        = $users->first();
		$pass_encode = md5( $all['password'] );

		if ( $user->getAttribute( 'password' ) != $pass_encode ) {//Sai mật khẩu
			$response->error     = true;
			$response->error_msg = 'Mật khẩu của bạn không đúng!';

			return response()->json( $response );
		}

		$response->error = false;
		$response->uid   = $user->getAttribute( 'id' );
		/**
		 * Trả về dữ liệu người dùng
		 */
		$user_x             = new stdClass();
		$user_x->name       = $user->getAttribute( 'name' );
		$user_x->email      = $user->getAttribute( 'email' );
		$user_x->type       = $user->getAttribute( 'type' );
		$user_x->lop        = $user->getAttribute( 'class' );
		$user_x->mssv       = $user->getAttribute( 'msv' );
		$user_x->created_at = $user->getAttribute( 'created_at' )
		                           ->setTimezone( new DateTimeZone( 'Asia/Ho_Chi_Minh' ) )
		                           ->format( 'Y-m-d H:m:i' );
		$user_x->updated_at = $user->getAttribute( 'updated_at' )
		                           ->setTimezone( new DateTimeZone( 'Asia/Ho_Chi_Minh' ) )
		                           ->format( 'Y-m-d H:m:i' );

		$response->user = $user_x;

		return response()->json( $response );
	}
}
