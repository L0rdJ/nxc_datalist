<?php
/**
 * @package nxcDataList
 * @class   nxcDataListSettings
 * @author  Serhey Dolgushev <serhey.dolgushev@nxc.no>
 * @date    25 Mar 2010
 **/

abstract class nxcDataListSettings extends nxcDataListTemplateObject {

	protected $attributes = array(
		'content_object_class'        => null,
		'title'                       => null,
		'attribute_groups'            => array(),
		'main_attribute_group'        => null,
		'multiple_actions'            => array(),
		'additional_action_templates' => array(),
		'all_attributes'              => array(),
		'parent_nodes'                => array()
	);

	public function __construct( eZContentClass $class ) {
		$this->setAttribute( 'content_object_class', $class );
		$this->setAttribute( 'title', $this->getTitle() );
		$this->setAttribute( 'attribute_groups', $this->getGroups() );
		$this->setAttribute( 'main_attribute_group', $this->getMainAttributeGroup() );
		$this->setAttribute(
			'multiple_actions',
			array_merge(
				array(
					'/datalist/update' => 'Update',
					'/datalist/remove' => 'Remove'
				),
				$this->getMultipleActions()
			)
		);
		$this->setAttribute(
			'additional_action_templates',
			array_merge(
				array( 'create_object' ),
				$this->getAdditionalActionTemplates()
			)
		); 

		$allAttributes     = array();
		$groupedAttributes = $this->getGroupedAttributes();
		foreach( $groupedAttributes as $groupIdentifier => $attributeIdentifiers ) {
			foreach( $attributeIdentifiers as $attributeIdentifier ) {
				$allAttributes[ $attributeIdentifier ] = $attributeIdentifier;
			}
		} 

		$classDataMap = $this->attribute( 'content_object_class' )->attribute( 'data_map' );
		foreach( $classDataMap as $identifier => $classAttribute ) {
			if( isset( $allAttributes[ $identifier ] ) ) {
				$dataListAttribute = new nxcDataListAttribute(
					$identifier,
					$classAttribute->attribute( 'name' )
				);
				$dataType = $classAttribute->attribute( 'data_type_string' );

				$dataListAttribute->setAttribute( 'datalist_view_template', $dataType );

				switch( $dataType ) {
					case 'ezstring':
					case 'eztext':
					case 'ezinteger':
					case 'ezfloat':
					case 'ezboolean':
					case 'ezdate':
					case 'ezdatetime':
					case 'eztime':
					case 'ezcountry':
					case 'ezselection':
					case 'ezemail':
					case 'ezurl':
					#case 'ezuser':
					case 'ezbirthday': {
						$dataListAttribute->setAttribute( 'datalist_edit_template', $dataType );
						break;
					}
				}

				$filterType = nxcDataListAttribute::transformFilterType( $classAttribute );
				if( $filterType !== false ) {
					$dataListAttribute
						->setAttribute( 'filterable', true )
						->setAttribute( 'filter_type', $filterType )
						->setAttribute( 'filter_attr_prefix', $this->attribute( 'content_object_class' )->attribute( 'identifier' ) . '/' );


					$selectionOptions = array();
					switch( $dataType ) {
						case 'ezcountry': {
							$trans    = eZCharTransform::instance();
							$settings = $classAttribute->attribute( 'content' );
							if( (bool) $settings['multiple_choice'] === false ) {
								$coutries         = eZCountryType::fetchCountryList();
								foreach( $coutries as $country ) {
									$selectionOptions[ $trans->transformByGroup( $country['Name'], 'lowercase' ) ] = $country['Name'];
								}
							}
							$dataListAttribute->setAttribute( 'filter_view_params', array( 'selection_options' => $selectionOptions ) );
							break;
						}
						case 'ezselection': {
							$settings = $classAttribute->attribute( 'content' );
							if( (bool) $settings['is_multiselect'] === false ) {
								$options = $settings['options'];
								foreach( $options as $option ) {
									$selectionOptions[ $option['id'] ] = $option['name'];
								}
							}
							$dataListAttribute->setAttribute( 'filter_view_params', array( 'selection_options' => $selectionOptions  ) );
							break;
						}
					}
 				}

				$allAttributes[ $identifier ] = $dataListAttribute;
			}
		}

		$this->setAttribute( 'all_attributes', $allAttributes );
		$this->setupAttributes();

		$allAttributes = $this->attribute( 'all_attributes' );
		$groups        = $this->attribute( 'attribute_groups' );
		foreach( $groupedAttributes as $groupIdentifier => $attributeIdentifiers ) {
			foreach( $attributeIdentifiers as $attributeIdentifier ) {
				if( $allAttributes[ $attributeIdentifier ] instanceof nxcDataListAttribute ) {
					$groups[ $groupIdentifier ]->addDataListAttribute(
						$allAttributes[ $attributeIdentifier ]
					);
				} else {
					eZDebug::writeError( 'Can`t fetch attribute "' . $attributeIdentifier . '"', 'NXC Datalist' );
				}
			}
		}

		$this->setAttribute( 'parent_nodes', $this->getParentNodes() );
	}

	final public static function fetch( eZContentClass $class ) { 
		$ini = eZINI::instance( 'nxcdatalist.ini' );
		$availableClasses  = $ini->variable( 'Datalist', 'AvailableClass' ); 
		$settingsClassName = $availableClasses[ $class->attribute( 'identifier' ) ];
		return new $settingsClassName( $class );
	}


	final public function getViewableAttributes( $additionalGroup = -1 ) {
		$attributeGroups    = $this->attribute( 'attribute_groups' );
		$viewableAttributes = $attributeGroups[ $this->attribute( 'main_attribute_group' ) ]->attribute( 'data_list_attributes' );
		if( $additionalGroup != -1 ) {
			$viewableAttributes = array_merge(
				$viewableAttributes,
				$attributeGroups[ $additionalGroup ]->attribute( 'data_list_attributes' )
			);
		}

		foreach( $viewableAttributes as $key => $attribute ) {
			if( $attribute->attribute( 'datalist_view_template' ) === null ) {
				unset( $viewableAttributes[ $key ] );
			}
		}

		return $viewableAttributes;
	}

	public function getTitle() {
		return $this->attribute( 'content_object_class' )->attribute( 'name' );
	}

	public function getGroups() {
		return array();
	}

	public function getMultipleActions() {
		return array();
	}

	public function getAdditionalActionTemplates() {
		return array();
	}

	public function getMainAttributeGroup() {
		return 'essential_data';
	}

	public function getGroupedAttributes() {
		return array();
	}

	public function getParentNodes() {
		return array( eZContentObjectTreeNode::fetch( 1 ) );
	}

	public function setupAttributes() {
	}

	public function getNavigationPath() {
	}

	public function getLeftMenu() {
	}

	public function getPath() {
	}
}
?>