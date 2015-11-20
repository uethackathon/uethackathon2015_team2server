<?php

namespace App\Http\Controllers;

use App\ClassX;
use App\User;
use DateTimeZone;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use stdClass;

class UserController extends Controller {
	/**
	 * API Register
	 *
	 * @param Request $request
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function register( Request $request ) {
		onlyAllowPostRequest( $request );

		$all = $request->only( [
			'email',
			'password',
			'mssv',
			'lop',
		] );

		/**
		 * Dữ liệu trả về
		 */
		$response = new stdClass();

		/**
		 * Tìm user đã tồn tại chưa?
		 */
		$user = User::all()->where( 'email', $all['email'] );

		if ( $user->count() > 0 ) {//Đã tồn tại người dùng
			$response->error     = true;
			$response->error_msg = 'Đã tồn tại người dùng với email '
			                       . $all['email'];

			return response()->json( $response );
		}

		/**
		 * Xử lý tài khoản VNU - Đăng ký học
		 */
		$user_vnu = $all['mssv'];
		$pass_vnu = $all['password'];

		$login_vnu = getTimeTableVNU( $user_vnu, $pass_vnu );
		if ( $login_vnu === false ) {
			$response->error     = true;
			$response->error_msg = 'Mã sinh viên hoặc mật khẩu không đúng!';

			return response()->json( $response );
		}

		$user_name = $login_vnu['name'];

		/**
		 * Xử lý lớp khóa học
		 */
		$classX   = $all['lop'];
		$id_class = ClassX::getIdByClassName( $classX );

		if ( $id_class == false ) {//Lớp khóa học không tồn tại
			$response->error     = true;
			$response->error_msg = 'Lớp khóa học không tồn tại';

			return response()->json( $response );
		}

		$type = 'student';//Mặc định người dùng đăng ký là sinh viên
		$user = User::create( [
			'email'    => $all['email'],
			'password' => md5( $all['password'] ),
			'msv'      => $all['mssv'],
			'class'    => $id_class,
			'type'     => $type,
			'name'     => $user_name,
			'pass_uet' => base64_encode( $pass_vnu ),
		] );

		$response->error    = false;
		$response->uid      = $user->getAttribute( 'id' );
		$user_x             = new stdClass();
		$user_x->name       = $user->getAttribute( 'name' );
		$user_x->email      = $user->getAttribute( 'email' );
		$user_x->type       = $user->getAttribute( 'type' );
		$user_x->lop        = ClassX::getClassName( $id_class );
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

	/**
	 * API Login
	 *
	 * @param Request $request
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
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
		$user_x->lop
		                    = ClassX::getClassName( $user->getAttribute( 'class' ) );
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

	/**
	 * Update information user
	 *
	 * @param Request $request
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function update( Request $request ) {
		onlyAllowPostRequest( $request );

		$all = $request->only( [
			'email',
			'name',
			'mssv',
			'lop',
		] );

		/**
		 * Dữ liệu trả về
		 */
		$response = new stdClass();

		/**
		 * Xử lý lớp khóa học
		 */
		$classX   = $all['lop'];
		$id_class = ClassX::getIdByClassName( $classX );

		if ( $id_class == false ) {//Lớp khóa học không tồn tại
			$response->error     = true;
			$response->error_msg = 'Lớp khóa học không tồn tại';

			return response()->json( $response );
		}

		/**
		 * Tìm user bằng email
		 */
		$users = DB::table( 'users' )->where( 'email', $all['email'] );

		if ( $users->count() == 0 ) {
			$response->error     = true;
			$response->error_msg = 'Đã có lỗi gì đó xảy ra!';

			return response()->json( $response );
		}

		$updated = $users->update( [
			'name'  => ucwords( $all['name'] ),
			'msv'   => $all['mssv'],
			'class' => $id_class,
		] );

		if ( $updated == 0 ) {
			$response->error     = true;
			$response->error_msg = 'Cập nhật không có gì thay đổi!';

			return response()->json( $response );
		}

		$user = $users->first();

		$response->error    = false;
		$response->uid      = $user->id;
		$user_x             = new stdClass();
		$user_x->name       = $user->name;
		$user_x->email      = $user->email;
		$user_x->type       = $user->type;
		$user_x->lop        = ClassX::getClassName( $user->class );
		$user_x->mssv       = $user->msv;
		$user_x->created_at = date_create( $user->created_at )
			->setTimezone( new DateTimeZone( 'Asia/Ho_Chi_Minh' ) )
			->format( 'Y-m-d H:m:i' );
		$user_x->updated_at = date_create( $user->updated_at )
			->setTimezone( new DateTimeZone( 'Asia/Ho_Chi_Minh' ) )
			->format( 'Y-m-d H:m:i' );

		$response->user = $user_x;

		return response()->json( $response );
	}
}
