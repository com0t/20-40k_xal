<?php
/**
 * Co-Authors Guest Authors
 *
 * Key idea: Create guest authors to assign as bylines on a post without having
 * to give them access to the dashboard through a WP_User account
 */

class CoAuthors_Guest_Authors {


	var $post_type              = 'guest-author';
	var $parent_page            = 'users.php';
	var $list_guest_authors_cap = 'list_users';

	public static $cache_group = 'coauthors-plus-guest-authors';

	/**
	 * Initialize our Guest Authors class and establish common hooks
	 */
	function __construct() {
		global $coauthors_plus;

		// Add the guest author management menu
		add_action( 'admin_menu', array( $this, 'action_admin_menu' ) );

		// WP List Table for breaking out our Guest Authors
		require_once dirname( __FILE__ ) . '/class-coauthors-wp-list-table.php';

		// Get a co-author based on a query
		add_action( 'wp_ajax_search_coauthors_to_assign', array( $this, 'handle_ajax_search_coauthors_to_assign' ) );

		// Any CSS or JS
		add_action( 'admin_enqueue_scripts', array( $this, 'action_admin_enqueue_scripts' ) );

		// Extra notices
		add_action( 'admin_notices', array( $this, 'action_admin_notices' ) );

		// Handle actions to create or delete guest author accounts
		add_action( 'admin_init', array( $this, 'handle_create_guest_author_action' ) );
		add_action( 'admin_init', array( $this, 'handle_delete_guest_author_action' ) );

		// Redirect if the user is mapped to a guest author
		add_action( 'parse_request', array( $this, 'action_parse_request' ) );

		// Filter author links and such
		add_filter( 'author_link', array( $this, 'filter_author_link' ), 10, 3 );

		// Over-ride the author feed
		add_filter( 'author_feed_link', array( $this, 'filter_author_feed_link' ), 10, 2 );

		// Validate new guest authors
		add_filter( 'wp_insert_post_empty_content', array( $this, 'filter_wp_insert_post_empty_content' ), 10, 2 );

		// Add metaboxes for our guest author management interface
		add_action( 'add_meta_boxes', array( $this, 'action_add_meta_boxes' ), 10, 2 );
		add_action( 'wp_insert_post_data', array( $this, 'manage_guest_author_filter_post_data' ), 10, 2 );
		add_action( 'save_post', array( $this, 'manage_guest_author_save_meta_fields' ), 10, 2 );

		// Empty associated caches when the guest author profile is updated
		add_filter( 'update_post_metadata', array( $this, 'filter_update_post_metadata' ), 10, 5 );

		// Modify the messages that appear when saving or creating
		add_filter( 'post_updated_messages', array( $this, 'filter_post_updated_messages' ) );

		// Allow admins to create or edit guest author profiles from the Manage Users listing
		add_filter( 'user_row_actions', array( $this, 'filter_user_row_actions' ), 10, 2 );

		// Add support for featured thumbnails that we can use for guest author avatars
		add_filter( 'get_avatar', array( $this, 'filter_get_avatar' ), 10, 5 );

		// Add a Personal Data Exporter to guest authors
		add_filter( 'wp_privacy_personal_data_exporters', array( $this, 'filter_personal_data_exporter' ), 1 );

		// Allow users to change where this is placed in the WordPress admin
		$this->parent_page = apply_filters( 'coauthors_guest_author_parent_page', $this->parent_page );

		// Allow users to change the required cap for modifying guest authors
		$this->list_guest_authors_cap = apply_filters( 'coauthors_guest_author_manage_cap', $this->list_guest_authors_cap );

		// Set up default labels, but allow themes to modify
		$this->labels = apply_filters(
			'coauthors_guest_author_labels',
			array(
				'singular'              => __( 'Guest Author', 'co-authors-plus' ),
				'plural'                => __( 'Guest Authors', 'co-authors-plus' ),
				'all_items'             => __( 'All Guest Authors', 'co-authors-plus' ),
				'add_new_item'          => __( 'Add New Guest Author', 'co-authors-plus' ),
				'edit_item'             => __( 'Edit Guest Author', 'co-authors-plus' ),
				'new_item'              => __( 'New Guest Author', 'co-authors-plus' ),
				'view_item'             => __( 'View Guest Author', 'co-authors-plus' ),
				'search_items'          => __( 'Search Guest Authors', 'co-authors-plus' ),
				'not_found'             => __( 'No guest authors found', 'co-authors-plus' ),
				'not_found_in_trash'    => __( 'No guest authors found in Trash', 'co-authors-plus' ),
				'update_item'           => __( 'Update Guest Author', 'co-authors-plus' ),
				'metabox_about'         => __( 'About the guest author', 'co-authors-plus' ),
				'featured_image'        => __( 'Avatar', 'co-authors-plus' ),
				'set_featured_image'    => __( 'Set Avatar', 'co-authors-plus' ),
				'use_featured_image'    => __( 'Use Avatar', 'co-authors-plus' ),
				'remove_featured_image' => __( 'Remove Avatar', 'co-authors-plus' ),
			)
		);

		// Register a post type to store our guest authors
		$args = array(
			'label'               => $this->labels['singular'],
			'labels'              => array(
				'name'                  => isset( $this->labels['plural'] ) ? $this->labels['plural'] : '',
				'singular_name'         => isset( $this->labels['singular'] ) ? $this->labels['singular'] : '',
				'add_new'               => _x( 'Add New', 'guest author', 'co-authors-plus' ),
				'all_items'             => isset( $this->labels['all_items'] ) ? $this->labels['all_items'] : '',
				'add_new_item'          => isset( $this->labels['add_new_item'] ) ? $this->labels['add_new_item'] : '',
				'edit_item'             => isset( $this->labels['edit_item'] ) ? $this->labels['edit_item'] : '',
				'new_item'              => isset( $this->labels['new_item'] ) ? $this->labels['new_item'] : '',
				'view_item'             => isset( $this->labels['view_item'] ) ? $this->labels['view_item'] : '',
				'search_items'          => isset( $this->labels['search_items'] ) ? $this->labels['search_items'] : '',
				'not_found'             => isset( $this->labels['not_found'] ) ? $this->labels['not_found'] : '',
				'not_found_in_trash'    => isset( $this->labels['not_found_in_trash'] ) ? $this->labels['not_found_in_trash'] : '',
				'featured_image'        => isset( $this->labels['featured_image'] ) ? $this->labels['featured_image'] : '',
				'set_featured_image'    => isset( $this->labels['set_featured_image'] ) ? $this->labels['set_featured_image'] : '',
				'use_featured_image'    => isset( $this->labels['use_featured_image'] ) ? $this->labels['use_featured_image'] : '',
				'remove_featured_image' => isset( $this->labels['remove_featured_image'] ) ? $this->labels['remove_featured_image'] : '',
			),
			'public'              => true,
			'publicly_queryable'  => false,
			'exclude_from_search' => true,
			'show_in_menu'        => false,
			'supports'            => array(
				'thumbnail',
			),
			'taxonomies'          => array(
				$coauthors_plus->coauthor_taxonomy,
			),
			'rewrite'             => false,
			'query_var'           => false,
		);
		register_post_type( $this->post_type, $args );

		// Some of the common sizes used by get_avatar
		$this->avatar_sizes = array();

		// Hacky way to remove the title and the editor
		remove_post_type_support( $this->post_type, 'title' );
		remove_post_type_support( $this->post_type, 'editor' );

	}

