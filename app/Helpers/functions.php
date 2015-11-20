<?php
/**
 * Created by PhpStorm.
 * User: Tu TV
 * Date: 20/11/2015
 * Time: 7:08 PM
 */
use Illuminate\Http\Request;

require_once __DIR__ . '/browser/FCurl.php';
require_once __DIR__ . '/browser/simple_html_dom.php';

/**
 * Only allowed POST Request | Abort 404 when request different POST request
 *
 * @param $request
 */
function onlyAllowPostRequest( Request $request ) {
	if ( method_exists( $request, 'getMethod' )
	     && $request->getMethod() !== 'POST'
	) {
		abort( 404 );
	}
}

function getTimeTableVNU( $user, $pass ) {
	$url            = 'http://dangkyhoc.daotao.vnu.edu.vn/dang-nhap';
	$browser        = new fCurl();
	$browser->refer = $url;
	$browser->resetopt();
	$browser->get( $browser->refer, true, 1 );
	$browser->get( $browser->refer, true, 1 );

	$str_temp = $browser->return;
	$str_temp = explode( '__RequestVerificationToken', $str_temp )[1];
	$str_temp = explode( '>', $str_temp )[0];
	$str_temp = explode( 'value="', $str_temp )[1];
	$verti    = explode( '"', $str_temp )[0];

	/**
	 * Đăng nhập
	 */
	$field = [
		'LoginName'                  => $user,
		'Password'                   => $pass,
		'__RequestVerificationToken' => $verti,
	];
	$browser->post( $url, $field, 1, 1 );

	$contentX = $browser->return;

	if ( strpos( $contentX, '/Account/Logout' ) === false ) {
		return false;
	}

	/**
	 * Lấy source trang thời khóa biểu
	 */
	$url_time
		= 'http://dangkyhoc.daotao.vnu.edu.vn/xem-va-in-ket-qua-dang-ky-hoc/1?layout=main';
	$browser->get( $url_time, 1, 0 );
	$source_html = $browser->return;

	$timeTable
		       = explode( '<table style="border:none; width: 100%; border-collapse:collapse;">',
		$source_html )[1];
	$timeTable = explode( '</table>', $timeTable )[0];

	return handleSourceTimeTable( $timeTable );
}

function handleSourceTimeTable( $timeTable ) {
	$trs       = explode( '<tr>', $timeTable );
	$count_str = count( $trs );

	$arrLMH = [ ];

	for ( $i = 2; $i < $count_str - 1; $i ++ ) {
		$tr = $trs[ $i ];

		$maLMH = explode( '<td', $tr )[7];
		$maLMH = explode( '</td>', $maLMH )[0];
		$maLMH = explode( '&nbsp;', $maLMH )[1];

		$arrLMH[] = $maLMH;
	}

	return $arrLMH;
}