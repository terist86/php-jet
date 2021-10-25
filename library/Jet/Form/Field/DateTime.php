<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

use DateTime;

/**
 *
 */
class Form_Field_DateTime extends Form_Field_Input
{
	/**
	 * @var string
	 */
	protected static string $default_renderer_script = 'field';

	/**
	 * @var string
	 */
	protected static string $default_row_start_renderer_script = 'field/row/start';

	/**
	 * @var string
	 */
	protected static string $default_row_end_renderer_script = 'field/row/end';

	/**
	 * @var string
	 */
	protected static string $default_input_container_start_renderer_script = 'field/input/container/start';

	/**
	 * @var string
	 */
	protected static string $default_input_container_end_renderer_script = 'field/input/container/end';

	/**
	 * @var string
	 */
	protected static string $default_error_renderer = 'field/error';

	/**
	 * @var string
	 */
	protected static string $default_label_renderer = 'field/label';

	/**
	 * @var string string
	 */
	protected static string $default_input_renderer = 'field/input/date-time';


	/**
	 * @var string
	 */
	protected string $_type = Form::TYPE_DATE_TIME;

	/**
	 * @var array
	 */
	protected array $error_messages = [
		self::ERROR_CODE_EMPTY          => '',
		self::ERROR_CODE_INVALID_FORMAT => '',
	];

	/**
	 * @param Data_Array $data
	 */
	public function catchInput( Data_Array $data ): void
	{
		parent::catchInput( $data );

		if( $this->_value === '' ) {
			$this->_value = null;
		}

	}

	/**
	 * validate value
	 *
	 * @return bool
	 */
	public function validate(): bool
	{

		if( $this->_value ) {

			$check = DateTime::createFromFormat( 'Y-m-d\TH:i', $this->_value );
			$check_c = DateTime::createFromFormat( 'Y-m-d\TH:i:s', $this->_value );

			if( !$check && !$check_c ) {
				$this->setError( self::ERROR_CODE_INVALID_FORMAT );

				return false;
			}
		} else {
			if( $this->is_required ) {
				$this->setError( self::ERROR_CODE_EMPTY );
				return false;
			}
		}

		$this->setIsValid();

		return true;
	}


	/**
	 * @return array
	 */
	public function getRequiredErrorCodes(): array
	{
		$codes = [];

		if( $this->is_required ) {
			$codes[] = self::ERROR_CODE_EMPTY;
		}
		$codes[] = self::ERROR_CODE_INVALID_FORMAT;

		return $codes;
	}

}