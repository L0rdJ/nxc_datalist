<?php
/**
 * @package nxcDataList
 * @class   nxcDataListAttributesGroup
 * @author  Serhey Dolgushev <serhey.dolgushev@nxc.no>
 * @date    25 Mar 2010
 **/

class nxcDataListAttributesGroup extends nxcDataListTemplateObject {

	protected $attributes = array(
		'identifier'           => null,
		'name'                 => null,
		'data_list_attributes' => array()
	);

	protected $functionAttributes = array(
		'has_viewable_attribute'  => 'hasViewableAttribute',
		'has_filtrable_attribute' => 'hasFiltrableAttribute'
	);

	public function __construct( $identifier, $name ) {
		$this->setAttribute( 'identifier', $identifier );
		$this->setAttribute( 'name', $name );
	}

	public function addDataListAttribute( nxcDataListAttribute $attribute ) {
		$this->attributes['data_list_attributes'][ $attribute->attribute( 'identifier' ) ] = $attribute;
	}

	protected function hasViewableAttribute() {
		foreach( $this->attribute( 'data_list_attributes' ) as $attribute ) {
			if( $attribute->attribute( 'datalist_view_template' ) !== null ) {
				return true;
			}
		}
		return false;
	}

	protected function hasFiltrableAttribute() {
		foreach( $this->attribute( 'data_list_attributes' ) as $attribute ) {
			if( $attribute->attribute( 'filterable' )  === true ) {
				return true;
			}
		}
		return false;
	}
}
?>