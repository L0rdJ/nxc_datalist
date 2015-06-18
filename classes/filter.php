<?php
/**
 * @package nxcDataList
 * @class   nxcDataListFilter
 * @author  Serhey Dolgushev <serhey.dolgushev@nxc.no>
 * @date    25 Mar 2010
 **/

class nxcDataListFilter extends eZPersistentObject {

	public function __construct( $row = array() ) {
		$this->eZPersistentObject( $row );

		$http = eZHTTPTool::instance();
		if( $http->hasPostVariable( 'datalist_filter' ) ) {
			$this->setAttribute( 'filter_values_serialized', serialize( $http->postVariable( 'datalist_filter' ) ) );
		}
	}

	public static function definition() {
		return array(
			'fields'              => array(
				'id' => array(
					'name'     => 'id',
					'datatype' => 'integer',
					'default'  => 0,
					'required' => true
				),
				'content_class_id' => array(
					'name'     => 'contentClassID',
					'datatype' => 'integer',
					'default'  => 0,
					'required' => true
				),
				'name' => array(
					'name'     => 'Name',
					'datatype' => 'string',
					'default'  => '',
					'required' => true
				),
				'filter_values_serialized' => array(
					'name'     => 'filterValuesSerialized',
					'datatype' => 'string',
					'default'  => '',
					'required' => true
				)

			),
			'function_attributes' => array(
				'filter_values'   => 'getFilterValues'
			),
			'keys'                => array( 'id' ),
			'sort'                => array( 'id' => 'desc' ),
			'increment_key'       => 'id',
			'class_name'          => 'nxcDataListFilter',
			'name'                => 'nxc_datalist_filters'
		);
	}

	protected function getFilterValues() {
		return unserialize( $this->attribute( 'filter_values_serialized' ) );
	}

	public static function getCurrent( $classID ) {
		$http = eZHTTPTool::instance();

		$currentClassFilters = array();
		if( $http->hasSessionVariable( 'datalistCurrentFilters' ) && is_array( $http->sessionVariable( 'datalistCurrentFilters' ) ) ) {
			$currentClassFilters = $http->sessionVariable( 'datalistCurrentFilters' );
		}

		$currentFilter = false;
		if( isset( $currentClassFilters[ $classID ] ) ) {
			$currentFilter = self::fetch( $currentClassFilters[ $classID ] );
		}

		if( !( $currentFilter instanceof nxcDataListFilter ) ) {
			$currentFilter = self::fetch( 1 );
			// Reseting default filter values
			$currentFilter->setAttribute( 'filter_values_serialized', serialize( array() ) );
			$currentFilter->store();
		}

		return $currentFilter;
	}

	public function setCurrent( $classID = false ) {
		$http = eZHTTPTool::instance();

		$currentClassFilters = array();
		if( $http->hasSessionVariable( 'datalistCurrentFilters' ) && is_array( $http->sessionVariable( 'datalistCurrentFilters' ) ) ) {
			$currentClassFilters = $http->sessionVariable( 'datalistCurrentFilters' );
		}

		// Default filter has no content_class_id attribute
		if( $classID === false ) {
			$classID = $this->attribute( 'content_class_id' );
		}
		// Reseting default filter values
		if( $this->attribute( 'id' ) == 1 ) {
			$this->setAttribute( 'filter_values_serialized', serialize( array() ) );
			$this->store();
		}

		$currentClassFilters[ $classID ] = $this->attribute( 'id' );

		$http->setSessionVariable( 'datalistCurrentFilters', $currentClassFilters );
	}

	public function getOpenedAttributeGroups( $classID ) {
		$class             = eZContentClass::fetch( $classID );
		$dataListSettings  = nxcDataListSettings::fetch( $class );
		$groupedAttributes = $dataListSettings->getGroupedAttributes();
		$allAttributes     = $dataListSettings->attribute( 'all_attributes' );

		$currentFilter = self::getCurrent( $classID );
		$filterValues  = $currentFilter->attribute( 'filter_values' );

		$wwwDir     = eZSys::wwwDir();
	    $cookiePath = $wwwDir != '' ? $wwwDir : '/';

		$openedGourps = array();
		foreach( $groupedAttributes as $groupIdentifier => $attributes ) {
			$isGroupOpened = false;
			foreach( $attributes as $attributeIdentifier ) {
				if( !( $allAttributes[ $attributeIdentifier ] instanceof nxcDataListAttribute ) ) {
					continue;
				}
				$attribute        = $allAttributes[ $attributeIdentifier ];
				$attrFilterValues = ( isset( $filterValues[ $attributeIdentifier ] ) ) ? $filterValues[ $attributeIdentifier ] : false;

				switch( $attribute->attribute( 'filter_type' ) ) {
					case nxcDataListAttribute::FILTER_TYPE_STRING:
					case nxcDataListAttribute::FILTER_TYPE_FETCH_PARAMS_CALLBACK: {
						if( strlen( $attrFilterValues ) > 0 ) {
							$isGroupOpened = true;
						}
						break;
					}
					case nxcDataListAttribute::FILTER_TYPE_NUMERIC: {
						if( is_numeric( $attrFilterValues['min'] ) || is_numeric( $attrFilterValues['max'] ) ) {
							$isGroupOpened = true;
						}
						break;
					}
					case nxcDataListAttribute::FILTER_TYPE_BOOL: {
						if( $attrFilterValues !== false ) {
							$isGroupOpened = true;
						}
						break;
					}
					case nxcDataListAttribute::FILTER_TYPE_DATE:
					case nxcDataListAttribute::FILTER_TYPE_BIRTHDAY: {
						if( is_numeric( $attrFilterValues['start'] ) || is_numeric( $attrFilterValues['end'] ) ) {
							$isGroupOpened = true;
						}
						break;
					}
					case nxcDataListAttribute::FILTER_TYPE_SELECTION: {
						if( $attrFilterValues != '' && $attrFilterValues != -1 ) {
							$isGroupOpened = true;
						}
						break;
					}
					case nxcDataListAttribute::FILTER_TYPE_USER_ACCOUNT: {
						if(
							strlen( $attrFilterValues['login'] ) > 0 ||
							strlen( $attrFilterValues['email'] ) > 0 ||
							isset( $attrFilterValues['enabled'] ) > 0
						) {
							$isGroupOpened = true;
						}
						break;
					}
					case nxcDataListAttribute::FILTER_TYPE_EMAIL: {
						if( strlen( $attrFilterValues ) > 0 ) {
							$isGroupOpened = true;
						}
						break;
					}
					case nxcDataListAttribute::FILTER_TYPE_RELATION_LIST: {
						if( $attrFilterValues != '' && $attrFilterValues != -1 ) {
							$isGroupOpened = true;
						}
						break;
					}
				}

				if( $isGroupOpened === true ) {
					break;
				}
			}

			if( $isGroupOpened ) {
				$openedGourps[] = $groupIdentifier;
			}
		}

		return $openedGourps;
	}

	public static function fetch( $id ) {
		return eZPersistentObject::fetchObject(
			self::definition(),
			null,
			array( 'id' => $id ),
			true
		);
	}
}
?>