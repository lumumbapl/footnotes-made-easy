<?php
/**
* General Options Page
*
* Screen for specifying general options for the plugin
*
* @package	footnotes-made-easy
* @since	1.0
*/
?>

<div class="wrap">

<h1><?php esc_html_e( 'Footnotes Made Easy', 'footnotes-made-easy' ); ?></h1>

<?php
if ( !empty( $_POST[ 'save_options' ] ) && ( check_admin_referer( 'footnotes-nonce', 'footnotes_nonce' ) ) ) {
	$message = __( 'Options saved.', 'footnotes-made-easy' );
} else {
	$message = '';
}
?>
	<?php if ( $message !== '' ) { ?>
	<div class="updated"><p><strong><?php echo esc_html( $message ); ?></strong></p></div>
	<?php } ?>

	<form method="post">

		<table class="form-table">

			<tr>
			<th scope="row"><label for="pre_identifier"><?php esc_html_e( 'Identifier', 'footnotes-made-easy' ); ?></label></th>
			<td>
			<input type="text" size="3" name="pre_identifier" value="<?php echo esc_attr(  $this->current_options[ 'pre_identifier' ] ); ?>" />
			<input type="text" size="3" name="inner_pre_identifier" value="<?php echo esc_attr(  $this->current_options[ 'inner_pre_identifier' ] ); ?>" />
			<select name="list_style_type">
				<?php foreach ( $this->styles as $key => $val ): ?>
				<option value="<?php echo esc_attr( $key ); ?>" <?php if ( $this->current_options[ 'list_style_type' ] === $key ) echo 'selected="selected"'; ?> ><?php echo esc_html( $val ); ?></option>
				<?php endforeach; ?>
			</select>
			<input type="text" size="3" name="inner_post_identifier" value="<?php echo esc_attr(  $this->current_options[ 'inner_post_identifier' ] ); ?>" />
			<input type="text" size="3" name="post_identifier" value="<?php echo esc_attr( $this->current_options[ 'post_identifier' ] ); ?>" />
			<p class="description"><?php esc_html_e( 'This defines how the link to the footnote will be displayed. The outer text will not be linked to.', 'footnotes-made-easy' ); ?></p></td>
			</tr>

			<tr>
			<th scope="row"><label for="list_style_symbol"><?php esc_html_e( 'Symbol', 'footnotes-made-easy' ); ?></label></th>
			<td><input type="text" size="8" name="list_style_symbol" value="<?php echo esc_attr( $this->current_options[ 'list_style_symbol' ] ); ?>" /><?php esc_html_e( 'If you have chosen a symbol as your list style.', 'footnotes-made-easy' ); ?>
			<p class="description"><?php esc_html_e( 'It\'s not usually a good idea to choose this type unless you never have more than a couple of footnotes per post', 'footnotes-made-easy' ); ?></p></td>
			</tr>

			<tr>
			<th scope="row"><label for="superscript"><?php esc_html_e( 'Superscript', 'footnotes-made-easy' ); ?></label></th>
			<td><input type="checkbox" name="superscript" <?php checked( $this->current_options[ 'superscript' ], true ); ?> /><?php esc_html_e( 'Show identifier as superscript', 'footnotes-made-easy' ); ?></td>
			</tr>

			<tr>
			<th scope="row"><label for="pre_backlink"><?php esc_html_e( 'Back-link', 'footnotes-made-easy' ); ?></label></th>
			<td>
			<input type="text" size="3" name="pre_backlink" value="<?php echo esc_attr( $this->current_options[ 'pre_backlink' ] ); ?>" />
			<input type="text" size="10" name="backlink" value="<?php echo esc_attr( $this->current_options[ 'backlink' ] ); ?>" />
			<input type="text" size="3" name="post_backlink" value="<?php echo esc_attr( $this->current_options[ 'post_backlink' ] ); ?>" />
			<p class="description"><?php
				/* translators: %s: Example back-link character (↩) */
				echo esc_html( sprintf( __( 'These affect how the back-links after each footnote look. A good back-link character is %s. If you want to remove the back-links all together, you can effectively do so by making all these settings blank.', 'footnotes-made-easy' ), '&amp;#8617; (↩)' ) );
			?></p></td>
			</tr>

			<tr>
			<th scope="row"><label for="pre_footnotes"><?php esc_html_e( 'Footnotes Header', 'footnotes-made-easy' ); ?></label></th>
			<td><textarea name="pre_footnotes" rows="3" cols="60" class="large-text code"><?php echo esc_textarea( $this->current_options[ 'pre_footnotes' ] ); ?></textarea>
			<p class="description"><?php esc_html_e( 'Anything to be displayed before the footnotes at the bottom of the post can go here.', 'footnotes-made-easy' ); ?></p></td>
			</tr>

			<tr>
			<th scope="row"><label for="post_footnotes"><?php esc_html_e( 'Footnotes Footer', 'footnotes-made-easy' ); ?></label></th>
			<td><textarea name="post_footnotes" rows="3" cols="60" class="large-text code"><?php echo esc_textarea( $this->current_options[ 'post_footnotes' ] ); ?></textarea>
			<p class="description"><?php esc_html_e( 'Anything to be displayed after the footnotes at the bottom of the post can go here.', 'footnotes-made-easy' ); ?></p></td>
			</tr>

			<tr>
			<th scope="row"><?php esc_html_e( 'Pretty Tooltips', 'footnotes-made-easy' ); ?></th>
			<td><label for="pretty_tooltips"><input type="checkbox" name="pretty_tooltips" id="pretty_tooltips" <?php checked( $this->current_options[ 'pretty_tooltips' ], true ); ?>/>
			<?php esc_html_e( 'Uses jQuery UI to show pretty tooltips', 'footnotes-made-easy' ); ?></label></td>
			</tr>

			<tr>
			<th scope="row"><?php esc_html_e( 'Combine Notes', 'footnotes-made-easy' ); ?></th>
			<td><label for="combine_identical_notes"><input type="checkbox" name="combine_identical_notes" id="combine_identical_notes" <?php checked( $this->current_options[ 'combine_identical_notes' ], true ); ?>/>
			<?php esc_html_e( 'Combine identical footnotes', 'footnotes-made-easy' ); ?></label></td>
			</tr>

			<tr>
			<th scope="row"><label for="priority"><?php esc_html_e( 'Priority', 'footnotes-made-easy' ); ?></label></th>
			<td><input type="text" size="3" name="priority" id="priority" value="<?php echo esc_attr( $this->current_options[ 'priority' ] ); ?>" />
			<?php esc_html_e( 'The default is 11', 'footnotes-made-easy' ); ?><p class="description"><?php esc_html_e( 'This setting controls the order in which this plugin executes in relation to others. Modifying this setting may therefore affect the behavior of other plugins.', 'footnotes-made-easy' ); ?></p></td>
			</tr>

			<tr>
			<th scope="row"><?php esc_html_e( 'Suppress Footnotes', 'footnotes-made-easy' ); ?></th>
			<td>
			<label for="no_display_home"><input type="checkbox" name="no_display_home" id="no_display_home" <?php checked( $this->current_options[ 'no_display_home' ], true ); ?> />&nbsp;<?php esc_html_e( 'On the Home Page', 'footnotes-made-easy' ); ?></label></br>
			<label for="no_display_preview"><input type="checkbox" name="no_display_preview" id="no_display_preview" <?php checked( $this->current_options[ 'no_display_preview' ], true ); ?> />&nbsp;<?php esc_html_e( 'When Displaying a Preview', 'footnotes-made-easy' ); ?></label></br>
			<label for="no_display_search"><input type="checkbox" name="no_display_search" id="no_display_search" <?php checked( $this->current_options[ 'no_display_search' ], true ); ?> />&nbsp;<?php esc_html_e( 'In Search Results', 'footnotes-made-easy' ); ?></label></br>
			<label for="no_display_feed"><input type="checkbox" name="no_display_feed" id="no_display_feed" <?php checked( $this->current_options[ 'no_display_feed' ], true ); ?> />&nbsp;<?php esc_html_e( 'In the feed (RSS, Atom, etc.)', 'footnotes-made-easy' ); ?></label></br>
			<label for="no_display_archive"><input type="checkbox" name="no_display_archive" id="no_display_archive" <?php checked( $this->current_options[ 'no_display_archive' ], true ); ?> />&nbsp;<?php esc_html_e( 'In Any Kind of Archive', 'footnotes-made-easy' ); ?></label></br>
			<label for="no_display_category"><input type="checkbox" name="no_display_category" id="no_display_category" <?php checked( $this->current_options[ 'no_display_category' ], true ); ?> />&nbsp;<?php esc_html_e( 'In Category Archives', 'footnotes-made-easy' ); ?></label></br>
			<label for="no_display_date"><input type="checkbox" name="no_display_date" id="no_display_date" <?php checked( $this->current_options[ 'no_display_date' ], true ); ?> />&nbsp;<?php esc_html_e( 'in date-based archives', 'footnotes-made-easy' ); ?></label></br>

			</td></tr>

		</table>

		<p><?php esc_html_e( 'Changing the following settings will change functionality in a way which may stop footnotes from displaying correctly. For footnotes to work as expected after updating these settings, you will need to manually update all existing posts with footnotes.', 'footnotes-made-easy' ); ?></p>

		<table class="form-table">

			<tr>
			<th scope="row"><label for="footnotes_open"><?php esc_html_e( 'Begin a Footnote', 'footnotes-made-easy' ); ?></label></th>
			<td><input type="text" size="3" name="footnotes_open" id="footnotes_open" value="<?php echo esc_attr( $this->current_options[ 'footnotes_open' ] ); ?>" /></td>
			</tr>

			<tr>
			<th scope="row"><label for="footnotes_close"><?php esc_html_e( 'End a Footnote', 'footnotes-made-easy' ); ?></label></th>
			<td><input type="text" size="3" name="footnotes_close" id="footnotes_close" value="<?php echo esc_attr( $this->current_options[ 'footnotes_close' ] ); ?>" /></td> 
			</tr>

		</table>

		<?php wp_nonce_field( 'footnotes-nonce','footnotes_nonce' ); ?>

		<p class="submit"><input type="submit" name="save_options" value="<?php esc_attr_e( 'Save Changes', 'footnotes-made-easy' ); ?>" class="button-primary" /></p>
		<input type="hidden" name="save_footnotes_made_easy_options" value="1" />

	</form>

</div>
