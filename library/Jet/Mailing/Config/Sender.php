<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

/**
 *
 */
#[Config_Definition(name: 'mailing')]
class Mailing_Config_Sender extends Config_Section
{

	/**
	 *
	 * @var string
	 */
	#[Config_Definition(type: Config::TYPE_STRING)]
	#[Config_Definition(is_required: true)]
	#[Config_Definition(form_field_label: 'E-mail:')]
	#[Config_Definition(form_field_type: Form::TYPE_EMAIL)]
	#[Config_Definition(form_field_error_messages: [Form_Field_Email::ERROR_CODE_EMPTY          => 'Please enter valid email address',
	                                                Form_Field_Email::ERROR_CODE_INVALID_FORMAT => 'Please enter valid email address'
	])]
	protected string $email = '';

	/**
	 *
	 * @var string
	 */
	#[Config_Definition(form_field_label: 'Name:')]
	#[Config_Definition(type: Config::TYPE_STRING)]
	#[Config_Definition(is_required: false)]
	protected string $name = '';

	/**
	 * @return string
	 */
	public function getEmail(): string
	{
		return $this->email;
	}

	/**
	 * @param string $email
	 */
	public function setEmail( string $email ): void
	{
		$this->email = $email;
	}

	/**
	 * @return string
	 */
	public function getName(): string
	{
		return $this->name;
	}

	/**
	 * @param string $name
	 */
	public function setName( string $name ): void
	{
		$this->name = $name;
	}


}