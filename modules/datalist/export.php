<?php
/**
 * @package nxcDataList
 * @author  Serhey Dolgushev <serhey.dolgushev@nxc.no>
 * @date    23 Apr 2010
 **/
set_time_limit( 3600 );

$class            = eZContentClass::fetch( $Params['classID'] );
$dataListSettings = nxcDataListSettings::fetch( $class );

$filename = isset( $Params['filename'] ) && ( $Params['filename'] !== '' ) ? $Params['filename'] : md5( microtime( true ) ) . '.cache';
$offset   = isset( $Params['offset'] ) && ( $Params['offset'] !== '' ) ? $Params['offset'] : 0;
$limit    = isset( $Params['limit'] ) && ( $Params['limit'] !== '' ) ? $Params['limit'] : 1000;
$filepath = 'var/storage/nxc_export/' . $filename;

$http   = eZHTTPTool::instance();
$currentAdditionalAttrGroups = array();
if( $http->hasSessionVariable( 'datalistAdditionalAttrGroups' ) && is_array( $http->sessionVariable( 'datalistAdditionalAttrGroups' ) ) ) {
	$currentAdditionalAttrGroups = $http->sessionVariable( 'datalistAdditionalAttrGroups' );
}
$additionalAttributeGroup = ( isset( $currentAdditionalAttrGroups[ $class->attribute( 'id' ) ] ) ) ? $currentAdditionalAttrGroups[ $class->attribute( 'id' ) ] : -1;

$viewableAttributes = $dataListSettings->attribute( 'all_attributes' );

$allNodesCount = nxcDataListOperations::fetchNodesCount( $dataListSettings );
$nodes         = nxcDataListOperations::fetchNodes( $dataListSettings, $offset, $limit );

$ini = eZINI::instance( 'nxcexport.ini' );

$datatypeHandlers       = $ini->variable( 'General', 'DatatypeHandlers' );
$classAttributeHandlers = $ini->variable( 'General', 'ClassAttributeHandlers' );
$availableDatatypes     = $ini->variable( 'General', 'AvailableDatatypes' );

$allRows = array();
if( file_exists( $filepath ) ) {
	$allRows = unserialize( @file_get_contents( $filepath ) );
}

foreach( $nodes as $node ) {
	$dataMap   = $node->attribute( 'data_map' );
	$exportRow = array();

	foreach( $viewableAttributes as $attributeIdentifier => $dataListAttribute ) {
		if( isset( $dataMap[ $attributeIdentifier ] ) ) {
			$attribute = $dataMap[ $attributeIdentifier ];

			if( in_array( $attribute->attribute( 'data_type_string' ), $availableDatatypes ) ) {
				if( isset( $converters[ $attributeIdentifier ] ) === false ) {
					$converterClass = 'nxcExportAttributeConverter';
					if( isset( $classAttributeHandlers[ $class->attribute( 'identifier' ) . '/' . $attributeIdentifier ] ) ) {
						$converterClass = $classAttributeHandlers[ $class->attribute( 'identifier' ) . '/' . $attributeIdentifier ];
					} elseif( isset( $datatypeHandlers[ $attribute->attribute( 'data_type_string' ) ] ) ) {
						$converterClass = $datatypeHandlers[ $attribute->attribute( 'data_type_string' ) ];
					}

					$converters[ $attributeIdentifier ] = $converterClass;
				}

				$attributeData = call_user_func(
					array(
						$converters[ $attributeIdentifier ],
						'export'
					),
					$dataMap[ $attributeIdentifier ]
				);
			}
		} elseif( $attributeIdentifier == 'published' ) {
			$attributeData = date( 'd M Y H:i:s', $node->attribute( 'object' )->attribute( 'published' ) );
		} elseif( $attributeIdentifier == 'object_id' ) {
			$attributeData = $node->attribute( 'contentobject_id' );
		} else {
			$attributeData = trim( strip_tags( $dataListAttribute->getViewContent( $node ) ) );
		}

		if( is_array( $attributeData ) ) {
			foreach( $attributeData as $key => $value ) {
				$exportRow[ $attributeIdentifier . '_' . $key ] = $value;
			}
		} else {
			$exportRow[ $attributeIdentifier ] = $attributeData;
		}
	}

	foreach( $exportRow as $key => $data ) {
		$exportRow[ $key ] = trim( $data );
	}

	$allRows[] = $exportRow;

	$object = $node->attribute( 'object' );
	$object->resetDataMap();
	eZContentObject::clearCache( array( $object->attribute( 'id' ) ) );
	unset( $dataMap );
	unset( $object );
	unset( $node );
}

if( $allNodesCount > $offset + $limit ) {
	$fh = fopen( $filepath, 'w' );
	fwrite( $fh, serialize( $allRows ) );
	fclose( $fh );

	$Params['Module']->redirectTo(
		'/datalist/export/' . $class->attribute( 'id' ) . '/' . $filename . '/' . ( $offset + $limit ) . '/' . $limit
	);
} else {
	@unlink( $filepath );

	$archiveFilename = 'var/storage/nxc_export/datalist_export.zip';
	$archive = ezcArchive::open( $archiveFilename, ezcArchive::ZIP );
	$archive->truncate();
	$files  = array();

	$portion = 2000;
	$maxi    = count( $allRows ) / $portion;
	for( $k = 0; $k < $maxi; $k++ ) {
		$rows = array_slice( $allRows, $k * $portion, $portion );

		$exportFilepath = 'var/storage/nxc_export/datalist_export-' . $k . '.xls';
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->getProperties()->setCreator( 'eZ Publish' );
		$objPHPExcel->setActiveSheetIndex( 0 );
		$objPHPExcelSheet = $objPHPExcel->getActiveSheet();
		$objPHPExcelSheet->setTitle( $class->attribute( 'name' ) );

		$dataIndex = 1;
		foreach( $viewableAttributes as $viewableAttribute ) {
			$headers = $viewableAttribute->getExportHeaders();
			foreach( $headers as $header ) {
				$objPHPExcelSheet->getCellByColumnAndRow( $dataIndex, 1 )->setValueExplicit( trim( $header ), PHPExcel_Cell_DataType::TYPE_STRING );
				$dataIndex++;
			}
		}

		$i = 2;
		foreach( $rows as $exportRow ) {
			$dataIndex = 1;
			foreach( $exportRow as $data ) {
				if( $data === null ) {
					$data = ' ';
				}
				$objPHPExcelSheet->getCellByColumnAndRow( $dataIndex, $i )->setValueExplicit( trim( $data ), PHPExcel_Cell_DataType::TYPE_STRING );
				$dataIndex++;
			}
			$i++;
		}

		$writer = new PHPExcel_Writer_Excel5( $objPHPExcel );
		$writer->save( $exportFilepath );
		$files[] = $exportFilepath;
	}

	$archive->appendToCurrent( $files, 'var/storage/nxc_export' );
	$content = file_get_contents( $archiveFilename );
	@unlink( $archiveFilename );
	foreach( $files as $file ) {
		@unlink( $file );
	}

	header( 'Content-Disposition: attachment; filename=datalist_export.zip' );
	header( 'Content-Type: application/force-download' );
	header( 'Content-Description: File Transfer');
	header( 'Content-Length: ' . strlen( $content ) );
	echo $content;
	eZExecution::cleanExit();
}
?>
