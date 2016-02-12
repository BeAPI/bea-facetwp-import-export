<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<div class="wrap">
	<h1><?php _e( 'Export' ); ?> / <?php _e( 'Import' ); ?> FACETWP</h1>

	<div class="metabox-holder">
		<div class="postbox">

			<div class="inside">

				<h2><?php _e( 'Export' ); ?></h2>

				<form method="POST">
					<?php foreach ( $export as $val => $label ) : ?>
						<input type="checkbox" name="export_facetwp[]" value="<?php echo $val; ?>">
						<label><?php echo $label; ?></label>
					<?php endforeach; ?>
					<input type="hidden" name="action" value="export_facetwp_settings"/>

					<p>
						<?php wp_nonce_field( 'export_facetwp_nonce', 'export_facetwp_nonce' ); ?>
						<?php submit_button( __( 'Export' ), 'primary', 'submit', false ); ?>
					</p>
				</form>
			</div>
		</div>
	</div>
	<div class="metabox-holder">
		<div class="postbox">

			<div class="inside">

				<h2><?php _e( 'Import' ); ?></h2>

				<form method="POST" enctype="multipart/form-data">
					<p>
						<input type="file" name="import_file"/><br/>
						<input type="checkbox" name="import_facetwp_overwrite"> <?php _e( 'Overwrite existing items?', 'fwp' ); ?>
					</p>

					<p>
						<input type="hidden" name="action" value="import_facetwp_settings"/>
						<?php wp_nonce_field( 'import_facetwp_nonce', 'import_facetwp_nonce' ); ?>
						<?php submit_button( __( 'Import' ), 'primary', 'submit', false ); ?>
					</p>
				</form>
			</div>
		</div>
	</div>
</div>