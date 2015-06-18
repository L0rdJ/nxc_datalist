{def
	$countries     = fetch( 'content', 'country_list' )
	$class_content = $attribute.class_content
	$country       = $attribute.content.value
}

<select name="nxc-data-list-edit[{$node.node_id}][{$attribute.contentclass_attribute_identifier}][]" {if $class_content.multiple_choice}multiple="multiple"{/if}>
	{if $class_content.multiple_choice|not}
		<option  value="">{'Not specified'|i18n( 'design/standard/content/datatype' )}</option>
	{/if}
	{def $alpha_2 = ''}
	{foreach $countries as $key => $current_country}
		{set $alpha_2 = $current_country.Alpha2}
		{if $country|ne( '' )}
			{if $country|is_array|not}
			{* Backwards compatability *}
				<option {if $country|eq( $current_country.Name )}selected="selected"{/if} value="{$alpha_2}">{$current_country.Name}</option>
			{else}
				<option {if is_set( $country.$alpha_2 )}selected="selected"{/if} value="{$alpha_2}">{$current_country.Name}</option>
			{/if}
		{else}
			<option {if is_set( $class_content.default_countries.$alpha_2 )}selected="selected"{/if} value="{$alpha_2}">{$current_country.Name}</option>
		{/if}
	{/foreach}
</select>

{undef $countries $class_content $country $alpha_2}
