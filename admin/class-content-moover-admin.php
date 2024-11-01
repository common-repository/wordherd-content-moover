<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://wordherd.io
 * @since      1.0
 *
 * @package    Content_Moover
 * @subpackage Content_Moover/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Content_Moover
 * @subpackage Content_Moover/admin
 * @author     WordHerd <migrate@wordherd.io>
 */
class Content_Moover_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0
	 * @access   private
	 * @var      string    $content_moover    The ID of this plugin.
	 */
	private $content_moover;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0
	 * @param      string    $content_moover       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $content_moover, $version ) {

		$this->content_moover = $content_moover;
		$this->version = $version;
		
		add_action( 'admin_menu', array($this, 'content_moover_menu') );
	}

	public function content_moover_menu() {
		global $menu;
        $menu_exist = false;
        foreach($menu as $item) {
            if(strtolower($item[2]) == strtolower('wordherd-migrate')) {
                $menu_exist = true;
            }
        }
        if(!$menu_exist) {
            add_menu_page(
                'WordHerd',
                'WordHerd',
                'edit_posts',
                'wordherd-migrate',
                array($this, 'migration_page'),
                'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pg0KPCEtLSBHZW5lcmF0b3I6IEFkb2JlIElsbHVzdHJhdG9yIDE5LjAuMCwgU1ZHIEV4cG9ydCBQbHVnLUluIC4gU1ZHIFZlcnNpb246IDYuMDAgQnVpbGQgMCkgIC0tPg0KPHN2ZyB2ZXJzaW9uPSIxLjEiIGlkPSJMYXllcl8xIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB4PSIwcHgiIHk9IjBweCINCgkgdmlld0JveD0iMCAwIDUxMiA1MTIiIHN0eWxlPSJlbmFibGUtYmFja2dyb3VuZDpuZXcgMCAwIDUxMiA1MTI7IiB4bWw6c3BhY2U9InByZXNlcnZlIj4NCjxnPg0KCTxnPg0KCQk8cGF0aCBkPSJNMjExLjA1NCwzNDkuODQ1Yy04Ljc3MywwLTE1Ljg4Niw3LjExMy0xNS44ODYsMTUuODg2djM4LjAxOWMwLDguNzczLDcuMTEzLDE1Ljg4NiwxNS44ODYsMTUuODg2DQoJCQlzMTUuODg2LTcuMTEzLDE1Ljg4Ni0xNS44ODZ2LTM4LjAxOUMyMjYuOTQxLDM1Ni45NTgsMjE5LjgyOCwzNDkuODQ1LDIxMS4wNTQsMzQ5Ljg0NXoiLz4NCgk8L2c+DQo8L2c+DQo8Zz4NCgk8Zz4NCgkJPHBhdGggZD0iTTMwMC45NDUsMzQ5Ljg0NWMtOC43NzMsMC0xNS44ODYsNy4xMTMtMTUuODg2LDE1Ljg4NnYzOC4wMTljMCw4Ljc3Myw3LjExMywxNS44ODYsMTUuODg2LDE1Ljg4Ng0KCQkJYzguNzczLDAsMTUuODg2LTcuMTEzLDE1Ljg4Ni0xNS44ODZ2LTM4LjAxOUMzMTYuODMyLDM1Ni45NTgsMzA5LjcxOSwzNDkuODQ1LDMwMC45NDUsMzQ5Ljg0NXoiLz4NCgk8L2c+DQo8L2c+DQo8Zz4NCgk8Zz4NCgkJPHBhdGggZD0iTTUxMiwxNTMuNjc3YzAtOC43NzMtNy4xMTMtMTUuODg2LTE1Ljg4Ni0xNS44ODZIMzk0LjA3Yy03LjA2OC0xMi43MjUtMTUuOTUtMjQuNTQ0LTI2LjUyNy0zNS4xMjINCgkJCWMtMC4zNzctMC4zNzctMC43NjQtMC43MzctMS4xNDMtMS4xMVY0NC4xODZjMC04Ljc3My03LjExMy0xNS44ODYtMTUuODg2LTE1Ljg4NnMtMTUuODg2LDcuMTEzLTE1Ljg4NiwxNS44ODZ2MzMuMTcNCgkJCWMtNDkuMDc1LTI4LjI4Ny0xMDkuNC0yNy4zNjQtMTU3LjI1Ni0wLjExNlY0NC4xODZjMC04Ljc3My03LjExMy0xNS44ODYtMTUuODg2LTE1Ljg4NnMtMTUuODg2LDcuMTEzLTE1Ljg4NiwxNS44ODZ2NTcuMDU4DQoJCQljLTExLjA0MiwxMC44NTMtMjAuMzIzLDIzLjI2Ny0yNy42MzcsMzYuNTQ5SDE1Ljg4NkM3LjExMywxMzcuNzkyLDAsMTQ0LjkwNSwwLDE1My42NzhjMCw1MC40NDUsNTAuODExLDg1LjY0Miw5OC4yNDksNjcuMjg4DQoJCQl2NzguOTAyYy0yNC4yMzEsMTUuODE0LTM4LjkyLDQyLjU5Mi0zOC45Miw3Mi4wNTF2MjUuNjQyYzAsNDcuNDk3LDM4LjY0MSw4Ni4xMzksODYuMTQsODYuMTM5aDIyMS4wNjMNCgkJCWM0Ny40OTgsMCw4Ni4xNC0zOC42NDEsODYuMTQtODYuMTM5di0yNS42NDJjMC0yOS40NjItMTQuNjk0LTU2LjI0Mi0zOC45Mi03Mi4wNTJ2LTc4LjkwMQ0KCQkJQzQ2MS4yMzIsMjM5LjMzNiw1MTIsMjA0LjA2LDUxMiwxNTMuNjc3eiBNMjU2LjIyMSw4OC4yMjhjLTEwLjM2NSw1Ny4zNzItNjMuMDQ5LDk4LjE5Ni0xMjEuODQxLDkzLjA3NA0KCQkJQzE0OS4xODcsMTI2LjM0LDE5OS4yMTIsODguMTYyLDI1Ni4yMjEsODguMjI4eiBNMTMwLjAyMSwyMTQuMjEzYzAtMC40NzIsMC4wMTEtMC45NDQsMC4wMTYtMS40MTYNCgkJCWM3Ni40MSw3LjUwMSwxNDUuMTQ1LTQ1LjczLDE1Ny43MzYtMTIwLjYzN2M1My4xNDYsMTMuNzI3LDk0LjIwNiw2Mi42NDYsOTQuMjA2LDEyMi4wNTN2NzIuOTM4DQoJCQljLTE4LjIzMS0zLjI5NC0yNDQuMjQ2LTEuMzk4LTI1MS45NTgsMFYyMTQuMjEzeiBNMzUuMDI3LDE2OS41NjNoNjkuNjM2Yy0xLjA5OSwzLjczNS0yLjEyNSw3Ljc4Mi0yLjk0MywxMS42MjENCgkJCUM4MS45MDIsMjAyLjUzMyw0Ni41NTYsMTk2LjQxNCwzNS4wMjcsMTY5LjU2M3ogTTQyMC44OTgsMzcxLjkxOXYyNS42NDJoMC4wMDFjMCwyOS45NzgtMjQuMzg4LDU0LjM2Ny01NC4zNjgsNTQuMzY3SDE0NS40NjgNCgkJCWMtMjkuOTc5LDAtNTQuMzY4LTI0LjM4OC01NC4zNjgtNTQuMzY3di0yNS42NDJjMC0zMC41MzYsMjQuNzMtNTQuMzY3LDU0LjM2OC01NC4zNjdoMjIxLjA2Mw0KCQkJQzM5Ni4xMTEsMzE3LjU1Myw0MjAuODk4LDM0MS4zMjksNDIwLjg5OCwzNzEuOTE5eiBNNDEwLjMsMTgxLjIwNWMtMC44MzEtMy45MjUtMS44MS03LjgwOS0yLjkzNi0xMS42NDJoNjkuNjA5DQoJCQlDNDY1LjQzNCwxOTYuNDM4LDQzMC4xMDIsMjAyLjUwNyw0MTAuMywxODEuMjA1eiIvPg0KCTwvZz4NCjwvZz4NCjxnPg0KCTxnPg0KCQk8Y2lyY2xlIGN4PSIxNzkuOTE2IiBjeT0iMjQwLjU2OSIgcj0iMjEuMDE1Ii8+DQoJPC9nPg0KPC9nPg0KPGc+DQoJPGc+DQoJCTxjaXJjbGUgY3g9IjMzMi43MjkiIGN5PSIyNDAuNTY5IiByPSIyMS4wMTUiLz4NCgk8L2c+DQo8L2c+DQo8Zz4NCjwvZz4NCjxnPg0KPC9nPg0KPGc+DQo8L2c+DQo8Zz4NCjwvZz4NCjxnPg0KPC9nPg0KPGc+DQo8L2c+DQo8Zz4NCjwvZz4NCjxnPg0KPC9nPg0KPGc+DQo8L2c+DQo8Zz4NCjwvZz4NCjxnPg0KPC9nPg0KPGc+DQo8L2c+DQo8Zz4NCjwvZz4NCjxnPg0KPC9nPg0KPGc+DQo8L2c+DQo8L3N2Zz4NCg==',
                '25'
            );
        }

		add_submenu_page( 
			'wordherd-migrate',
			__( 'Content Moover', 'content-moover' ),
			__( 'Content Moover', 'content-moover' ),
			'manage_options',
			'wordherd-migrate-settings',
			array($this, 'migration_page')
		);
	}

	public function migration_page() {
		require('partials/content-moover-admin-display.php');
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0
	 */
	public function enqueue_styles() {
		$my_current_screen = get_current_screen();
		if($my_current_screen->base == 'wordherd_page_wordherd-migrate-settings' || $my_current_screen->base == 'toplevel_page_wordherd-migrate') {
			wp_enqueue_style( $this->content_moover, plugin_dir_url( __FILE__ ) . 'css/content-moover-admin.css', array(), $this->version, 'all' );
		}
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0
	 */
	public function enqueue_scripts() {
		$my_current_screen = get_current_screen();
		if($my_current_screen->base == 'wordherd_page_wordherd-migrate-settings' || $my_current_screen->base == 'toplevel_page_wordherd-migrate') {
			wp_enqueue_script( $this->content_moover, plugin_dir_url( __FILE__ ) . 'js/content-moover-admin.js', array( 'jquery' ), $this->version, false );
		}
	}

}
