<?php
/**
 * @package nxcDataList
 * @author  Serhey Dolgushev <serhey.dolgushev@nxc.no>
 * @date    30 Mar 2010
 **/

$module = $Params['Module'];
$http   = eZHTTPTool::instance();

if( $http->hasPostVariable( 'datalist_action_select' ) && $http->hasPostVariable( 'datalist_action_nodes' ) ) {
	$nodeIDs = $http->postVariable( 'datalist_action_nodes' );
	if( is_array( $nodeIDs ) ) {
		$http->setSessionVariable( 'datalist_action_nodes', $nodeIDs );
	}
	$module->redirectTo( $http->postVariable( 'datalist_action_select' ) );
} else {
	return $module->handleError( eZError::KERNEL_NOT_FOUND, 'kernel' );
}
?>