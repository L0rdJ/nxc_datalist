<?php
/**
 * @package nxcDataList
 * @author  Serhey Dolgushev <serhey.dolgushev@nxc.no>
 * @date    19 Apr 2010
 **/

$class            = eZContentClass::fetch( $Params['classID'] );
$dataListSettings = nxcDataListSettings::fetch( $class );

$http   = eZHTTPTool::instance();
$currentAdditionalAttrGroups = array();
if( $http->hasSessionVariable( 'datalistAdditionalAttrGroups' ) && is_array( $http->sessionVariable( 'datalistAdditionalAttrGroups' ) ) ) {
	$currentAdditionalAttrGroups = $http->sessionVariable( 'datalistAdditionalAttrGroups' );
}
$additionalAttributeGroup = ( isset( $currentAdditionalAttrGroups[ $class->attribute( 'id' ) ] ) ) ? $currentAdditionalAttrGroups[ $class->attribute( 'id' ) ] : -1;

$limit = (int) $Params['limit'];
if( $limit <= 0 || $limit > 500 ) {
	$limit = 10;
}

// We are using nxc_data_list_edit_mode sssion variable to not deifne each time edit mode in nxcDataListAttribute::getViewContent
$http->setSessionVariable( 'nxc_data_list_edit_mode', 0 );
$classID   = $class->attribute( 'id' );
$editModes = (array) $http->sessionVariable( 'nxc_data_list_edit_modes', array() );
$edtiMode  = in_array( $classID, $editModes ) ? (bool) $editModes[ $classID ] : false;
$http->setSessionVariable( 'nxc_data_list_edit_mode', (int) $edtiMode );

include_once( 'kernel/common/template.php' );
$tpl = templateInit();
$tpl->setVariable( 'edit_mode', $edtiMode );
$tpl->setVariable( 'viewable_attributes', $dataListSettings->getViewableAttributes( $additionalAttributeGroup ) );
$tpl->setVariable( 'nodes', nxcDataListOperations::fetchNodes( $dataListSettings, $Params['offset'], $limit ) );
$content = $tpl->fetch( 'design:datalist/nodes_list.tpl' );
echo '<!-- Memory usage ' . number_format( memory_get_usage() / 1024 / 1024, 2 ) . ' Mb -->' . "\n";
echo $content;
eZExecution::cleanExit();
?>