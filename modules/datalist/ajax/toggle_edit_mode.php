<?php
/**
 * @package nxcDataList
 * @author  Serhey Dolgushev <serhey.dolgushev@nxc.no>
 * @date    25 Jan 2012
 **/

$response = new nxcMootoolsAJAXResponse();
$response->setStatus( nxcMootoolsAJAXResponse::STATUS_SUCCESS );

$http      = eZHTTPTool::instance();
$classID   = (int) $Params['classID'];
$editModes = (array) $http->sessionVariable( 'nxc_data_list_edit_modes', array() );
$current   = in_array( $classID, $editModes ) ? $editModes[ $classID ] : false;

$message = $current ? 'Edit mode is disabled' : 'Edit mode is enabled' ;
$response->setMessage( ezi18n( 'extension/datalist', $message ) );
$response->editMode    = (int) !$current;
$editModes[ $classID ] = !$current;
$http->setSessionVariable( 'nxc_data_list_edit_modes', $editModes );

$response->output();
?>