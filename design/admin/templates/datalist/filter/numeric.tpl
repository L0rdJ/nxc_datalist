{def $filter_attribute_values = $current_filter.filter_values[$data_list_attribute.identifier]}
from <input class="datalist-filter-view-numeric" type="text" value="{$filter_attribute_values['min']}" name="datalist_filter[{$data_list_attribute.identifier}][min]" id="datalist-filter-{$data_list_attribute.identifier}-min" /> to <input class="datalist-filter-view-numeric" type="text" value="{$filter_attribute_values['max']}" name="datalist_filter[{$data_list_attribute.identifier}][max]" id="datalist-filter-{$data_list_attribute.identifier}-max" />
{undef $filter_attribute_values}

{literal}
<script type="text/javascript">
	window.addEvent( 'domready', function() {
		window.messageStack = ( $type( window.messageStack ) === false ) ? new NXC.MessageStack() : window.messageStack;
		var minErrorMessage = '{/literal}{'%attribute: can be only a number'|i18n( 'extension/datalist', , hash( '%attribute', concat( $data_list_attribute.name, ' (from)' ) ) )}{literal}';
		var maxErrorMessage = '{/literal}{'%attribute: can be only a number'|i18n( 'extension/datalist', , hash( '%attribute', concat( $data_list_attribute.name, ' (to)' ) ) )}{literal}';

		var minInput = document.id( 'datalist-filter-{/literal}{$data_list_attribute.identifier}-min{literal}' );
		var maxInput = document.id( 'datalist-filter-{/literal}{$data_list_attribute.identifier}-max{literal}' );

		document.id( 'datalist-filter-form' ).addEvent( 'submit', function( e ) {
			var value = minInput.get( 'value' );
			if( value != '' && isNaN( value ) ) {
				window.messageStack.showMessage( minErrorMessage, 'error' );
				e.stop();
			}

			var value = maxInput.get( 'value' );
			if( value != '' && isNaN( value ) ) {
				window.messageStack.showMessage( maxErrorMessage, 'error' );
				e.stop();
			}
		} );

	} );
</script>
{/literal}