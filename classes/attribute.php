<?php
/**
 * @package nxcDataList
 * @class   nxcDataListAttribute
 * @author  Serhey Dolgushev <serhey.dolgushev@nxc.no>
 * @date    25 Mar 2010
 **/

class nxcDataListAttribute extends nxcDataListTemplateObject {

	const FILTER_TYPE_STRING                = 1;
	const FILTER_TYPE_NUMERIC               = 2;
	const FILTER_TYPE_BOOL                  = 3;
	const FILTER_TYPE_DATE                  = 4;
	const FILTER_TYPE_SELECTION             = 5;
	const FILTER_TYPE_EMAIL                 = 6;
	const FILTER_TYPE_USER_ACCOUNT          = 7;
	const FILTER_TYPE_RELATION_LIST         = 8;
	const FILTER_TYPE_BIRTHDAY              = 9;
	const FILTER_TYPE_IMAGE                 = 10;
	const FILTER_TYPE_FETCH_PARAMS_CALLBACK = 11;

	protected $attributes = array(
		'identifier'                   => null,
		'name'                         => null,
		'filterable'                   => false,
		'filter_parent_node'           => false,
		'filter_type'                  => null,
		'filter_attr_prefix'           => null,
		'filter_view_template'         => null,
		'filter_view_params'           => array(
			'selection_options' => array()
		),
		/* is used to filer results after fetch */
		'filter_callback'              => false,
		'datalist_view_template'       => null,
		'datalist_edit_template'       => null,
		'datalist_view_params'         => array(),
		'export_headers'               => array(),
		'filter_fetch_params_callback' => false
	);

	protected $functionAttributes = array(
		'filter_view' => 'getFilterView'
	);

	public function __construct( $identifier, $name ) {
		$this->setAttribute( 'identifier', $identifier );
		$this->setAttribute( 'name', $name );

		return parent::__construct();
	}

	public function getFilterView() {
		$template = $this->attribute( 'filter_view_template' );
		if( $template === null ) {
			switch( $this->attribute( 'filter_type' ) ) {
				case self::FILTER_TYPE_NUMERIC:
					$template = 'numeric';
					break;
				case self::FILTER_TYPE_BOOL:
					$template = 'boolean';
					break;
				case self::FILTER_TYPE_DATE:
				case self::FILTER_TYPE_BIRTHDAY:
					$template = 'date';
					break;
				case self::FILTER_TYPE_SELECTION:
					$template = 'selection';
					break;
				case self::FILTER_TYPE_EMAIL:
					$template = 'email';
					break;
				case self::FILTER_TYPE_USER_ACCOUNT:
					$template = 'useraccount';
					break;
				case self::FILTER_TYPE_RELATION_LIST:
					$template = 'relationlist';
					break;
				default:
					$template = 'string';
			}
		}

		include_once( 'kernel/common/template.php' );
		$tpl = templateInit();
		$tpl->setVariable( 'data_list_attribute', $this );
		return $tpl->fetch( 'design:datalist/filter/' . $template . '.tpl' );
	}

