<script type="text/javascript">
{literal}
window.addEvent( 'domready', function() {
	var startDateValue  = document.id( '{/literal}datalist-filter-{$data_list_attribute.identifier}-start{literal}' );
	new DatePicker( startDateValue, {
		pickerClass: 'datepicker_vista',
		allowEmpty: true,
		format: 'd\.m\.Y',
		toggleElements: document.id( '{/literal}datalist-filter-{$data_list_attribute.identifier}-start-toggler{literal}' )
	} );
	document.id( '{/literal}datalist-filter-{$data_list_attribute.identifier}-start-empty{literal}' ).addEvent( 'click', function( e ) {
		e.stop();
		startDateValue.set( 'value', '' ).getNext( 'input' ).set( 'value', '' );
	} );

	var endDateValue = document.id( '{/literal}datalist-filter-{$data_list_attribute.identifier}-end{literal}' );
	new DatePicker( endDateValue, {
		pickerClass: 'datepicker_vista',
		allowEmpty: true,
		format: 'd\.m\.Y',
		toggleElements: document.id( '{/literal}datalist-filter-{$data_list_attribute.identifier}-end-toggler{literal}' )
	} );
	document.id( '{/literal}datalist-filter-{$data_list_attribute.identifier}-end-empty{literal}' ).addEvent( 'click', function( e ) {
		e.stop();
		endDateValue.set( 'value', '' ).getNext( 'input' ).set( 'value', '' );
	} );

	window.messageStack = ( $type( window.messageStack ) === false ) ? new NXC.MessageStack() : window.messageStack;
	var errorMessage = '{/literal}{'%attribute: end date should be later than start date'|i18n( 'extension/datalist', , hash( '%attribute', $data_list_attribute.name ) )}{literal}';
	document.id( 'datalist-filter-form' ).addEvent( 'submit', function( e ) {
		if(
			( startDateValue.get( 'value' ) > 0 ) &&
			( endDateValue.get( 'value' ) > 0 ) &&
			( endDateValue.get( 'value' ) < startDateValue.get( 'value' ) )
		) {
			window.messageStack.showMessage( errorMessage, 'error' );
			e.stop();
		}
	} );
} );
{/literal}
</script>
{def $filter_attribute_values = $current_filter.filter_values[$data_list_attribute.identifier]}
from <input class="datalist-filter-view-date" readonly="readonly" type="text" value="{$filter_attribute_values['start']}" name="datalist_filter[{$data_list_attribute.identifier}][start]" id="datalist-filter-{$data_list_attribute.identifier}-start" /> <img alt="{'Pop-up calendar'|i18n( 'extension/datalist' )}" title="{'Pop-up calendar'|i18n( 'extension/datalist' )}" src="{'datepicker/calendar.gif'|ezimage( 'no' )}" id="datalist-filter-{$data_list_attribute.identifier}-start-toggler" class="datepicker-toggler" /> <img alt="{'Clear date'|i18n( 'extension/datalist' )}" title="{'Clear date'|i18n( 'extension/datalist' )}" src="{'datepicker/empty.png'|ezimage( 'no' )}" id="datalist-filter-{$data_list_attribute.identifier}-start-empty" class="datepicker-empty" /> to <input class="datalist-filter-view-date" readonly="readonly" type="text" value="{$filter_attribute_values['end']}" name="datalist_filter[{$data_list_attribute.identifier}][end]" id="datalist-filter-{$data_list_attribute.identifier}-end" /> <img alt="{'Pop-up calendar'|i18n( 'extension/datalist' )}" title="{'Pop-up calendar'|i18n( 'extension/datalist' )}" src="{'datepicker/calendar.gif'|ezimage( 'no' )}" id="datalist-filter-{$data_list_attribute.identifier}-end-toggler" class="datepicker-toggler" /> <img alt="{'Clear date'|i18n( 'extension/datalist' )}" title="{'Clear date'|i18n( 'extension/datalist' )}" src="{'datepicker/empty.png'|ezimage( 'no' )}" id="datalist-filter-{$data_list_attribute.identifier}-end-empty" class="datepicker-empty" />
{undef $filter_attribute_values}