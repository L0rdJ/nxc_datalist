<div class="context-block">

	<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">
		<h1 class="context-title">{'Create'|i18n( 'extension/datalist' )}</h1>
		<div class="header-subline"></div>
	</div></div></div></div></div></div>

	<div class="box-ml"><div class="box-mr"><div class="box-content">
	</div></div></div>

	<div class="controlbar">
		<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">
			<div class="block">
				{def $parentNodes = $data_list_settings.parent_nodes}
				<form name="datalist_create_content_object" method="post" action="{'content/action'|ezurl( 'no' )}">
					{if eq( $parentNodes|count(), 1)}
					<input type="hidden" name="NodeID" value="{$parentNodes[0].node_id}" />
					{else}
					<div class="block">
						<label>Parent node:</label>
						<select name="NodeID">
						{foreach $parentNodes as $datalist_parent_node}
							<option value="{$datalist_parent_node.node_id}">{$datalist_parent_node.name}</option>
						{/foreach}
						</select>
					</div>
					{/if}

					<div class="block">
						<input type="hidden" name="ClassID" value="{$data_list_settings.content_object_class.id}" />
						<input type="hidden" name="RedirectURIAfterPublish" value="/datalist/list/{$data_list_settings.content_object_class.identifier}" />
						<input type="hidden" name="RedirectIfDiscarded" value="/datalist/list/{$data_list_settings.content_object_class.identifier}" />
					    <input type="submit" name="NewButton" value="{'Create here'|i18n( 'design/admin/node/view/full' )}" title="{'Create a new item in the current location. Use the menu on the left to select the type of  item.'|i18n( 'design/admin/node/view/full' )}" class="button" />
				    </div>
				</form>
				{undef $parentNodes}
			</div>
		</div></div></div></div></div></div>
	</div>

</div>