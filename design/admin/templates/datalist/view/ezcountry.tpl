{if $attribute.has_content}
   {if is_array( $attribute.content.value )}
       {foreach $attribute.content.value as $country}
        {$country.Name|wash( xhtml )}
       {/foreach}
   {else}
       {$attribute.content.value|wash( xhtml )}
   {/if}
{else}
{'Not specified'|i18n( 'design/standard/content/datatype' )}
{/if}