{if $attribute.content.is_valid}
{$attribute.content.timestamp|datetime('custom','%d.%m.%Y %H:%i:%s')}
{/if}