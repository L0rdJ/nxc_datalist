{def
	$image_content = $attribute.content
	$image = false()
	$alt_text = false()
}

{if $image_content.is_valid}
	{set $image = $image_content['datalist_view']}
    {if $image.text}
        {set $alt_text = $image.text}
    {else}
        {set $alt_text = $attribute.object.name}
    {/if}

	<img src="{$image.url|ezroot( 'no' )}" alt="{$alt_text|wash( xhtml )}" title="{$title|wash( xhtml )}" />
{/if}

{undef $image_content $image $alt_text}