	/**
	 * Filter the messages that appear when saving or updating a guest author
	 *
	 * @since 3.0
	 */
	function filter_post_updated_messages( $messages ) {
		global $post;

		if ( $this->post_type !== $post->post_type ) {
			return $messages;
		}

		$guest_author      = $this->get_guest_author_by( 'ID', $post->ID );
		$guest_author_link = $this->filter_author_link( '', $guest_author->ID, $guest_author->user_nicename );

		$messages[ $this->post_type ] = array(
			0  => '', // Unused. Messages start at index 1.
			1  => sprintf( __( 'Guest author updated. <a href="%s">View profile</a>', 'co-authors-plus' ), esc_url( $guest_author_link ) ),
			2  => __( 'Custom field updated.', 'co-authors-plus' ),
			3  => __( 'Custom field deleted.', 'co-authors-plus' ),
			4  => __( 'Guest author updated.', 'co-authors-plus' ),
			/* translators: %s: date and time of the revision */
			5  => isset( $_GET['revision'] ) ? sprintf( __( 'Guest author restored to revision from %s', 'co-authors-plus' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
			6  => sprintf( __( 'Guest author updated. <a href="%s">View profile</a>', 'co-authors-plus' ), esc_url( $guest_author_link ) ),
			7  => __( 'Guest author saved.', 'co-authors-plus' ),
			8  => sprintf( __( 'Guest author submitted. <a target="_blank" href="%s">Preview profile</a>', 'co-authors-plus' ), esc_url( add_query_arg( 'preview', 'true', $guest_author_link ) ) ),
			9  => sprintf(
				__( 'Guest author scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview profile</a>', 'co-authors-plus' ),
				// translators: Publish box date format, see http://php.net/date
				date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ),
				esc_url( $guest_author_link )
			),
			10 => sprintf( __( 'Guest author updated. <a target="_blank" href="%s">Preview profile</a>', 'co-authors-plus' ), esc_url( add_query_arg( 'preview', 'true', $guest_author_link ) ) ),
		);
		return $messages;
	}

	/**
	 * Handle the admin action to create a guest author based
	 * on an existing user
	 *
	 * @since 3.0
	 */
	function handle_create_guest_author_action() {

		if ( ! isset( $_GET['action'], $_GET['nonce'], $_GET['user_id'] ) || 'cap-create-guest-author' !== $_GET['action'] ) {
			return;
		}

		if ( ! wp_verify_nonce( $_GET['nonce'], 'create-guest-author' ) ) {
			wp_die( esc_html__( "Doin' something fishy, huh?", 'co-authors-plus' ) );
		}

		if ( ! current_user_can( $this->list_guest_authors_cap ) ) {
			wp_die( esc_html__( "You don't have permission to perform this action.", 'co-authors-plus' ) );
		}

		$user_id = intval( $_GET['user_id'] );

		// Create the guest author
		$post_id = $this->create_guest_author_from_user_id( $user_id );
		if ( is_wp_error( $post_id ) ) {
			wp_die( esc_html( $post_id->get_error_message() ) );
		}

		do_action( 'cap_guest_author_create' );

		// Redirect to the edit Guest Author screen
		$edit_link   = get_edit_post_link( $post_id, 'redirect' );
		$redirect_to = add_query_arg( 'message', 'guest-author-created', $edit_link );
		wp_safe_redirect( esc_url_raw( $redirect_to ) );
		exit;

	}

	/**
	 * Handle the admin action to delete a guest author and possibly reassign their posts
	 *
	 * @since 3.0
	 */
	function handle_delete_guest_author_action() {
		global $coauthors_plus;

		if ( ! isset( $_POST['action'], $_POST['reassign'], $_POST['_wpnonce'], $_POST['id'] ) || 'delete-guest-author' != $_POST['action'] ) {
			return;
		}

		// Verify the user is who they say they are
		if ( ! wp_verify_nonce( $_POST['_wpnonce'], 'delete-guest-author' ) ) {
			wp_die( esc_html__( "Doin' something fishy, huh?", 'co-authors-plus' ) );
		}

		// Make sure they can perform the action
		if ( ! current_user_can( $this->list_guest_authors_cap ) ) {
			wp_die( esc_html__( "You don't have permission to perform this action.", 'co-authors-plus' ) );
		}

		// Make sure the guest author actually exists
		$guest_author = $this->get_guest_author_by( 'ID', (int) $_POST['id'] );
		if ( ! $guest_author ) {
			wp_die( esc_html( sprintf( __( "%s can't be deleted because it doesn't exist.", 'co-authors-plus' ), $this->labels['singular'] ) ) );
		}

		// Perform the reassignment if needed
		$guest_author_term = $coauthors_plus->get_author_term( $guest_author );
		switch ( $_POST['reassign'] ) {
			// Leave assigned to the current linked account
			case 'leave-assigned':
				$reassign_to = $guest_author->linked_account;
				break;
			// Reassign to a different user
			case 'reassign-another':
				if ( isset( $_POST['leave-assigned-to'] ) ) {
					$user_nicename = sanitize_title( $_POST['leave-assigned-to'] );
					$reassign_to   = $coauthors_plus->get_coauthor_by( 'user_nicename', $user_nicename );
					if ( ! $reassign_to ) {
						wp_die( esc_html__( 'Co-author does not exists. Try again?', 'co-authors-plus' ) );
					}
					$reassign_to = $reassign_to->user_login;
				}
				break;
			// Remove the byline, but don't delete the post
			case 'remove-byline':
				$reassign_to = false;
				break;
			default:
				wp_die( esc_html__( 'Please make sure to pick an option.', 'co-authors-plus' ) );
				break;
		}

		$retval = $this->delete( $guest_author->ID, $reassign_to );

		$args = array(
			'page' => 'view-guest-authors',
		);
		if ( is_wp_error( $retval ) ) {
			$args['message'] = 'delete-error';
		} else {
			$args['message'] = 'guest-author-deleted';

			do_action( 'cap_guest_author_del' );
		}

		// Redirect to safety
		$redirect_to = add_query_arg( array_map( 'rawurlencode', $args ), admin_url( $this->parent_page ) );
		wp_safe_redirect( esc_url_raw( $redirect_to ) );
		exit;
	}

	/**
	 * Given a search query, suggest some co-authors that might match it
	 *
	 * @since 3.0
	 */
	function handle_ajax_search_coauthors_to_assign() {
		global $coauthors_plus;

		if ( ! current_user_can( $this->list_guest_authors_cap ) ) {
			die();
		}

		if ( ! isset( $_GET['q'] ) ) {
			die();
		}

		$search = sanitize_text_field( $_GET['q'] );
		if ( ! empty( $_GET['guest_author'] ) ) {
			$ignore = array( $this->get_guest_author_by( 'ID', (int) $_GET['guest_author'] )->user_login );
		} else {
			$ignore = array();
		}

		$results = wp_list_pluck( $coauthors_plus->search_authors( $search, $ignore ), 'user_login' );
		$retval  = array();
		foreach ( $results as $user_login ) {
			$coauthor = $coauthors_plus->get_coauthor_by( 'user_login', $user_login );
			$retval[] = (object) array(
				'display_name' => $coauthor->display_name,
				'user_login'   => $coauthor->user_login,
				'id'           => $coauthor->user_nicename,
			);
		}
		echo wp_json_encode( $retval );
		die();
	}


	/**
	 * Some redirection we need to do for linked accounts
	 *
	 * @todo support author ID query vars
	 */
	function action_parse_request( $query ) {

		if ( ! isset( $query->query_vars['author_name'] ) ) {
			return $query;
		}

		// No redirection needed on admin requests
		if ( is_admin() ) {
			return $query;
		}

		$coauthor = $this->get_guest_author_by( 'linked_account', sanitize_title( $query->query_vars['author_name'] ) );
		if ( is_object( $coauthor ) && $query->query_vars['author_name'] != $coauthor->user_login ) {
			global $wp_rewrite;
			$link = $wp_rewrite->get_author_permastruct();

			if ( empty( $link ) ) {
				$file = home_url( '/' );
				$link = $file . '?author_name=' . $coauthor->user_login;
			} else {
				$link = str_replace( '%author%', $coauthor->user_login, $link );
				$link = home_url( user_trailingslashit( $link ) );
			}
			wp_safe_redirect( $link );
			exit;
		}

		return $query;
	}

	/**
	 * Add the admin menus for seeing all co-authors
	 *
	 * @since 3.0
	 */
	function action_admin_menu() {

		add_submenu_page( $this->parent_page, $this->labels['plural'], $this->labels['plural'], $this->list_guest_authors_cap, 'view-guest-authors', array( $this, 'view_guest_authors_list' ) );

	}

	/**
	 * Enqueue any scripts or styles used for Guest Authors
	 *
	 * @since 3.0
	 */
	function action_admin_enqueue_scripts() {
		global $pagenow;
		// Enqueue our guest author CSS on the related pages
		if ( $this->parent_page === $pagenow && isset( $_GET['page'] ) && 'view-guest-authors' === $_GET['page'] ) {
			wp_enqueue_script( 'jquery-select2', plugins_url( 'lib/select2/select2.min.js', dirname( __FILE__ ) ), array( 'jquery' ), COAUTHORS_PLUS_VERSION );
			wp_enqueue_style( 'cap-jquery-select2-css', plugins_url( 'lib/select2/select2.css', dirname( __FILE__ ) ), false, COAUTHORS_PLUS_VERSION );

			wp_enqueue_style( 'guest-authors-css', plugins_url( 'css/guest-authors.css', dirname( __FILE__ ) ), false, COAUTHORS_PLUS_VERSION );
			wp_enqueue_script( 'guest-authors-js', plugins_url( 'js/guest-authors.js', dirname( __FILE__ ) ), false, COAUTHORS_PLUS_VERSION );
		} elseif ( in_array( $pagenow, array( 'post.php', 'post-new.php' ) ) && $this->post_type === get_post_type() ) {
			add_action( 'admin_head', array( $this, 'change_title_icon' ) );
		}
	}

	/**
	 * Change the icon appearing next to the title
	 * Core doesn't allow us to filter screen_icon(), so changing the ID is the next best thing
	 *
	 * @since 3.0.1
	 */
	function change_title_icon() {
		?>
		<script type="text/javascript">
			jQuery(document).ready(function($){
				$('#icon-edit').attr('id', 'icon-users');
			});
		</script>
		<?php
	}

	/**
	 * Show some extra notices to the user
	 *
	 * @since 3.0
	 */
	function action_admin_notices() {
		global $pagenow;

		if ( $this->parent_page != $pagenow || ! isset( $_REQUEST['message'] ) ) {
			return;
		}

		switch ( $_REQUEST['message'] ) {
			case 'guest-author-deleted':
				$message = __( 'Guest author deleted.', 'co-authors-plus' );
				break;
			default:
				$message = false;
				break;
		}

		if ( $message ) {
			echo '<div class="updated"><p>' . esc_html( $message ) . '</p></div>';
		}
	}

	/**
	 * Register the metaboxes used for Guest Authors
	 *
	 * @since 3.0
	 */
	function action_add_meta_boxes() {
		global $coauthors_plus;

		if ( get_post_type() == $this->post_type ) {
			// Remove the submitpost metabox because we have our own
			remove_meta_box( 'submitdiv', $this->post_type, 'side' );
			remove_meta_box( 'slugdiv', $this->post_type, 'normal' );
			add_meta_box( 'coauthors-manage-guest-author-save', __( 'Save', 'co-authors-plus' ), array( $this, 'metabox_manage_guest_author_save' ), $this->post_type, 'side', 'default' );
			add_meta_box( 'coauthors-manage-guest-author-slug', __( 'Unique Slug', 'co-authors-plus' ), array( $this, 'metabox_manage_guest_author_slug' ), $this->post_type, 'side', 'default' );
			// Our metaboxes with co-author details
			add_meta_box( 'coauthors-manage-guest-author-name', __( 'Name', 'co-authors-plus' ), array( $this, 'metabox_manage_guest_author_name' ), $this->post_type, 'normal', 'default' );
			add_meta_box( 'coauthors-manage-guest-author-contact-info', __( 'Contact Info', 'co-authors-plus' ), array( $this, 'metabox_manage_guest_author_contact_info' ), $this->post_type, 'normal', 'default' );
			add_meta_box( 'coauthors-manage-guest-author-bio', $this->labels['metabox_about'], array( $this, 'metabox_manage_guest_author_bio' ), $this->post_type, 'normal', 'default' );
		}
	}

	/**
	 * View a list table of all guest authors
	 *
	 * @since 3.0
	 */
	function view_guest_authors_list() {

		// Allow guest authors to be deleted
		if ( isset( $_GET['action'], $_GET['id'], $_GET['-author-b// Al ), COAUTHORSs_listionauthorstillow guest authors to be deleted
	st(hor-alse, COAUTHORS_P_

	 * @past(hor-al['-a = true;
		mal ), ), COAUTHr-al		mstionauthorstillow guest authors to bemetorsuthor_by( 'ID', on vi_
nformatiorsuthor_al['$current_usGET[hinformat= true;
		mal ), ), COauthorrst	ms,d
	st(>labels['plu

		iirsuthor_by(ors  , oosti_
uths/date
	a_boxye whor_al[erform this action.", 'co-autl
			});
		</script>
		<?php
	}

	/**
	 * Show some exu"= ted.', '
			})w>post_tu"y:rst	ms,_item'] : '',
			 '
			})w>post_tu"y:rst	ms,_itparse_request( $qu]_plus->gnii_
nformatior_REQUEST['message'] ) ) {
			/ Redirect tauthora_box( 'slugdiv', $thise( __FILE_s ) ) );
 tauthoraors>mcho code( $retval )).atttsHORS_P_

	 * @paMlugin( $this,-ror_alxes() {xP, $this->h, $_GET[''', arget] : '',
				'new	 * @past(hor-al['-a =		'viect aels"wur=		'viect aelpaMplus;ion iled to revuest_authes/gueshl )).atttsHORS_Redirect'ript( 'guest-authors'@paMlugin( e_queryaRSION );ost_empsers';lname o $qu]_plus->gnoepaMplus;ion iled to revues arrdb( 'Guesthhors-usersST['message'] ) ) {
			/ Re(b
				ter_post_uhes/
			/j-',f ( ! $reassiv', $thise( ssig toe			/ Re(b
				ter_p?(-_thor->linked_DT_p?_querest-author-namege'] ) ) {
	, $_GEter_post_uhes/
			/j-',f ( ! $reassiv', $		add_metawnforaT$poy( $_GET['guest_author'] ) ) {
			$ignore = arra'] ) ) {
		user_irra'] ):-useuseuseuseuseuseuseuseuse'e dela
			$r_po {
			/ ) ) {
p_su-namege'] ) ) {
	$rse {
			kd
	}

st	ms,d
	s) )tdiv', se {
		? $thi}

st	ass="uthi]_plus->gnoepaMplus}

st	ass="uthi]_plus->gnoepaMplus}

author_by( 'userf ( issabels['ust_type, 'gin;
				}
				break;
			// Remove the byline, but don't delete the post
			case 'y( $th 'nordove uery' ), COAUTHOh		'v) . '</p><dcase 'y(  No rediy( 'userf ) {
p_j) {
			/ Reduthor_by( 'ID', bksage'] ) ) {
			/ Re, 'ci'truseuseuseuse'e dela
			$r_po {
			/ ) ) {
p_su-namege']thor $Rvseuseuseuspe, 'gin;
lspe, 'gin;
lspe, 'Re,uaor-namege		// R3s_url( 'js/gu:ranslati esc_html( sprintf( __( "%s _by( 'Ip
p_su-namege']tT['re {
j,-ror_) {
			.h=' . $coauthor->use&& iss(-_trl>gnoepr Re, 'ci'truaothoeturn false;
			}

	 No r),
				'not_fod ) ) {
	$rse {
			kd
',
				'new
			/ R	/ Reduthor_byrintf( __( "_
or coauthnomypluserl( 'jso	} el ) {array();
			}
















 r),
				'not_lseiforay();
			}








Rngular_he title and tap( 	}
			'onalr),
				'not_lseiforay();
			}

.
	 * wur=		'vibb_id;
		$coaut-title and tap( 	}
 static $c'c $c'car_he tit ) e Paes   );

		// Some oauthor->use&& iss(-_trl>gnoei	}

		// Make sure th







Rngul $thi}

st	agnoepr Re, 'ci'truaothoeturn falgor Re, 'ci'truao ) {aors_plus;

		if ( ! curihe story bud		//  ) ) {
cuse&& iss(-_trl __( '
		//  ) e Paes   );

 urn fal
				wp_die( esc_htm wur=		'vibb_id;
tory bud		ues );

		if ) e Paes  'vibb$coaut-titrl>gnntf( __oetur
			kd) {
		mege'] ) ) {
	, $_GEter_posbjecyrue_sced.', 'co-autrl=use&& iss(-_trl __( '
o', 'cpl=use&&( 'ID', (int) $Ts="%>labeSRe,trl>epr Re, ' d**
	 *	/S:k'iotart at index 1.
			1  => sprintf( __Shor_, bksage'] ) )xo();
			}


oirintf( __(on ilcsw;

	
	 *
	 */**
	 * Chan_boxe?ur=trl __( '
o'   = get_post( $comment->comment_post_ID );
		$c=int) $or_fe S get_poaege'fy_message  'userf ( issabels[]mf
s

oirinfcsw;

	
	 *
	 */**
	 * Chan_boxe?ur=tr__(on ilcsw;

	
	 *
	 */**
	 * Chan_boxw;
'vibb_id;
rledcause it d/j-',f*
	 * Chal'Guestsage'] is->post_type, 'norma __( 'Guest autho
	
	 *
	 */**
	 * Cauthor_bio' ), $tor-sIlt,aost( $comment->confcsw;ues2.min.js', ohe guesdcause it d/j-gin;n}
				// Remove t bitap( 	}
			GET[hi	 */
	funo// uest admin_heoths/xes() {xP, $thithor us}pj i	 */
 t__(on i = _ON_ON_ON_ON_ONiese *
	o// uest admin_heoths/xes()

	
	 *
	 uehfst au.f ( issabL fal Ij-',f*
	 * st admin_heo'GET[hi	 */
	funod st aaMplus;ion iled to revues arrdb( 'Gues

	
	 *
	 */**
	 * Chan_bce'] = 'delete-erro 	 */
 t deleted
		ifmanage_guest_autd/j-gis_he tit ) e Paes   );

est_author_del' );()

	
	coauthor_by

		if ) e ve t bitoo revass-coauthors Chan_boxe?ur=trl __( '
o'  t(on i = __	'ona,/evues arrdb( 'Gues

	
	 *
	 */**
	 * Chan_bcegin;
lspe, 'gxists. Try again?', 'co- *
	 */**
	 * Chanevues rview_item'       
	 */
	function /**
	st_typing the ID is the next best thing
	 *
	 * rTChanevues rview_item'       f 
	 */
	ft_upd/j-',f ( ! $reas.eate' );

		//_post( $comment-de = __( 'Guestmy<ge .= __( 'Comment: ' ) . "\r\n" . $commemy ) {
		 *
s['seatf( __( 'GuestiP ge']thvge_guerGtmy<ge .= __( 'Comment: ' ) . "\iew['user_1s Chan_n" . $commemy ) {
		 *
s['seatf( __( 'GuestiP $thing
	 *
-e .= __( 'Ce th( $cent->	1  => sbitems$. $ePeT'ccth(ems$g	GET_author_posts_url( s_data_url( $args, $i $args, f

		/o:ment: ' dnevues rvisplay_nu del-'Com$seatf( Ce th( $cent->	1  => sbitems$. $ePeRthor_$ePeRthor_$ {
		megereq$i $args/o:mei $cent->	1  => 				break;
			default:
	s', dir$cent->	1 ece 3.0.1
ei $args, f

		/o:ment: ' dnevu%W_( 'Gue Cauth) {
			case d		 *
s['seatf( __( 'GuestiP r_ON_
		}

	)vu%	1  => 				F	}

	)vwexclce 3.0:ory butGgnoepaMlsplay_name,
				'user_logiT['rst_type(

	/*nt:  title
	 * Core doesn'g( __( "_
or coauthnomypluserl(oesn'g( __( "_
or coauthnomye
	 * Cors_plus->htf( Ced
		$gues		globcoauthnomye
	 * Cors_pc=(,_, ohe guesdcause it d/j-gin;nser_logiT[''}_,esn'g( __( "_
or coauthnom => true,
			'show
		$gues		f ) . "\tf( Ced
		$gery mglobal $coautthor']use we have our own
			reeatf(nt_pas$g1 Ced
		$gues	