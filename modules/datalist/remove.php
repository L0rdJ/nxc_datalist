<?php
/**
 * @package nxcDataList
 * @author  Serhey Dolgushev <serhey.dolgushev@nxc.no>
 * @date    30 Mar 2010
 **/

$module = $Params['Module'];
$http   = eZHTTPTool::instance();

$nodeIDs = $http->sessionVariable( 'datalist_action_nodes' );
$http->removeSessionVariable( 'datalist_action_nodes' );

$powerContentHandler = new nxcPowerContent();
if( is_array( $nodeIDs ) ) {
	foreach( $nodeIDs as $nodeID ) {
		$node = eZContentObjectTreeNode::fetch( $nodeID );

		if( !( $node instanceof eZContentObjectTreeNode ) ) {
			continue;
		}

		$powerContentHandler->removeObject( $node->attribute( 'object' ) );
	}
}

if( $http->hasSessionVariable( 'RedirectURIAfterSetFilter' ) ) {
	$module->redirectTo( $http->sessionVariable( 'RedirectURIAfterSetFilter' ) );
}
?>