<?php

require_once DOL_DOCUMENT_ROOT.'/core/class/extrafields.class.php';
require_once DOL_DOCUMENT_ROOT.'/projet/class/project.class.php';
require_once DOL_DOCUMENT_ROOT.'/core/class/html.formprojet.class.php';

/**
 * Class Actionsthirdpartycodegenerator
 */
class Actionsthirdpartycodegenerator
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

	public function doActions($parameters, &$object, &$action, $hookmanager)
	{
		global $langs, $db, $user, $conf;

		$langs->loadLangs(array('categories'));
        $langs->load('thirdpartycodegenerator@thirdpartycodegenerator');

		$confirm = GETPOST('confirm', 'alpha');

		
		if(GETPOST('confirmmassaction') && GETPOST('massaction') == 'generate_customer_code' && $user->rights->societe->creer) {

			require_once DOL_DOCUMENT_ROOT.'/societe/class/societe.class.php';
			$societe = new societe($db);

			$u = 0;

			$arraydata = $_POST['toselect'];

			foreach ($arraydata as $key => $objid) {

				$res = $societe->fetch($objid);

				$to_modify = false;

				if($res && empty($societe->code_client) && $societe->client) {
					$societe->code_client = 'auto';
					$to_modify = true;
				}
				if($res && empty($societe->code_fournisseur) && $societe->fournisseur) {
					$societe->code_fournisseur = 'auto';
					$to_modify = true;
				}

				if($to_modify) {
					$res = $societe->update($objid, $user, $call_trigger = 0, $allowmodcodeclient = 1, $allowmodcodefournisseur = 1);

					if($res) {
						$u++;
					}
				}
			}

			setEventMessages($langs->trans("RecordsModified", $u), null, 'mesgs');
			
			header('Location: '.$_SERVER['PHP_SELF']);
    		exit();

		}
	}

	public function addMoreMassActions($parameters=false)
	{
		global $conf, $user, $langs;

		$currentpage = array_pop(explode(':', $parameters['context']));

		if (empty($user->rights->societe->creer)) return 0;

		if($currentpage == 'thirdpartylist') {

			$langs->load('thirdpartycodegenerator@thirdpartycodegenerator');

			$massactions = '';
			$label = img_picto('', 'user', 'class="pictofixedwidth"').' '.$langs->trans('GenerateCustomerCode');
			$massactions .= '<option value="generate_customer_code" data-html="'.dol_escape_htmltag($label).'">'.$label.'</option>';
			$this->resprints = $massactions;

		}

		return 0;
	}
}
