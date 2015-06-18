<?php
/**
 * @package nxcDataList
 * @author  Serhey Dolgushev <serhey.dolgushev@nxc.no>
 * @date    26 Mar 2010
 **/

$response = new nxcMootoolsAJAXResponse();

$filter = nxcDataListFilter::fetch( $Params['filterID'] );
if( !( $filter instanceof nxcDataListFilter ) || ( $filter->attribute( 'id' ) == 1 ) ) {
	$response->setStatus( nxcMootoolsAJAXResponse::STATUS_ERROR );
	$response->addError( ezi18n( 'extension/datalist', 'Can`t fetch filter' ), 'filter' );
} else {
	$filter->remove();

	$response->setStatus( nxcMootoolsAJAXResponse::STATUS_SUCCESS );
	$response->setMessage(
		ezi18n(
			'extension/datalist',
			'Filter "%filter_name" removed',
			null,
			array( '%filter_name' => $filter->attribute( 'name' ) )
		)
	);
}
$response->output();
?>