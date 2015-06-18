<!-- my filters -->
<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">
	<h1 class="context-title">{'My filters'|i18n( 'extension/datalist' )}</h1>
	<div class="header-mainline"></div>
</div></div></div></div></div></div>

<div class="box-ml"><div class="box-mr"><div class="box-content">
	<div class="context-toolbar"><div class="break"></div></div>

	<table id="datalist-filter-list-table">
		<tbody>
			{foreach $all_filters as $filter}
			<tr id="datalist-filter-list-{$filter.id}">
				<td class="datalist-filter-active">
					{if or(
						and( eq( $current_filter.id, 1 ), eq( $filter.id, $current_filter.id ), eq( count( $current_filter.filter_values ), 0 ) ),
						and( ne( $current_filter.id, 1 ), eq( $filter.id, $current_filter.id ) )
					)}
					<img alt="" src="{'icons/tick.gif'|ezimage( 'no' )}" />
					{else}
					&nbsp;
					{/if}
				</td>
				<td class="datalist-filter-name">
					<a href="{if eq( $filter.id, 1 )}{concat( '/datalist/set_filter/', $filter.id, '/class_id/', $data_list_settings.content_object_class.id )|ezurl( 'no' )}{else}{concat( '/datalist/set_filter/', $filter.id )|ezurl( 'no' )}{/if}/">{$filter.name}</a>
				</td>
				<td class="datalist-filter-actions">
					{if ne( $filter.id, 1 )}
					<img alt="{'Edit filter'|i18n( 'extension/datalist' )}" src="{'icons/edit.gif'|ezimage( 'no' )}" class="datalist-filter-action-edit" />
					<img alt="{'Delete filter'|i18n( 'extension/datalist' )}" src="{'icons/delete.gif'|ezimage( 'no' )}" class="datalist-filter-action-delete" />
					<img style="display:none;" alt="{'Save filter'|i18n( 'extension/datalist' )}" src="{'icons/save.gif'|ezimage( 'no' )}" class="datalist-filter-action-save" />
					{else}
					&nbsp;
					{/if}
				</td>
			</tr>
			{/foreach}
		</tbody>
	</table>

	<script type="text/javascript">
	{literal}
	window.addEvent( 'domready', function() {
		window.messageStack = ( $type( window.messageStack ) === false ) ? new NXC.MessageStack() : window.messageStack;

		var AJAXBaseURL = '{/literal}{'/datalist'|ezurl( 'no' )}{literal}/';
		var filterListTable = document.id( 'datalist-filter-list-table' );
		var editIcons   = filterListTable.getElements( 'img.datalist-filter-action-edit' );
		var deleteIcons = filterListTable.getElements( 'img.datalist-filter-action-delete' );
		var saveIcons   = filterListTable.getElements( 'img.datalist-filter-action-save' );

		editIcons.each( function( icon, index ) {
			icon.addEvent( 'click', function( e ) {
				icon.getParent().getParent().getElement( 'td.datalist-filter-name a' ).set( 'contentEditable', true ).addClass( 'editable' ).fireEvent( 'focus' );
				editIcons[index].setStyle( 'display', 'none' );
				deleteIcons[index].setStyle( 'display', 'none' );
				saveIcons[index].setStyle( 'display', 'inline' );
			} );
		} );
		deleteIcons.each( function( icon ) {
			icon.addEvent( 'click', function( e ) {
				var filterID = icon.getParent().getParent().get( 'id' ).replace( 'datalist-filter-list-', '' );

				new Request.JSON( {
					'url'      : AJAXBaseURL + 'ajax_filter_delete/' + filterID,
					'method'   : 'post',
					'onSuccess': function( response ) {
						if( response.status.toInt() === 1 ) {
							window.messageStack.showMessage( response.message, 'notice' );
							icon.getParent().getParent().destroy();
						} else {
							window.messageStack.showMessage( response.errors.filter, 'error' );
						}
					}
				} ).send();
			} );
		} );
		saveIcons.each( function( icon, index ) {
			icon.addEvent( 'click', function( e ) {
				var name     = icon.getParent().getParent().getElement( 'td.datalist-filter-name a' );
				var filterID = icon.getParent().getParent().get( 'id' ).replace( 'datalist-filter-list-', '' );
				new Request.JSON( {
					'url'      : AJAXBaseURL + 'ajax_filter_edit/' + filterID + '/' + encodeURIComponent( name.get( 'text' ) ),
					'method'   : 'post',
					'onSuccess': function( response ) {
						if( response.status.toInt() === 1 ) {
							window.messageStack.showMessage( response.message, 'notice' );

							editIcons[index].setStyle( 'display', 'inline' );
							deleteIcons[index].setStyle( 'display', 'inline' );
							saveIcons[index].setStyle( 'display', 'none' );

							name.set( 'contentEditable', false ).removeClass( 'editable' );
						} else {
							window.messageStack.showMessage( response.errors.filter, 'error' );
						}
					}
				} ).send();
			} );
		} );
	} );
	{/literal}
	</script>

	<div class="context-toolbar"><div class="break"></div></div>
</div></div></div>

<div class="controlbar">
	<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">
		<div class="block"></div>
	</div></div></div></div></div></div>
</div>
<!-- end my filters -->