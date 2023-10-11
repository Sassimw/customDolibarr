<?php

/**
 * Class Actionsshowholidayinagenda
 */
class Actionsshowholidayinagenda
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

	public function getCalendarEvents($parameters, &$object, &$action, $hookmanager)
	{
		if($parameters['currentcontext'] == 'agenda'){
			?>
			<style>.family_holiday:not(.hideholiday){display: block !important;}</style>
			<script>
				$(document).ready(function() {

					if($('#check_holiday').length > 0) {
						$('#check_holiday').click();

						$('#check_holiday').change(function(){
							$('.family_holiday').addClass('hideholiday');
						});
					}
				});
			</script>
			<?php
		}
	}

}
