<input type="text" class="datalist-filter-view-email" value="{$current_filter.filter_values[$data_list_attribute.identifier]}" name="datalist_filter[{$data_list_attribute.identifier}]" id="datalist-filter-{$data_list_attribute.identifier}" />
{*
{literal}
<script type="text/javascript">
	window.addEvent( 'domready', function() {
		window.messageStack = ( $type( window.messageStack ) === false ) ? new NXC.MessageStack() : window.messageStack;
		var errorMessage = '{/literal}{'%attribute: a valid e-mail address is required'|i18n( 'extension/datalist', , hash( '%attribute', $data_list_attribute.name ) )}{literal}';
		var emailRegExp  = /^[^@]+@[^@]+.[a-z]{2,}$/i;
		var input        = document.id( 'datalist-filter-{/literal}{$data_list_attribute.identifier}{literal}' );

		document.id( 'datalist-filter-form' ).addEvent( 'submit', function( e ) {
			var value = input.get( 'value' ).replace( /(^\s+)|(\s+$)/g, '' );
			if( value != '' ) {
				if( value.search( emailRegExp ) == -1 ) {
					window.messageStack.showMessage( errorMessage, 'error' );
					e.stop();
				}
			}
		} );

	} );
</script>
{/literal}
*}