<?php
/**
 * @package nxcDataList
 * @author  Serhey Dolgushev <serhey.dolgushev@nxc.no>
 * @date    25 Mar 2010
 **/

class nxc_datalistSettings extends nxcExtensionSettings {

	public $defaultOrder = 10;
	public $dependencies = array( 'nxc_mootools', 'nxc_extendedfilter', 'nxc_powercontent', 'nxc_export' );

	public function activate() {}

	public function deactivate() {}
}
?>