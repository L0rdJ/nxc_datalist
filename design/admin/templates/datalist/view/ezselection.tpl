{def $selected_id_array = $attribute.content}

{foreach $attribute.class_content.options as $option}
	{if and( $selected_id_array|contains( $option.id ), ne( $option.name, 'Not specified' ) )}
	{$option.name|wash( xhtml )}
	{/if}
{/foreach}

{undef $selected_id_array}