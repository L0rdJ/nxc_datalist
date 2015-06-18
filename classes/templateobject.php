<?php
/**
 * @package nxcDataList
 * @class   nxcTemplateObject
 * @author  Serhey Dolgushev <serhey.dolgushev@nxc.no>
 * @date    25 Mar 2010
 **/

abstract class nxcDataListTemplateObject {

	protected $attributes         = array();
	protected $functionAttributes = array();

	public function __construct() {
		return $this;
	}

	public function attributes() {
		return array_keys( array_merge( $this->attributes, $this->functionAttributes ) );
	}

	public function hasAttribute( $attr ) {
		if( isset( $this->attributes[ $attr ] ) ) {
			return true;
		} elseif( isset( $this->functionAttributes[ $attr ] ) ) {
			return true;
		} else {
			return false;
		}
	}

	public function attribute( $attr ) {
		$value = null;

		if( isset( $this->attributes[ $attr ] ) ) {
			$value = $this->attributes[ $attr ];
		} elseif( isset( $this->functionAttributes[ $attr ] ) ) {
			$methodName = $this->functionAttributes[ $attr ];
			if( method_exists( $this, $methodName ) ) {
                $value = call_user_func( array( $this, $methodName ) );
            }
		}

		return $value;;
	}

	public function setAttribute( $attr, $value ) {
		$this->attributes[ $attr ] = $value;
		return $this;
	}
}
?>