<form method="post" action="" name="datalist_filter_form" id="datalist-filter-form">
	<div class="datalist-user-filters-container">
		<!-- current filters -->
		<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">
			<h1 class="context-title">{'Current filter'|i18n( 'extension/datalist' )}</h1>
			<div class="header-mainline"></div>
		</div></div></div></div></div></div>

		<div class="box-ml"><div class="box-mr"><div class="box-content">
			<div class="context-toolbar"><div class="break"></div></div>

			{foreach $data_list_settings.attribute_groups as $attribute_group}
				{if eq( $attribute_group.has_filtrable_attribute, true() )}
					<div class="datalist-filter-group" id="datalist-filter-group-{$attribute_group.identifier}">
						<a href="#" class="datalist-filter-group-title">{$attribute_group.name}</a>
						<div class="datalist-filter-group-attributes" style="display: none;">
						{foreach $attribute_group.data_list_attributes as $data_list_attribute_index => $data_list_attribute}
							{if $data_list_attribute.filterable}
								<div class="datalist-filter-group-attribute">
									<div class="datalist-filter-group-attribute-title">{$data_list_attribute.name}</div>
									<div class="datalist-filter-group-attribute-value">{$data_list_attribute.filter_view}</div>
									<div class="break"></div>
								</div>
							{/if}
						{/foreach}
						</div>
					</div>
				{/if}
			{/foreach}

			<script type="text/javascript">
			{literal}
			window.addEvent( 'domready', function() {
				var attributeGroups = new Array();
				{/literal}
				{foreach $data_list_settings.attribute_groups as $attribute_group}
					{if eq( $attribute_group.has_filtrable_attribute, true() )}
					attributeGroups.include( new Hash( {ldelim}
						'identifier': '{$attribute_group.identifier}',
						'opened': '{if $opened_filter_attribute_groups|contains( $attribute_group.identifier )}1{else}0{/if}'
					{rdelim} ) );
					{/if}
				{/foreach}
				{literal}

				attributeGroups.each( function( group ) {
					var groupContainer = document.id( 'datalist-filter-group-' + group.get( 'identifier' ) );
					var el = groupContainer.getElement( 'a.datalist-filter-group-title' );
					el.store( 'filter_slider', new Fx.Slide( groupContainer.getElement( 'div.datalist-filter-group-attributes' ) ) );

					if( group.get( 'opened' ) == 0 ) {
						el.toggleClass( 'opened' ).retrieve( 'filter_slider' ).hide();
					}
					el.retrieve( 'filter_slider' ).element.setStyle( 'display', 'block' );

					el.addEvent( 'click', function( e ) {
						e.stop();
						this.retrieve( 'filter_slider' ).toggle().chain( function() {
							this.toggleClass( 'opened' )
						}.bind( this ) );
					} );
				} );
			} );
			{/literal}
			</script>

			<div class="context-toolbar"><div class="break"></div></div>
		</div></div></div>

		<div class="controlbar">
			<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">
				<div class="block">
					<div class="left">
						<input class="button" type="submit" value="{'Apply filters'|i18n( 'extension/datalist' )}" name="datalist_filter_apply" />
					</div>
					<div class="float-right">
						<input type="text" size="30" value="" name="datalist_filter_name" id="datalist-filter-name" /> <input class="button" type="submit" value="{'Save filter'|i18n( 'extension/datalist' )}" name="datalist_filter_save" />
					</div>
					<div class="break"></div>
				</div>
			</div></div></div></div></div></div>
		</div>
		<!-- end current filters -->
	</div>
</form>