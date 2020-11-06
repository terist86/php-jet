<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplicationModule\ManageAccess\Administrators\Users;

use JetApplication\Auth_Administrator_User as User;
use JetApplication\Auth_Administrator_Role as Role;

use Jet\Data_Listing;
use Jet\Data_Listing_Filter_search;
use Jet\DataModel_Fetch_Instances;
use Jet\Form;
use Jet\Form_Field_Select;
use Jet\Http_Request;
use Jet\Tr;

/**
 *
 */
class Listing extends Data_Listing {

	use Data_Listing_Filter_search;

	/**
	 * @var array
	 */
	protected $grid_columns = [
		'_edit_'     => [
			'title'         => '',
			'disallow_sort' => true
		],
		'id'         => ['title' => 'ID'],
		'username'   => ['title' => 'Username'],
		'first_name' => ['title' => 'First name'],
		'surname'    => ['title' => 'Surname'],
	];

	/**
	 * @var string[]
	 */
	protected $filters = [
		'search',
		'role'
	];

	/**
	 * @var int
	 */
	protected $role = 0;

	/**
	 * @return DataModel_Fetch_Instances
	 */
	protected function getList()
	{
		return User::getList();
	}

	/**
	 *
	 */
	protected function filter_search_getWhere()
	{
		if(!$this->search) {
			return;
		}

		$search = '%'.$this->search.'%';
		$this->filter_addWhere([
			'username *'   => $search,
			'OR',
			'first_name *' => $search,
			'OR',
			'surname *'    => $search,
			'OR',
			'email *'      => $search,
		]);

	}




	/**
	 *
	 */
	protected function filter_role_catchGetParams()
	{
		$this->role = Http_Request::GET()->getString('role');
		$this->setGetParam('role', $this->role);
	}

	/**
	 * @param Form $form
	 */
	public function filter_role_catchForm( Form $form )
	{
		$value = $form->field('role')->getValue();

		$this->role = $value;
		$this->setGetParam('role', $value);
	}

	/**
	 * @param Form $form
	 */
	protected function filter_role_getForm( Form $form )
	{
		$field = new Form_Field_Select('role', 'Role:', $this->role);
		$field->setErrorMessages([
			Form_Field_Select::ERROR_CODE_INVALID_VALUE => ' '
		]);
		$options = [0=>Tr::_('- all -')];

		foreach(Role::getList() as $role) {
			$options[$role->getId()] = $role->getName();
		}
		$field->setSelectOptions( $options );


		$form->addField($field);
	}

	/**
	 *
	 */
	protected function filter_role_getWhere()
	{
		if($this->role) {
			$this->filter_addWhere([
				'role.id' => $this->role,
			]);
		}
	}

}