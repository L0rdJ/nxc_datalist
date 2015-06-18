<?php
/**
 * @package nxcDataList
 * @author  Serhey Dolgushev <serhey.dolgushev@nxc.no>
 * @date    25 Mar 2010
 **/

$module = $Params['Module'];
$http   = eZHTTPTool::instance();

$ini = eZINI::instance( 'nxcdatalist.ini' );
$availableClasses = $ini->variable( 'Datalist', 'AvailableClass' );
if( !isset( $availableClasses[ $Params['class'] ] ) ) {
	return $module->handleError( eZError::KERNEL_NOT_FOUND, 'kernel' );
}

$contentClass = eZContentClass::fetchByIdentifier( $Params['class'] );
if( !( $contentClass instanceof eZContentClass ) ) {
	return $module->handleError( eZError::KERNEL_NOT_FOUND, 'kernel' );
}
$http->setSessionVariable( 'RedirectURIAfterSetFilter', '/datalist/list/' . $contentClass->attribute( 'identifier' ) );


// Filters handling
if( $http->hasPostVariable( 'datalist_filter_save' ) && $http->hasPostVariable( 'datalist_filter_name' ) ) {
	$name = trim( strip_tags( $http->postVariable( 'datalist_filter_name' ) ) );
	if( strlen( $name ) > 0 ) {
		$filter = new nxcDataListFilter(
			array(
				'name'             => $name,
				'content_class_id' => $contentClass->attribute( 'id' )
			)
		);
		$filter->store();

		$filter->setCurrent();
	}
}
// If "Apply filter" button was clicked - setting default filter as current and applying values to it
// otherwise just fetching current filter
if( $http->hasPostVariable( 'datalist_filter_apply' ) ) {
	$currentFilter = nxcDataListFilter::fetch( 1 );
	$currentFilter->setCurrent( $contentClass->attribute( 'id' ) );
	$currentFilter->setAttribute( 'filter_values_serialized', serialize( $http->postVariable( 'datalist_filter' ) ) );
	$currentFilter->store();
} else {
	$currentFilter = nxcDataListFilter::getCurrent( $contentClass->attribute( 'id' ) );
}
$openedFilterAttributeGroup = $currentFilter->getOpenedAttributeGroups( $contentClass->attribute( 'id' ) );
eZDebug::writeDebug( $openedFilterAttributeGroup, 'NXC Datalist - filter opened attribute groups' );


$dataListSettings = nxcDataListSettings::fetch( $contentClass );
$allFilters       = array_merge(
	array( nxcDataListFilter::fetch( 1 ) ),
	eZPersistentObject::fetchObjectList(
		nxcDataListFilter::definition(),
		null,
		array( 'content_class_id' => $contentClass->attribute( 'id' ) ),
		true
	)
);


// Additional addtribute group handling
$currentAdditionalAttrGroups = array();
if( $http->hasSessionVariable( 'datalistAdditionalAttrGroups' ) && is_array( $http->sessionVariable( 'datalistAdditionalAttrGroups' ) ) ) {
	$currentAdditionalAttrGroups = $http->sessionVariable( 'datalistAdditionalAttrGroups' );
}
if( $http->hasPostVariable( 'datalist_additional_attribute_group_apply' ) ) {
	$currentAdditionalAttrGroups[ $contentClass->attribute( 'id' ) ] = $http->postVariable( 'datalist_additional_attribute_group' );
	$http->setSessionVariable( 'datalistAdditionalAttrGroups', $currentAdditionalAttrGroups );
}
$additionalAttributeGroup = ( isset( $currentAdditionalAttrGroups[ $contentClass->attribute( 'id' ) ] ) ) ? $currentAdditionalAttrGroups[ $contentClass->attribute( 'id' ) ] : -1;



// Check edit mode
$classID   = $contentClass->attribute( 'id' );
$editModes = (array) $http->sessionVariable( 'nxc_data_list_edit_modes', array() );
$edtiMode  = in_array( $classID, $editModes ) ? (bool) $editModes[ $classID ] : false;


include_once( 'kernel/common/template.php' );
$tpl = templateInit();
$tpl->setVariable( 'edit_mode', $edtiMode );
$tpl->setVariable( 'data_list_settings', $dataListSettings );
$tpl->setVariable( 'opened_filter_attribute_groups', $openedFilterAttributeGroup );
$tpl->setVariable( 'all_filters', $allFilters );
$tpl->setVariable( 'current_filter', $currentFilter );
$tpl->setVariable( 'additional_attribute_group', $additionalAttributeGroup );
$tpl->setVariable( 'viewable_attributes', $dataListSettings->getViewableAttributes( $additionalAttributeGroup ) );
$tpl->setVariable( 'nodes_count', nxcDataListOperations::fetchNodesCount( $dataListSettings ) );

eZDebug::writeDebug( 'Memory usage', number_format( memory_get_usage() / 1024 / 1024, 2 ) . ' Mb' );

$Result = array();
$Result['content']         = $tpl->fetch( 'design:datalist/index.tpl' );
$Result['navigation_part'] = $dataListSettings->getNavigationPath();
$Result['left_menu']       = $dataListSettings->getLeftMenu();
$Result['path']            = $dataListSettings->getPath();
?>