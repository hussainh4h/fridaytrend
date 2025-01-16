<form role="search" method="get" class="search-form form-inline" action="<?php echo esc_url( home_url( '/' )); ?>">
	<div class="row w-100 mx-auto d-flex align-items-center justify-content-center">

		<?php
			// Without Location --> SM: 12+4-4-4  | MD: x-3-3-2
			// With Location --> SM: 12+4-4-4+12  | MD: x-2-2-2-2
			$parameters = array_merge( array( 's' => '', 'store' => 0, 'offer-category' => 0, 'location' => 0  ), $_GET );
		?>
		
		<div class="col-sm-12 col-md-2 my-1 search-text" style="padding-left:0.5%; padding-right:0.5%">
			<input class="form-control w-100"  <?= get_theme_mod('ajax_search','no')=='yes'?'onkeyup="cmdAjaxSearch(this);"':'' ?>  autocomplete="off" type="text" name="s" id="v-search" placeholder="<?= __("Search",'clipmydeals') ?>..." value="<?php echo $parameters['s']; ?>" />
		</div>
		
		<div class="col-sm-3 <?php echo (get_theme_mod('location_taxonomy',false) ? 'col-md-2' : 'col-md-2'); ?> my-1 search-store" style="padding-left:0.5%; padding-right:0.5%">
			<?php 
				global $store_status;
				global $exclude_stores;
				
			$terms = get_terms( 'stores', array(
			    'hide_empty' => false,
			) ); 
			foreach($terms as $term){
				store_taxonomy_status($term->term_id);
			}
				$stores_found = wp_dropdown_categories(array(
																				'name'               => 'store',
																				'id'                 => 'store',
																				'class'              => 'form-control w-100',
																				'taxonomy'           => 'stores',
																				'exclude'			 => $exclude_stores,
																				'orderby'            => 'name',
																				'order'              => 'ASC',
																				'hide_if_empty'      => true,
																				'value_field'	     => 'slug',
																				'selected'	=>	$parameters['store'],
																				'show_option_all'    => __( 'Any Store','clipmydeals'),
																				'hide_empty'         => 1,
																				'hierarchical'       => 1,
																				'depth'              => 2,
																			));
				if(!$stores_found) {
					echo '<select name="store" id="store" class="form-control w-100"><option value="0">'.__( 'Any Store','clipmydeals').'</option></select>';
				}
			?>
		</div>
		
		<div class="col-sm-4 <?php echo (get_theme_mod('location_taxonomy',false) ? 'col-md-2' : 'col-md-2'); ?> my-1 search-category" style="padding-left:0.5%; padding-right:0.5%">
			<?php
				$categories_found = wp_dropdown_categories(array(
																				'name'               => 'offer-category',
																				'id'                 => 'offer-category',
																				'class'              => 'form-control w-100',
																				'taxonomy'           => 'offer_categories',
																				'orderby'            => 'name',
																				'order'              => 'ASC',
																				'hide_if_empty'      => true,
																				'value_field'	     => 'slug',
																				'selected'	=> $parameters['offer-category'],
																				'show_option_all'    => __( 'Any Category','clipmydeals'),
																				'hide_empty'         => 1,
																				'hierarchical'       => 1,
																				'depth'              => 2,
																			));
				if(!$categories_found) {
					echo '<select name="offer-category" id="offer-category" class="form-control w-100"><option value="0">'.__( 'Any Category','clipmydeals').'</option></select>';
				}
			?>
		</div>
		
		<?php if(get_theme_mod('location_taxonomy',false)) { ?>
		<div class="col-sm-4 col-md-2 my-1 search-category" style="padding-left:0.5%; padding-right:0.5%">
			<?php
				$locations_found = wp_dropdown_categories(array(
																				'name'               => 'location',
																				'id'                 => 'location',
																				'class'              => 'form-control w-100',
																				'taxonomy'           => 'locations',
																				'orderby'            => 'name',
																				'order'              => 'ASC',
																				'hide_if_empty'      => true,
																				'value_field'	     => 'slug',
																				'selected'	=> $parameters['location'],
																				'show_option_all'    => __( 'Any Location','clipmydeals'),
																				'hide_empty'         => 1,
																				'hierarchical'       => 1,
																				'depth'              => 2,
																			));
				if(!$locations_found) {
					echo '<select name="location" id="location" class="form-control w-100"><option value="0">'.__( 'Any Location','clipmydeals').'</option></select>';
				}
			?>
		</div>
		<?php } ?>
		
		<div class="<?php echo (get_theme_mod('location_taxonomy',false) ? 'col-sm-12' : 'col-sm-4'); ?> col-md-2 my-1 search-button" style="padding-left:0.5%; padding-right:0.5%">
			<button type="submit" class="btn btn-primary btn-block"><?= __('Search','clipmydeals') ?> <i class="fa fa-search"></i></button>
		</div>
		
	</div>
</form>
