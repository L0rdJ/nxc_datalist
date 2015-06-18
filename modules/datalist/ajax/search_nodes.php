<?php
/**
 * @package nxcDataList
 * @author  Serhey Dolgushev <serhey.dolgushev@nxc.no>
 * @date    13 May 2010
 **/

$data = array();

$fetchParams = array(
	'Depth'            => false,
	'ClassFilterType'  => 'include',
	'ClassFilterArray' => array( $Params['class'] ),
	'LoadDataMap'      => false,
	'AsObject'         => true,
	'Limitation'       => array(),
	'AttributeFilter'  => array(
		array( 'name', 'like', '*' . strtolower( $_GET['q'] ) . '*' )
	),
	'SortBy'           => array( 'name', true ),
	'Limit'            => 25
);

$nodes = eZContentObjectTreeNode::subTreeByNodeID( $fetchParams, $Params['parentNodeID'] );
foreach( $nodes as $node ) {
	$data[] = array(
		'id'    => $node->attribute( 'contentobject_id' ),
		'value' => $node->attribute( 'name' )
	);
}

echo json_encode( $data );
eZExecution::cleanExit();
?>