{def $checked = false()}
{if is_set( $current_filter.filter_values[$data_list_attribute.identifier] )}
{set $checked = true()}
{/if}
<input type="checkbox" value="1" name="datalist_filter[{$data_list_attribute.identifier}]" id="datalist-filter-{$data_list_attribute.identifier}" {if $checked}checked="checked"{/if}/>
{undef $checked}