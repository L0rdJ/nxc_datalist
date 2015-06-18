<?php
/**
 * @package nxcDataList
 * @author  Serhey Dolgushev <serhey.dolgushev@nxc.no>
 * @date    26 Mar 2010
 **/

$module = $Params['Module'];

$filter = nxcDataListFilter::fetch( $Params['filterID'] );
if( !( $filter instanceof nxcDataListFilter ) ) {
	return $module->handleError( eZError::KERNEL_NOT_FOUND, 'kernel' );
}

$classID = ( $Params['classID'] ) ? $Params['classID'] : false;
$filter->setCurrent( $classID );

$http = eZHTTPTool::instance();
$module->redirectTo( $http->sessionVariable( 'RedirectURIAfterSetFilter' ) );
?>