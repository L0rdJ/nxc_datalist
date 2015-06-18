<?php
/**
 * @package nxcDataList
 * @author  Serhey Dolgushev <serhey.dolgushev@nxc.no>
 * @date    24 Mar 2010
 **/

$Module = array(
	'name'            => 'NXC Data list',
 	'variable_params' => true
);

$ViewList = array();
$ViewList['list'] = array(
	'functions'        => array( 'datalist' ),
	'script'           => 'list.php',
	'params'           => array( 'class' )
);
$ViewList['export'] = array(
	'functions'        => array( 'datalist' ),
	'script'           => 'export.php',
	'params'           => array( 'classID', 'filename', 'offset', 'limit' )
);
$ViewList['set_filter'] = array(
	'functions'        => array( 'datalist' ),
	'script'           => 'set_filter.php',
	'params'           => array( 'filterID' ),
	'unordered_params' => array( 'class_id' => 'classID' )
);
$ViewList['action'] = array(
	'functions'        => array( 'datalist' ),
	'script'           => 'action.php',
	'params'           => array()
);
$ViewList['remove'] = array(
	'functions'        => array( 'datalist' ),
	'script'           => 'remove.php',
	'params'           => array()
);
$ViewList['update'] = array(
	'functions'        => array( 'datalist' ),
	'script'           => 'update.php',
	'params'           => array()
);
$ViewList['ajax_toggle_bool_attribute'] = array(
	'functions'        => array( 'datalist' ),
	'script'           => 'ajax/toggle_bool_attribute.php',
	'params'           => array( 'nodeID', 'attribute' )
);
$ViewList['ajax_filter_delete'] = array(
	'functions'        => array( 'datalist' ),
	'script'           => 'ajax/filter/delete.php',
	'params'           => array( 'filterID' )
);
$ViewList['ajax_filter_edit'] = array(
	'functions'        => array( 'datalist' ),
	'script'           => 'ajax/filter/edit.php',
	'params'           => array( 'filterID', 'name' )
);
$ViewList['ajax_get_nodes'] = array(
	'functions'        => array( 'datalist' ),
	'script'           => 'ajax/get_nodes.php',
	'params'           => array( 'classID', 'offset', 'limit' )
);
$ViewList['ajax_search_nodes'] = array(
	'functions'        => array( 'datalist' ),
	'script'           => 'ajax/search_nodes.php',
	'params'           => array( 'class', 'parentNodeID' )
);
$ViewList['ajax_toggle_edit_mode'] = array(
	'functions'        => array( 'datalist' ),
	'script'           => 'ajax/toggle_edit_mode.php',
	'params'           => array( 'classID' )
);

$FunctionList             = array();
$FunctionList['datalist'] = array();
?>