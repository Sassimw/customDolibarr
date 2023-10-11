<?php
/* Copyright (C) 2017-2021	Florian Charlaix		<fcharlaix@easya.solutions>
 * Copyright (C) 2023		Maximilien Rozniecki	<mrozniecki@easya.solutions>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.
 */

/**
 * 		\defgroup   openidconnect     Module OpenID Connect Authentication
 *      \brief      OpenID Connect Authentication
 *      \file       /core/modules/modOpenIDConnect.class.php
 *      \ingroup    openidconnect
 *      \brief      Description and activation file for module OpenID Connect Authentication
 */
include_once DOL_DOCUMENT_ROOT . "/core/modules/DolibarrModules.class.php";

/**
 * 		\class      modOpenIDConnect
 *      \brief      Description and activation class for module OpenID Connect Authentication
 */
class modOpenIDConnect extends DolibarrModules
{
   /**
    *	Constructor
    *
    *	@param	DoliDB	$db		Database handler
    */
	function __construct($db)
	{
		global $langs, $conf;

		$langs->load('opendsi@openidconnect');
		$this->db = $db;

		// Id for module (must be unique).
		// Use here a free id (See in Home -> System information -> Dolibarr for list of used modules id).
		$this->numero = 163071;
		// Key text used to identify module (for permissions, menus, etc...)
		$this->rights_class = 'openidconnect';

        $family = (!empty($conf->global->EASYA_VERSION) ? 'easya' : 'opendsi');
        // Family can be 'crm','financial','hr','projects','products','ecm','technic','interface','other'
        // It is used to group modules by family in module setup page
        $this->family = $family;
        // Gives the possibility to the module, to provide his own family info and position of this family (Overwrite $this->family and $this->module_position. Avoid this)
        $this->familyinfo = array($family => array('position' => '001', 'label' => $langs->trans($family."Family")));

		// Module position in the family
		$this->module_position = 3;
		// Module label (no space allowed), used if translation string 'ModuleXXXName' not found (where XXX is value of numeric property 'numero' of module)
		$this->name = preg_replace('/^mod/i','',get_class($this));
		// Module description, used if translation string 'ModuleXXXDesc' not found (where XXX is value of numeric property 'numero' of module)
		$this->description = "Module for manage OpenID Connect Authentication";
		//$this->descriptionlong = "A very lon description. Can be a full HTML content";
		$this->editor_name = 'Open-DSI';
		$this->editor_url = 'https://easya.solutions';
		$this->editor_email     = 'support@open-dsi.fr';
		// Possible values for version are: 'development', 'experimental', 'dolibarr' or version
		$this->version = '1.0.2';
		// Key used in llx_const table to save module status enabled/disabled (where MYMODULE is value of property name of module in uppercase)
		$this->const_name = 'MAIN_MODULE_'.strtoupper($this->name);
		// Can be enabled / disabled only in the main company with superadmin account
		$this->core_enabled = 1;
		// Name of image file used for this module.
		// If file is in theme/yourtheme/img directory under name object_pictovalue.png, use this->picto='pictovalue'
		// If file is in module/img directory, use this->picto=DOL_URL_ROOT.'/module/img/file.png'
		$this->picto='opendsi_big@easya';

		// Defined all module parts (triggers, login, substitutions, menus, css, etc...)
		$this->module_parts = array(
			
			'js' => array(
				'/openidconnect/js/jquery.cookie.js',
				'/openidconnect/js/openidconnect.js'
			),
			'login' => 1
		);

		// Data directories to create when module is enabled.
		$this->dirs = array();

		// Config pages. Put here list of php page names stored in admmin directory used to setup module.
		$this->config_page_url = array();

		// Dependencies
		$this->hidden = false;			// A condition to hide module
		$this->depends = array();					// List of modules id that must be enabled if this module is enabled
		$this->requiredby = array();				// List of modules id to disable if this one is disabled
		$this->phpmin = array(5,6);					// Minimum version of PHP required by module
		$this->need_dolibarr_version = array(8,0);	// Minimum version of Dolibarr required by module
		$this->langfiles = array("openidconnect@openidconnect", "opendsi@openidconnect");
		$langs->load('openidconnect@openidconnect');
		$this->warnings_activation = array();                     // Warning to show when we activate module. array('always'='text') or array('FR'='textfr','ES'='textes'...)
		$this->warnings_activation_ext = array();                 // Warning to show when we activate an external module. array('always'='text') or array('FR'='textfr','ES'='textes'...)


		$printColVisible = json_encode(array(0,3,4));
		$colHidden = json_encode(array(1,2,4));

		// Constants
		// List of particular constants to add when module is enabled
        // List of particular constants to add when module is enabled (key, 'chaine', value, desc, visible, 'current' or 'allentities', deleteonunactive)
		$this->const=array(
            0 => array('MAIN_AUTHENTICATION_OPENID_URL','chaine','','OpenID Connect URL',1,'allentities',0),
			1 => array('MAIN_LOGOUT_GOTO_URL','chaine','','Identity Provider logout URL',1,'allentities',0),
			2 => array('MAIN_AUTHENTICATION_OIDC_CLIENT_ID','chaine','My-Super-Awesome-Client-ID-1234','OpenID Connect Client ID',0,'allentities',0),
			3 => array('MAIN_AUTHENTICATION_OIDC_CLIENT_SECRET','chaine','My-Very-Hidden-Client-Secret-1234','OpenID Connect Client Secret',0,'allentities',0),
			4 => array('MAIN_AUTHENTICATION_OIDC_TOKEN_URL','chaine','','OpenID Connect token URL',1,'allentities',0),
			5 => array('MAIN_AUTHENTICATION_OIDC_USERINFO_URL','chaine','','OpenID Connect userinfo URL',1,'allentities',0),
			6 => array('MAIN_AUTHENTICATION_OIDC_REDIRECT_URL','chaine','','OpenID Connect redirect URL',1,'allentities',0),
			7 => array('MAIN_AUTHENTICATION_OIDC_LOGIN_CLAIM','chaine','sub','OpenID Connect login claim',1,'allentities',0)
		);

		// Tabs
		$this->tabs = array();

		// Boxes
		$this->boxes = array();			// List of boxes
		$r=0;

		// Permissions
		$this->rights = array();		// Permission array used by this module
		$r=0;

                // Main menu entries
		$this->menu = array();			// List of menus to add
		$r=0;

  	}

	/**
     *		Function called when module is enabled.
     *		The init function add constants, boxes, permissions and menus (defined in constructor) into Dolibarr database.
     *		It also creates data directories.
	 *
	 *      @param string $options Options when enabling module ('', 'newboxdefonly', 'noboxes')
	 *                             'noboxes' = Do not insert boxes
	 *                             'newboxdefonly' = For boxes, insert def of boxes only and not boxes activation
	 *      @param  int   $force_entity	Force current entity
	 *      @return int 1 if OK, 0 if KO
     */
  	function init($options = '', $force_entity = null)
	{
		$sql = array();

		return $this->_init($sql, $options);
	}

	/**
	 *		Function called when module is disabled.
 	 *      Remove from database constants, boxes and permissions from Dolibarr database.
 	 *		Data directories are not deleted.
	 *
	 *      @param string $options Options when enabling module ('', 'noboxes')
	 *      @return int 1 if OK, 0 if KO
 	 */
	function remove($options = '')
	{
		$sql = array();

		return $this->_remove($sql, $options);
	}
}
