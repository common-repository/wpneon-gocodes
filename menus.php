<?php

if ( ! defined( 'ABSPATH' ) ) {
	die;
}

//***** Manage Menu *****
function wsc_gocodes_managemenu() {
	global $wpdb;
	$table_name  = $wpdb->prefix . "wsc_gocodes";
	echo '<div class="wrap">';

	if ( isset( $_GET['page'] ) && $_GET['page'] == "gocodes" && ! isset( $_GET['editgc'] ) ) {

//Add Redirect
		if ( isset( $_GET['savegc'] ) && $_GET['savegc'] == "yes") {
			$gckey     = isset( $_POST['Key'] ) ? sanitize_text_field( $_POST['Key'] ) : '';
			$gctarget  = isset( $_POST['Target'] ) ? sanitize_text_field( $_POST['Target'] ) : '';
			$gckey     = preg_replace("[^a-zA-Z0-9._-]", "", $gckey );
			$gcdocount = ( isset( $_POST['DoCount'] ) && intval( $_POST['DoCount'] ) ) ? (int) $_POST['DoCount'] : '';

			if ( $gcdocount == "on" ) {
				$gcdocount = 1;
			} else {
				$gcdocount = 0;
			}
			if ( $gckey != "" && $gctarget != "" && $gctarget != "http://" ) {
				$result = $wpdb->insert(
					$table_name,
					array(
						'key1'    => $gckey,
						'target'  => $gctarget,
						'docount' => $gcdocount
					),
					array(
						'%s',
						'%s',
						'%d'
					)
				);
				echo '<div id="message" class="updated fade"><p>Redirect added successfully.</p></div>';
			} else {
				echo '<div id="message" class="updated fade"><p>Could not add redirect. You did not properly fill-out both fields!</p></div>';
			}
		}

//Delete Redirect
		if ( isset( $_GET['deletegc'] ) && $_GET['deletegc'] != "" && intval( $_GET['deletegc'] ) ) {
			$gcid = (int) $_GET['deletegc'];
			echo '<div id="message" class="updated fade"><p>Are you sure you want to delete the redirect? <a href="' . GOCODES_URL . '&deletegcconf=yes&gcid=' . $gcid . '">Yes</a> &nbsp; <a href="' . GOCODES_URL . '">No!</a></p></div>';
		}
		if ( isset( $_GET['deletegcconf'] ) && $_GET['deletegcconf'] === "yes" && ! empty( $_GET['gcid'] ) && intval( $_GET['gcid'] ) ) {
			$gcid       = (int) $_GET['gcid'];
			$wpdb->delete(
				$table_name,
				array(
					'id' => $gcid
				),
				array(
					'%d'
				)
			);
			echo '<div id="message" class="updated fade"><p>Redirect removed successfully.</p></div>';
		}

//Uninstall plugin
		if ( isset( $_GET['uninstallgc'] ) &&  $_GET['uninstallgc'] === "yes" ) {
			echo '<div id="message" class="updated fade"><p><strong>Are you sure you want to delete the GoCodes database entries? You will lose all of your redireccts!</strong><br/><a href="' . GOCODES_URL . '&uninstallgc=yes&confirm=yes">Yes, delete.</a> &nbsp; <a href="index.php">NO!</a></p></div>';
			if ( isset( $_GET['uninstallgc'] ) && $_GET['uninstallgc'] == "yes" &&  isset( $_GET['confirm'] ) && $_GET['confirm'] == "yes" ) {
				$uninstallgc = "DROP TABLE " . $table_name;
				$results     = $wpdb->query( $uninstallgc );
				echo '<div id="message" class="updated fade"><p>GoCodes has removed its database entries. Now deactivate the plugin.</p></div>';
				return;
			}
		}

//Update Redirect
		if ( isset( $_GET['editgcconf'] ) && $_GET['editgcconf'] == "yes" ) {
			$gcpostid      = ( isset( $_POST['id'] ) && intval( $_POST['id'] ) )  ? (int) $_POST['id'] : '';
			$gcpostkey     = isset( $_POST['Key'] )  ? sanitize_text_field( $_POST['Key'] ) : '';
			$gcposttarget  = isset( $_POST['Target'] ) ? sanitize_text_field( $_POST['Target'] ) : '';
			$gcpostdocount = isset( $_POST['DoCount'] )  ? $_POST['DoCount'] : '';
			$gcpostkey     = preg_replace("[^a-zA-Z0-9._-]", "", $gcpostkey );
			if ( $gcpostdocount == "on") {
				$gcpostdocount = 1;
			} else {
				$gcpostdocount = 0;
			}
			if ( ! empty( $gcpostkey )  && ! empty( $gcposttarget ) && ( $gcposttarget != "http://" ) ) {
				$results = $wpdb->update(
					$table_name,
					array(
						'target'  => $gcposttarget,
						'key1'    => $gcpostkey,
						'docount' => $gcpostdocount
					),
					array( 'id' => $gcpostid ),
					array(
						'%s',
						'%s',
						'%d'
					)
				);
				echo '<div id="message" class="updated fade"><p>Redirect saved successfully.</p></div>';
			}
			else { echo '<div id="message" class="updated fade"><p>Could not update redirect. You did not properly fill-out a field!</p></div>'; }
		}

//Reset Redirect Counter
		if (  isset( $_GET['gcresetcount'] ) && $_GET['gcresetcount'] == "yes" && ! empty( $_GET['gcid'] ) && intval( $_GET['gcid'] ) ) {
			$gcid = (int) $_GET['gcid'];
			echo '<div id="message" class="updated fade"><p>Are you sure you want to reset the hit count for the redirect? <a href="' . GOCODES_URL . '&gcresetcountconf=yes&gcid=' . $gcid . '">Yes</a> &nbsp; <a href="' . GOCODES_URL . '">No!</a></p></div>';
		}
		if ( isset( $_GET['gcresetcountconf'] ) && $_GET['gcresetcountconf'] == "yes" && ! empty( $_GET['gcid'] ) && intval( $_GET['gcid'] ) ) {
			$gcid       = (int) $_GET['gcid'];
			$results    = $wpdb->update(
				$table_name,
				array(
					'hitcount' => 0
				),
				array(
					'id' => $gcid
				),
				array(
					'%d'
				)
			);
		}


//Form
		echo "<h2>Add GoCode</h2>";
		echo '<div>';
		echo '<form method="post" action="' . GOCODES_URL . '&savegc=yes">';
		echo '<table class="form-table">';
		echo '<tr class="form-field form-required">';
		echo '<th scope="row" valign="top"><label for="Key">Redirection Key</label></th>';
		echo '<td><input type="text" name="Key" value="" />';
		echo ' <br />The text after /go/ that triggers the redirect (e.g. yourblog.com/go/thekey/).</td>';
		echo '</tr>';
		echo '<tr class="form-field form-required">';
		echo '<th scope="row" valign="top"><label for="Target">Target URL</label></th>';
		echo '<td><input type="text" name="Target" value="http://" />';
		echo ' <br />The URL you wish to redirect to. "http://" is required.</td>';
		echo '</tr>';
		echo '<tr class="form-field form-required">';
		echo '<th scope="row" valign="top"><label for="DoCount">Count hits?</label></th>';
		echo ' <td><input type="checkbox" name="DoCount" style="width:1em" /> Yes, track the number of times this redirect is used.</td>';
		echo '</tr>';
		echo '</table>';
		echo ' <p class="submit"><input type="submit" name="Submit" class="button" value="Add Redirect" /></p>';
		echo '</form>';
		echo '<br/>';
		echo '</div>';

//List
		echo "<h2>Manage GoCodes</h2><br />";
		echo '<div class="subsubsub" style="margin-top:-10px;"><strong>Sort by:</strong> <a href="' . GOCODES_URL . '">Date Added</a> | <a href="' . GOCODES_URL . '&sortby=key">Key</a> | <a href="' . GOCODES_URL . '&sortby=hits">Hits</a></div>';
		echo '<div>';
		echo '<table class="widefat">';
		echo '<thead>';
		echo '<tr>';
		echo '<th scope="col"><div style="text-align: center">Key</div></th>';
		echo '<th scope="col"><div style="text-align: center">Target</div></th>';
		echo '<th scope="col"><div style="text-align: center">Hits</div></th>';
		echo '<th scope="col"></th>';
		echo '<th scope="col"></th>';
		echo '</tr>';
		echo '</thead>';
		echo '<tbody id="the-list">';

		$trigger = get_option("wsc_gocodes_url_trigger");
		if ( $trigger == '' ) {
			$trigger = 'go';
		}
		if ( isset( $_GET['sortby'] ) ) {
			$sortby = $_GET['sortby'];
			if ( $sortby == 'key' ) {
				$sort = 'key1 ASC';
			} else if ( $sortby == 'hits' ) {
				$sort = 'hitcount DESC';
			}			
		} else {
			$sort = 'id DESC';
		}

		$gocodes   = $wpdb->get_results( "SELECT id, target, key1, docount, hitcount FROM $table_name WHERE key1 != '' ORDER BY $sort", OBJECT );
		$basewpurl = get_option('siteurl');
		if ( $gocodes ) :
			foreach ( $gocodes as $gocode ):
				if ( $gocode->docount != 1 ) {
					$gocode->hitcount = "";
				}
				echo '<tr class="alternate"> <td><strong>' . esc_html( $gocode->key1 ) . '</strong><br /><small>' . esc_url_raw( $basewpurl ) . '/' . esc_html( $trigger ) . '/' . esc_html( $gocode->key1 ) . '/</small></td> <td>' . esc_html( wsc_gocodes_truncate($gocode->target) ) . '</td> <td style="text-align: center">' . ( ( $gocode->docount == 1 ) ? (int) $gocode->hitcount : '' ) . '</td> <td><a href="' . GOCODES_URL . '&editgc=' . (int) $gocode->id . '">Edit</a></td> <td><a href="' . GOCODES_URL . '&deletegc=' . (int) $gocode->id . '" class="delete">Delete</a></td> </tr>';
			endforeach; 
		else : 
			echo "<tr><td colspan='3'>Not Found</td></tr>";
		endif;

		echo '</tbody>';
		echo '</table>';
		echo '</div>';

	}

	if ( ! empty( $_GET['editgc'] ) && intval( $_GET['editgc'] ) ) {
		$gcid       = (int) $_GET['editgc'];
		$editquery  = "SELECT id, target, key1, docount, hitcount FROM $table_name WHERE id=$gcid";
		$gocode     = $wpdb->get_row( $editquery, OBJECT );
		echo '<div class="wrap">';
		echo "<h2>Edit GoCode</h2>";
		echo '<div>';
		echo '<form method="post" action="' . GOCODES_URL . '&editgcconf=yes">';
		echo '<table class="form-table">';
		echo '<table class="form-table">';
		echo '<tr class="form-field form-required">';
		echo '<th scope="row" valign="top"><label for="Key">Redirection Key</label></th>';
		echo '<td><input type="text" name="Key" value="' . esc_html( $gocode->key1 ) . '" />';
		echo '<br />The text after /go/ that triggers the redirect (e.g. yourblog.com/go/thekey/).</td>';
		echo '</tr>';
		echo '<tr class="form-field form-required">';
		echo '<th scope="row" valign="top"><label for="Target">Target URL</label></th>';
		echo '<td><input type="text" name="Target" value="' . esc_url_raw( $gocode->target ) . '" />';
		echo '<br />The URL you wish to redirect to. "http://" is required.</td>';
		echo '</tr>';
		echo '<tr class="form-field form-required">';
		echo '<th scope="row" valign="top"><label for="DoCount">Count hits?</label></th>';
		echo ' <td><input type="checkbox" name="DoCount"'; if( $gocode->docount == 1 ) { echo 'checked="checked"'; } echo '/> Yes, track the number of times this redirect is used. &nbsp;&nbsp; <a href="' . GOCODES_URL . '&gcresetcount=yes&gcid=' . (int) $gcid . '">Reset count</a></td>';
		echo '</tr>';
		echo '</table>';
		echo ' <input type="hidden" name="id" value="' . (int) $gcid . '" />';
		echo ' <p class="submit"><input type="submit" name="Submit" class="button" value="Edit Redirect" /></p>';
		echo '</form>';
		echo '</div>';
		echo '</div>';
	}

	wsc_gocodes_footer();
	echo '</div>';
}



