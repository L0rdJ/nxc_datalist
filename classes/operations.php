<?php
/**
 * @package nxcDataList
 * @class   nxcDataListOperations
 * @author  Serhey Dolgushev <serhey.dolgushev@nxc.no>
 * @date    25 Mar 2010
 **/

class nxcDataListOperations {

	private function __construct() {
	}

	public static function fetchNodes( nxcDataListSettings $dataListSettings, $offset = null, $limit = null ) {
		$fetchParams = array(
			'Depth'            => false,
			'ClassFilterType'  => 'include',
			'ClassFilterArray' => array( $dataListSettings->attribute( 'content_object_class' )->attribute( 'identifier' ) ),
			'LoadDataMap'      => false,
			'AsObject'         => true,
			'Limitation'       => array(),
			'SortBy'           => array( 'published', false )
		);

		$params = self::getFetchParams( $dataListSettings );

		if( count( $params['callbackFilter'] ) === 0 ) {
			if( is_null( $offset ) === false ) {
				$fetchParams['Offset'] = $offset;
			}
			if( is_null( $limit ) === false ) {
				$fetchParams['Limit'] = $limit;
			}
		}

		if( count( $params['attributeFilter'] ) > 0 ) {
			$fetchParams['AttributeFilter'] = $params['attributeFilter'];
		}
		if( count( $params['extenedAttributeFilter'] ) > 0 ) {
			$fetchParams['ExtendedAttributeFilter'] = array(
				'id'     => 'nxc_extendedfilter',
				'params' => array(
					'sub_filters' => $params['extenedAttributeFilter']
				)
			);
		}

		$nodes = eZContentObjectTreeNode::subTreeByNodeID( $fetchParams, $params['parentNodeID'] );
		foreach( $params['callbackFilter'] as $callbackFilter ) {
			$nodes = call_user_func(
				$callbackFilter['callback'],
				array_merge( $callbackFilter['params'], array( 'nodes' => $nodes ) )
			);
		}

		if( count( $params['callbackFilter'] ) > 0 ) {
			if( is_null( $limit ) === false ) {
				$offset = is_null( $offset ) ? 0 : $offset;
				$nodes  = array_slice( $nodes, $offset, $limit );
			}
		}

		return $nodes;
	}

	public static function fetchNodesCount( nxcDataListSettings $dataListSettings ) {
		$fetchParams = array(
			'Depth'            => false,
			'ClassFilterType'  => 'include',
			'ClassFilterArray' => array( $dataListSettings->attribute( 'content_object_class' )->attribute( 'identifier' ) ),
			'LoadDataMap'      => false,
			'AsObject'         => true,
			'Limitation'       => array()
		);

		$params = self::getFetchParams( $dataListSettings );
		if( count( $params['attributeFilter'] ) > 0 ) {
			$fetchParams['AttributeFilter'] = $params['attributeFilter'];
		}
		if( count( $params['extenedAttributeFilter'] ) > 0 ) {
			$fetchParams['ExtendedAttributeFilter'] = array(
				'id'     => 'nxc_extendedfilter',
				'params' => array(
					'sub_filters' => $params['extenedAttributeFilter']
				)
			);
		}

		$nodes = eZContentObjectTreeNode::subTreeByNodeID( $fetchParams, $params['parentNodeID'] );
		foreach( $params['callbackFilter'] as $callbackFilter ) {
			$nodes = call_user_func(
				$callbackFilter['callback'],
				array_merge( $callbackFilter['params'], array( 'nodes' => $nodes ) )
			);
		}
		return count( $nodes );
	}

	private static function getFetchParams( nxcDataListSettings $dataListSettings ) {
		$params = array(
			'parentNodeID'           => 1,
			'attributeFilter'        => array(),
			'extenedAttributeFilter' => array(),
			'callbackFilter'         => array()
		);

		$parentNodes  = $dataListSettings->attribute( 'parent_nodes' );
		if( count( $parentNodes ) === 1 ) {
			$params['parentNodeID'] = $parentNodes[0]->attribute( 'node_id' );
		}

		$filter = nxcDataListFilter::getCurrent( $dataListSettings->attribute( 'content_object_class' )->attribute( 'id' ) );

		foreach( $dataListSettings->attribute( 'all_attributes' ) as $dataListAttribute ) {
			if( !( $dataListAttribute instanceof nxcDataListAttribute ) ) {
				continue;
			}

			$filterValue = $dataListAttribute->getFilterValue(
				$filter,
				$dataListSettings->attribute( 'content_object_class' )->attribute( 'identifier' ) . '/'
			);

			if( isset( $filterValue['parentNodeID'] ) ) {
				$params['parentNodeID'] = $filterValue['parentNodeID'];
			}

			if( isset( $filterValue['attributeFilter'] ) ) {
				$params['attributeFilter'][] = $filterValue['attributeFilter'];
			}

			if( isset( $filterValue['extenedAttributeFilter'] ) ) {
				$params['extenedAttributeFilter'][] = $filterValue['extenedAttributeFilter'];
			}

			if( isset( $filterValue['callback'] ) ) {
				$params['callbackFilter'][] = $filterValue;
			}
		}

		eZDebug::writeDebug( $params['parentNodeID'], 'NXC Datalist - parent node id' );
		eZDebug::writeDebug( $params['attributeFilter'], 'NXC Datalist - attribute filters' );
		eZDebug::writeDebug( $params['extenedAttributeFilter'], 'NXC Datalist - extended attribute filters' );
		eZDebug::writeDebug( $params['callbackFilter'], 'NXC Datalist - callback filters' );

		return $params;
	}
}
?>
