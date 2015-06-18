<?php
/**
 * @package nxcDataList
 * @author  Serhey Dolgushev <serhey.dolgushev@nxc.no>
 * @date    30 Jan 2012
 **/

$module = $Params['Module'];
$http   = eZHTTPTool::instance();

$nodeIDs = $http->hasPostVariable( 'datalist_action_nodes' )
	? $http->postVariable( 'datalist_action_nodes' )
	: array();
$attributes = $http->hasPostVariable( 'nxc-data-list-edit' )
	? $http->postVariable( 'nxc-data-list-edit' )
	: array();

$class = null;
$data  = array();
if( is_array( $nodeIDs ) ) {
	foreach( $nodeIDs as $nodeID ) {
		$node = eZContentObjectTreeNode::fetch( $nodeID );
		if(
			$node instanceof eZContentObjectTreeNode
			&& isset( $attributes[ $nodeID ] )
		) {
			if( $class === null ) {
				$identifier = $node->attribute( 'class_identifier' );
				$class      = eZContentClass::fetchByIdentifier( $identifier );
			}
			$data[ $nodeID ] = $attributes[ $nodeID ];
		}
		unset( $node );
	}
}

if( count( $data ) > 0 ) {
	$pc      = new nxcPowerContent();
	$dataMap = $class->attribute( 'data_map' );
	foreach( $data as $nodeID => $attributes ) {
		$node = eZContentObjectTreeNode::fetch( $nodeID );
		if(
			$node instanceof eZContentObjectTreeNode === false
			|| $node->attribute( 'object' ) instanceof eZContentObject === false
		) {
			continue;
		}

		foreach( $attributes as $attribute => $value ) {
			if( isset( $dataMap[ $attribute ] ) === false ) {
				continue;
			}

			$newValue       = false;
			$classAttribute = $dataMap[ $attribute ];
			switch( $classAttribute->attribute( 'data_type_string' ) ) {
				case 'ezstring':
				case 'eztext':
				case 'ezbirthday': {
					$newValue = $value;
					break;
				}
				case 'ezdate':
				case 'ezdatetime':
				case 'ezboolean':
				case 'ezinteger': {
					$newValue = (int) $value;
					break;
				}
				case 'ezfloat': {
					$newValue = (float) $value;
					break;
				}
				case 'ezcountry': {
					if( is_array( $value ) ) {
						$newValue = implode( ',', $value );
					}
					break;
				}
				case 'ezselection': {
					if( is_array( $value ) ) {
						$newValue = implode( '|', $value );
					}
					break;
				}
				case 'eztime': {
					if(
						is_array( $value )
						&& isset( $value['hour'] )
						&& isset( $value['minute'] )
						&& isset( $value['second'] )
					) {
						$newValue = (int) $value['hour'] . ':' . (int) $value['minute']
							. ':' . (int) $value['second'];
					}
					break;
				}
				case 'ezurl': {
					if(
						is_array( $value )
						&& isset( $value['url'] )
					) {
						$newValue = $value['url'];
						if(
							isset( $value['text'] )
							&& strlen( $value['text'] ) > 0
						) {
							$newValue .= '|' . $value['text'];
						}
					}
					break;
				}
				case 'ezemail': {
					$value = trim( $value );
					if(
						$value == ''
						|| eZMail::validate( $value )
					) {
						$newValue = $value;
					}
				}
			}
			if( $newValue === false ) {
				unset( $attributes[ $attribute ] );
			} else {
				$attributes[ $attribute ] = $newValue;
			}
		}

		$object = $node->attribute( 'object' );
		$pc->updateObject( $object, $attributes );
		
		eZContentObject::clearCache( $object->attribute( 'id' ) );
		$object->resetDataMap();
		unset( $object );
		unset( $node );
	}
}

if( $http->hasSessionVariable( 'RedirectURIAfterSetFilter' ) ) {
	$module->redirectTo( $http->sessionVariable( 'RedirectURIAfterSetFilter' ) );
}
?>
