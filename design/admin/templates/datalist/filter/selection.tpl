<select name="datalist_filter[{$data_list_attribute.identifier}]" id="datalist-filter-{$data_list_attribute.identifier}" >
	<option value="-1">{'- Any -'|i18n( 'extension/datalist' )}</option>
	{foreach $data_list_attribute.filter_view_params.selection_options as $value => $title}
	<option value="{$value}" {if and( ne( $current_filter.filter_values[$data_list_attribute.identifier], '' ), eq( $current_filter.filter_values[$data_list_attribute.identifier], $value ) )}selected="selected"{/if}>{$title}</option>
	{/foreach}
</select>
