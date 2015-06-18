<?php
/**
 * @package nxcDataList
 * @author  Serhey Dolgushev <serhey.dolgushev@nxc.no>
 * @date    25 Mar 2010
 **/

$eZTemplateOperatorArray = array();
$eZTemplateOperatorArray[] = array(
	'script'         => 'extension/nxc_datalist/autoloads/nxcdatalisttemplatefunctions.php',
    'class'          => 'nxcDataListTemplateFunctions',
	'operator_names' => array( 'datalist_view' )
);
?>