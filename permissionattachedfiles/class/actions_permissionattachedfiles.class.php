<?php

/**
 * Class Actionspermissionattachedfiles
 */
class Actionspermissionattachedfiles
{
	/**
	 * @var array Hook results. Propagated to $hookmanager->resArray for later reuse
	 */
	public $results = array();

	/**
	 * @var string String displayed by executeHook() immediately after return
	 */
	public $resprints;

	/**
	 * @var array Errors
	 */
	public $errors = array();

	/**
	 * Constructor
	 */
	public function __construct()
	{
	}

	public function downloadDocument($parameters, &$object, &$action, $hookmanager)
	{
		global $conf, $langs, $user, $attachment;

		if(!$conf->permissionattachedfiles->enabled) return 0;

		$usercanview = $user->rights->permissionattachedfiles->view;
		
		if(!$usercanview && !isset($_GET["hashp"])) {
			$langs->loadLangs(array('errors', 'permissionattachedfiles@permissionattachedfiles'));

			$msgtoshow .= '<b>';
			$msgtoshow .= $langs->trans('permissionattachedfiles').' : <br><br>';
			$msgtoshow .= '</b>';
			$msgtoshow .= $langs->trans("ErrorForbidden");

			accessforbidden($msgtoshow);
		}
	}

}
