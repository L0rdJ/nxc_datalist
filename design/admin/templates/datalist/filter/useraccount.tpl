<div class="datalist-filter-view-user">
	<div class="datalist-filter-view-user-title">login:</div>
	<div class="datalist-filter-view-user-value">
		<input type="text" class="datalist-filter-view-user-login" value="{$current_filter.filter_values[$data_list_attribute.identifier][login]}" name="datalist_filter[{$data_list_attribute.identifier}][login]" id="datalist-filter-{$data_list_attribute.identifier}-login" />
	</div>
	<div class="break"></div>
</div>

<div class="datalist-filter-view-user">
	<div class="datalist-filter-view-user-title">email:</div>
	<div class="datalist-filter-view-user-value">
		<input type="text" class="datalist-filter-view-user-email" value="{$current_filter.filter_values[$data_list_attribute.identifier][email]}" name="datalist_filter[{$data_list_attribute.identifier}][email]" id="datalist-filter-{$data_list_attribute.identifier}-email" />
	</div>
	<div class="break"></div>
</div>

{def $checked = false()}
{if is_set( $current_filter.filter_values[$data_list_attribute.identifier][enabled] )}
{set $checked = true()}
{/if}
<div class="datalist-filter-view-user">
	<div class="datalist-filter-view-user-title">enabled:</div>
	<div class="datalist-filter-view-user-value">
		<input type="checkbox" value="1" name="datalist_filter[{$data_list_attribute.identifier}][enabled]" id="datalist-filter-{$data_list_attribute.identifier}-enabled" {if $checked}checked="checked"{/if}/>
	</div>
	<div class="break"></div>
</div>
{undef $checked}