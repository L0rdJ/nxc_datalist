<?php
/**
 * @package nxcDataList
 * @author  Serhey Dolgushev <serhey.dolgushev@nxc.no>
 * @date    31 Mar 2010
 **/

$response = new nxcMootoolsAJAXResponse();
$response->setStatus( nxcMootoolsAJAXResponse::STATUS_SUCCESS );

$node = eZContentObjectTreeNode::fetch( $Params['nodeID'] );
if( !( $node instanceof eZContentObjectTreeNode ) ) {
	$response->setStatus( nxcMootoolsAJAXResponse::STATUS_ERROR );
	$response->addError( ezi18n( 'extension/datalist', 'Can not fetch the node' ), 'toggle_bool_attribute' );
}

if( $response->getStatus !== nxcMootoolsAJAXResponse::STATUS_ERROR ) {
	$dataMap = $node->attribute( 'data_map' );
	if( !isset( $dataMap[ $Params['attribute'] ] ) ) {
		$response->setStatus( nxcMootoolsAJAXResponse::STATUS_ERROR );
		$response->addError(
			ezi18n(
				'extension/datalist',
				'Can not find "%attribute_name" attribute',
				null,
				array( '%attribute_name' => $Params['attribute'] )
			),
			'toggle_bool_attribute'
		);
	}
}

if( $response->getStatus !== nxcMootoolsAJAXResponse::STATUS_ERROR ) {
	$attribute = $dataMap[ $Params['attribute'] ];
	if( $attribute->attribute( 'data_type_string' ) != 'ezboolean' ) {
		$response->setStatus( nxcMootoolsAJAXResponse::STATUS_ERROR );
		$response->addError(
			ezi18n(
				'extension/datalist',
				'Attribute "%attribute_name" is not boolean',
				null,
				array( '%attribute_name' => $Params['attribute'] )
			),
			'toggle_bool_attribute'
		);
	}
}

$attribute->fromString( (int) !( (bool) $attribute->toString() ) );
$attribute->store();

$response->attributeNewValue = $attribute->toString();
$response->output();
?>