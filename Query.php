<?php
class QueryPlugin extends MantisPlugin {

	/** Error constants */
	const ERROR_DELETE_QUERY = "error_delete_query";
	const ERROR_QUERY_EMPTY_TITLE = "error_query_empty_title";
	const ERROR_QUERY_EMPTY_DESC = "error_query_empty_desc";
	const ERROR_SCHEDULE_EMPTY_TITLE = "error_schedule_empty_title";
	const ERROR_SCHEDULE_EMPTY_TARGET = "error_schedule_empty_target";
	const ERROR_SCHEDULE_EMPTY_QUERY = "error_schedule_empty_query";
	const ERROR_QUERY_NAME_NOT_UNIQUE = "error_query_name_not_unique";
	const ERROR_QUERY_NOT_VALID = "error_query_not_valid";
	const ERROR_FILTER_NOT_POSSIBLE = "error_filter_not_possible";
	const ERROR_SCHEDULE_NAME_NOT_UNIQUE = "error_schedule_name_not_unique";

	function register() {
		$this->name        = lang_get( 'plugin_query_name' );
		$this->description = lang_get( 'plugin_query_description' );
		$this->version     = '2.19';
		$this->requires    = array('MantisCore'       => '2.0.0',);
		$this->author      = 'Cas Nuy';
		$this->contact     = 'Cas-at-nuy.info';
		$this->url         = 'http://www.nuy.info';
		$this->page			= 'config';
	}

 	function config() {
		return array(
			'manage_threshold'	=> ADMINISTRATOR,
			'execute_threshold'	=> DEVELOPER,
			'download_location' => 'c:/temp/' ,
			'separator'			=> ',',
			'delete_file'		=> ON,
			'build_sql'			=> ON,
			'from_address' 		=> 'me@mydomain.com' ,
			'log_loc' 			=> '/var/log/' ,
			);
	}

	function init() {
		plugin_event_hook( 'EVENT_MENU_MANAGE',		'query_menu' );
		plugin_event_hook( 'EVENT_MENU_MAIN',		'query_menu_user' );
	}

	function errors() {
		return [
			self::ERROR_DELETE_QUERY => plugin_lang_get( self::ERROR_DELETE_QUERY ),
			self::ERROR_QUERY_EMPTY_TITLE => plugin_lang_get( self::ERROR_QUERY_EMPTY_TITLE ),
			self::ERROR_QUERY_EMPTY_DESC => plugin_lang_get( self::ERROR_QUERY_EMPTY_DESC ),
			self::ERROR_SCHEDULE_EMPTY_TITLE => plugin_lang_get( self::ERROR_SCHEDULE_EMPTY_TITLE ),
			self::ERROR_SCHEDULE_EMPTY_TARGET => plugin_lang_get( self::ERROR_SCHEDULE_EMPTY_TARGET ),
			self::ERROR_SCHEDULE_EMPTY_QUERY => plugin_lang_get( self::ERROR_SCHEDULE_EMPTY_QUERY ),
			self::ERROR_QUERY_NAME_NOT_UNIQUE => plugin_lang_get( self::ERROR_QUERY_NAME_NOT_UNIQUE ),
			self::ERROR_QUERY_NOT_VALID => plugin_lang_get( self::ERROR_QUERY_NOT_VALID ),
			self::ERROR_FILTER_NOT_POSSIBLE => plugin_lang_get( self::ERROR_FILTER_NOT_POSSIBLE  ),
			self::ERROR_SCHEDULE_NAME_NOT_UNIQUE => plugin_lang_get( self::ERROR_SCHEDULE_NAME_NOT_UNIQUE ),
		];
	}

 	function query_menu() {
				return array( '<a href="' . plugin_page( 'manage_query' ) . '">' . lang_get( 'plugin_query_manage' ) .  '</a>', );
	}


 	function query_menu_user() {
		if ( access_has_project_level( plugin_config_get( 'execute_threshold' ) )  ){
			$links = array();
			$links[] = array('title' => lang_get("plugin_user_query","Query"),
			'url' => plugin_page("user_query", true),
			'icon' => 'fa-user-secret');
			return $links;
		}
	}


	function schema() {
		return array(
			array( 'CreateTableSQL', array( plugin_table( 'definitions' ), "
						query_id 			I       UNSIGNED NOTNULL PRIMARY AUTOINCREMENT,
						query_name			C(100)	NOTNULL DEFAULT \" '' \" ,
						query_desc			C(200)	NOTNULL DEFAULT \" '' \"  ,
						query_type			C(1)	NOTNULL DEFAULT \" '' \"  ,
						query_script		XL		NOTNULL DEFAULT \" '' \" ,
						query_tables		XL		NOTNULL DEFAULT \" '' \" ,
						query_joins			XL		NOTNULL DEFAULT \" '' \" ,
						query_fields		XL		NOTNULL DEFAULT \" '' \" ,
						query_filter		XL		NOTNULL DEFAULT \" '' \" ,
						query_order			XL		NOTNULL DEFAULT \" '' \" ,
						query_group			XL		NOTNULL DEFAULT \" '' \" ,
						query_sql			XL		NOTNULL DEFAULT \" '' \" ,
						query_lvl			C(1)	NOTNULL DEFAULT \" '' \"
						" ) ),
			array( 'CreateTableSQL', array( plugin_table( 'schedule' ), "
						schedule_id 		I       UNSIGNED NOTNULL PRIMARY AUTOINCREMENT,
						schedule_desc		C(200)	NOTNULL DEFAULT \" '' \"  ,
						query_id			I		NOTNULL ,
						schedule_filter		XL		NOTNULL DEFAULT \" '' \" ,
						target				XL		NOTNULL DEFAULT \" '' \" ,
						frequency			C(1)    NOTNULL DEFAULT \" '' \"
						" ) ),
			# Make columns nullable
			array( 'AlterColumnSQL', array( plugin_table( 'definitions' ), "
						query_script		XL		DEFAULT \" '' \" ,
						query_tables		XL		DEFAULT \" '' \" ,
						query_joins			XL		DEFAULT \" '' \" ,
						query_fields		XL		DEFAULT \" '' \" ,
						query_filter		XL		DEFAULT \" '' \" ,
						query_order			XL		DEFAULT \" '' \" ,
						query_group			XL		DEFAULT \" '' \" ,
						query_sql			XL		DEFAULT \" '' \" 
						" ) ),
			# drop field
			array( 'DropColumnSQL', array( plugin_table( 'definitions' ), 'query_lvl' ) ),

			);			
	}

}