	public function getFilterValue( nxcDataListFilter $filter, $attrPathPrefix ) {
		$return = array();
		$currentFilterValues = $filter->attribute( 'filter_values' );
		$identifier = $this->attribute( 'identifier' );
		$path = $identifier;
		if( is_null( $this->attribute( 'filter_attr_prefix' ) ) === false ) {
			$path = $this->attribute( 'filter_attr_prefix' ) . $identifier;
		}

		if( isset( $currentFilterValues[ $identifier ] ) && $this->attribute( 'filterable' ) ) {
			$value = $currentFilterValues[ $identifier ];

			if( $this->attribute( 'filter_callback' ) === false ) {
				switch( $this->attribute( 'filter_type' ) ) {
					case self::FILTER_TYPE_STRING: {
						if( strlen( $value ) > 0 ) {
							$return['attributeFilter'] = array( $path, 'like', '*' . $value . '*' );
						}
						break;
					}
					case self::FILTER_TYPE_NUMERIC: {
						if( is_array( $value ) ) {
							if( is_numeric( $value['min'] ) && is_numeric( $value['max'] ) ) {
								$return['attributeFilter'] = array( $path, 'between', array( $value['min'], $value['max'] ) );
							} elseif( is_numeric( $value['min'] ) ) {
								$return['attributeFilter'] = array( $path, '>=', $value['min'] );
							} elseif( is_numeric( $value['max'] ) ) {
								$return['attributeFilter'] = array( $path, '<=', $value['max'] );
							}
						}
						break;
					}
					case self::FILTER_TYPE_DATE: {
						if( is_array( $value ) ) {
							if( is_numeric( $value['start'] ) && is_numeric( $value['end'] ) ) {
								$return['attributeFilter'] = array( $path, 'between', array( $value['start'] - 1, $value['end'] + 24 * 60 * 60 - 1 ) );
							} elseif( is_numeric( $value['start'] ) ) {
								$return['attributeFilter'] = array( $path, '>=', $value['start'] );
							} elseif( is_numeric( $value['end'] ) ) {
								$return['attributeFilter'] = array( $path, '<=', $value['end'] + 24 * 60 * 60 - 1 );
							}
						}
						break;
					}
					case self::FILTER_TYPE_BOOL: {
						$return['attributeFilter'] = array( $path, '=', 1 );
						break;
					}
					case self::FILTER_TYPE_SELECTION: {
						if( $value != -1 && $value != '' ) {
							if( $this->attribute( 'filter_parent_node' ) === true ) {
								$return['parentNodeID'] = $value;
							} else {
								$return['attributeFilter'] = array( $path, '=', $value );
							}
						}
						break;
					}
					case self::FILTER_TYPE_USER_ACCOUNT: {
						$values = array();
						if( strlen( $value['login'] ) > 0 ) {
							$values['login'] = $value['login'];
						}
						if( strlen(  $value['email'] ) > 0 ) {
							$values['email'] = $value['email'];
						}
						if( isset( $value['enabled'] ) ) {
							$values['enabled'] = $value['enabled'];
						}

						if( count( $values ) > 0 ) {
							$return['extenedAttributeFilter'] = array(
								'callback' => array(
									'method_name' => 'userAccount'
								),
								'params' => $values
							);
						}
						break;
					}
					case self::FILTER_TYPE_EMAIL: {
						if( strlen( $value ) > 0 ) {
							$return['attributeFilter'] = array( $path, 'like', '*' . $value . '*' );
						}
						break;
					}
					case self::FILTER_TYPE_RELATION_LIST: {
						if( $value != -1 && $value != '' ) {
							$return['extenedAttributeFilter'] = array(
								'callback' => array(
									'method_name' => 'relatedObjectList'
								),
								'params' => array(
									'attribute'  => $this->attribute( 'filter_attr_prefix' ) . $this->attribute( 'identifier' ),
									'object_ids' => $value
								)
							);
						}
						break;
					}
					case self::FILTER_TYPE_BIRTHDAY: {
						if( is_array( $value ) ) {
							$params = array();
							if( is_numeric( $value['start'] ) ) {
								$params['start_timestamp'] = $value['start'];
							}
							if( is_numeric( $value['end'] ) ) {
								$params['end_timestamp'] = $value['end'] + 24 * 60 * 60 - 1;
							}

							$return['extenedAttributeFilter'] = array(
								'callback' => array(
									'method_name' => 'birthday'
								),
								'params' => $params
							);
						}
						break;
					}
					case self::FILTER_TYPE_FETCH_PARAMS_CALLBACK: {
						$callback = $this->attribute( 'filter_fetch_params_callback' );
						if(
							is_array( $callback )
							&& isset( $callback['callback'] )
							&& is_callable( $callback['callback'] )
						) {
							$params = array( $value );
							if(
								isset( $callback['params'] )
								&& is_array( $callback['params'] )
							) {
								$params = array_merge( $params, $callback['params'] );
							}

							$return = call_user_func_array( $callback['callback'], $params );
						}
					}
				}
			} else {
				$return['callback'] = $this->attribute( 'filter_callback' );
				$return['params']   = array( 'value' => $value );
			}
		}

		return $return;
	}

	public function getViewContent( eZContentObjectTreeNode $node ) {
		$http     = eZHTTPTool::instance();
		$template = 'view/' . $this->attribute( 'datalist_view_template' );

		if(
			(bool) $http->sessionVariable( 'nxc_data_list_edit_mode' ) === true
			&& $this->attribute( 'datalist_edit_template' ) !== null
		) {
			$template = 'edit/' . $this->attribute( 'datalist_edit_template' );
		}

		$dataMap = $node->attribute( 'data_map' );

		include_once( 'kernel/common/template.php' );
		$tpl = templateInit();
		$tpl->setVariable( 'data_list_attribute', $this );
		$tpl->setVariable( 'node', $node );
		if( isset( $dataMap[ $this->attribute( 'identifier' ) ] ) ) {
			$tpl->setVariable( 'attribute', $dataMap[ $this->attribute( 'identifier' ) ] );
		}
		return $tpl->fetch( 'design:datalist/' . $template . '.tpl' );
	}

	public static function transformFilterType( $classAttribute ) {
		switch( $classAttribute->attribute( 'data_type_string' ) ) {
			case 'ezstring':
				return self::FILTER_TYPE_STRING;
			case 'ezinteger':
				return self::FILTER_TYPE_NUMERIC;
			case 'ezboolean':
				return self::FILTER_TYPE_BOOL;
			case 'ezdate':
			case 'ezdatetime':
			case 'eztime':
				return self::FILTER_TYPE_DATE;
			case 'ezcountry':
			case 'ezobjectrelation':
			case 'ezselection':
				return self::FILTER_TYPE_SELECTION;
			case 'ezemail':
				return self::FILTER_TYPE_EMAIL;
			case 'ezuser':
				return self::FILTER_TYPE_USER_ACCOUNT;
			case 'ezobjectrelationlist':
				return self::FILTER_TYPE_RELATION_LIST;
			case 'ezbirthday':
				return self::FILTER_TYPE_BIRTHDAY;
			case 'ezimage':
				return self::FILTER_TYPE_IMAGE;
			default:
				return false;
		}
	}

	public function getExportHeaders() {
		return ( count( $this->attribute( 'export_headers' ) ) === 0 )
			? array( $this->attribute( 'name' ) )
			: $this->attribute( 'export_headers' );
	}
}
?>