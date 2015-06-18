{def $selected_object = fetch( 'content', 'object', hash( 'object_id', $current_filter.filter_values[$data_list_attribute.identifier] ) )}
<input class="datalist-filter-view-string" type="text" value="{if $selected_object}{$selected_object.name}{/if}" id="datalist-filter-{$data_list_attribute.identifier}" />
<input type="hidden" value="{if $selected_object}{$selected_object.id}{/if}" name="datalist_filter[{$data_list_attribute.identifier}]" id="value-datalist-filter-{$data_list_attribute.identifier}" />
{undef $selected_object}
<span style="margin-bottom: 10px; display: block;">(start typing the text)</span>

{literal}
<script type="text/javascript">
window.addEvent( 'domready', function() {
	var inputElement = document.id( 'datalist-filter-{/literal}{$data_list_attribute.identifier}{literal}' );
	var valueElement = document.id( 'value-datalist-filter-{/literal}{$data_list_attribute.identifier}{literal}' );

	var instance     = new Meio.Autocomplete.Select(
		inputElement,
		'{/literal}{concat( '/datalist/ajax_search_nodes/', $data_list_attribute.filter_view_params.class, '/', $data_list_attribute.filter_view_params.parentNodeID )|ezurl( 'no' )}{literal}',
		{
			delay: 1, 
			maxVisibleItems: 0,
			cacheLength: 0, 
			minChars: 1,
			selectOnTab: false,
			filter: {
				filter: function( text, data ) {
					return true;
				},
				path: 'value'
			},
			/*
			filter: {
				type: function( text, data ) {
					return true;
				},
				path: 'value'
			},
			*/
			listOptions: {
				width: '400px'
			},
			valueField: valueElement,
			valueFilter: function( data ){
				return data.id;
			}
		}
	);
} );
</script>
{/literal}