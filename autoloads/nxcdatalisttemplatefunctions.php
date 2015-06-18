<?php
/**
 * @package nxcDataList
 * @class   nxcDataListTemplateFunctions
 * @author  Serhey Dolgushev <serhey.dolgushev@nxc.no>
 * @date    25 Mar 2010
 **/

class nxcDataListTemplateFunctions {

	public function operatorList() {
		return array( 'datalist_view' );
	}

	public function namedParameterPerOperator() {
		return true;
	}

	public function namedParameterList() {
		return array(
			'datalist_view' => array(
				'datalist_attribute' => array(
					'type'     => 'nxcDataListAttribute',
					'required' => true,
					'default'  => null
				)
			)
		);
	}

    public function modify(
		$tpl,
		$operatorName,
		$operatorParameters,
		&$rootNamespace,
		&$currentNamespace,
		&$operatorValue,
		&$namedParameters
	) {
		switch( $operatorName ) {
			case 'datalist_view': {
				$operatorValue = $namedParameters['datalist_attribute']->getViewContent( $operatorValue );
				break;
			}
		}
	}
}
?>