//***** Options Menu *****
function wsc_gocodes_optionsmenu() {
	echo '<div class="wrap">';
	echo "<h2>GoCodes Settings</h2>";
	if ( isset( $_POST['issubmitted'] ) && $_POST['issubmitted'] == 'yes' ) {
		$post_urltrigger = isset( $_POST['urltrigger'] ) ? sanitize_text_field( wp_unslash( $_POST['urltrigger'] ) ) : '';
		$post_nofollow   = isset( $_POST['nofollow'] ) ? sanitize_text_field( $_POST['nofollow'] ) : '';
		if ( $post_nofollow == 'on' ) {
			$post_nofollow = 'yes';
		} else {
			$post_nofollow = '';
		}
		update_option( "wsc_gocodes_url_trigger", $post_urltrigger );
		update_option( "wsc_gocodes_nofollow", $post_nofollow );
	}
	$setting_url_trigger = get_option( "wsc_gocodes_url_trigger" );
	$setting_nofollow    = get_option( "wsc_gocodes_nofollow" );
	if ( $setting_url_trigger == '' ) {
		$setting_url_trigger = 'go';
	}

	echo '<form method="post" >';
	echo '<table class="form-table">';
	?>

	<tr valign="top">
		<th scope="row">URL Trigger</th>
		<td><input name="urltrigger" type="text" id="urltrigger" value="<?php echo esc_html( $setting_url_trigger ); ?>" size="50" /><br/>Change the <em>/go/</em> part of your redirects to something else. Enter without slashes.</td>
	</tr>

	<tr valign="top">
		<th scope="row">Nofollow</th>
		<td><input type="checkbox" name="nofollow" <?php if ( $setting_nofollow != '' ) { echo 'checked="checked"'; } ?> /> Nofollow GoCodes <br/>Adds a <em>nofollow</em> into the redirection sequence.</td>
	</tr>

	<?php
	echo '</table>';
	echo '<input name="issubmitted" type="hidden" value="yes" />';
	echo '<p class="submit"><input type="submit" name="Submit" value="Save settings" /></p>';
	echo '</form>';
	wsc_gocodes_footer();
	echo '</div>';
}



//***** Common Elements *****
function wsc_gocodes_admin_script() {
	if ( function_exists( 'wp_enqueue_style' ) ) {
		wp_enqueue_script('thickbox');
	}
}
function wsc_gocodes_admin_style() {
	if ( function_exists( 'wp_enqueue_style' ) ) {
		wp_enqueue_style('thickbox');
	}
}

add_action( 'init', 'wsc_gocodes_admin_script' );
add_action( 'wp_head', 'wsc_gocodes_admin_style' );

function wsc_gocodes_footer() {
	echo '<div style="margin-top:45px; font-size:0.87em;">';
	echo '<div><a href="' . plugin_dir_url( __FILE__ ) . 'readme.txt?KeepThis=true&amp;TB_iframe=true&amp;height=450&amp;width=680" class="thickbox" title="Documentation">Documentation</a> | <a href="http://wpneon.com/gocodes-wordpress-redirection-plugin/">GoCodes Homepage</a></div>';
	echo '</div>';
}

?>
