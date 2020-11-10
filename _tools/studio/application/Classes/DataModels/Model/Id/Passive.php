<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetStudio;

use Jet\Form_Field;

class DataModels_Model_Id_Passive extends DataModels_Model_Id_Abstract {

	/**
	 * @return array
	 */
	public function getOptionsList()
	{
		return [];
	}

	/**
	 *
	 * @return Form_Field[]
	 */
	public function getOptionsFormFields()
	{
		return [];
	}
}