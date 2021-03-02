<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetStudio;

use Jet\DataModel_Definition_Property_String as Jet_DataModel_Definition_Property_String;
use Jet\Form_Field;
use Jet\Form_Field_Input;
use Jet\Form_Field_Int;

/**
 *
 */
class DataModel_Definition_Property_String extends Jet_DataModel_Definition_Property_String implements DataModel_Definition_Property_Interface
{
	use DataModel_Definition_Property_Trait;

	/**
	 * @param Form_Field[] &$fields
	 */
	public function getEditFormCustomFields( array &$fields ): void
	{
		$max_len_field = new Form_Field_Int( 'max_len', 'Maximal string length:', $this->getMaxLen() );
		$max_len_field->setMinValue( 1 );
		$max_len_field->setIsRequired( true );
		$max_len_field->setErrorMessages( [
			Form_Field_Int::ERROR_CODE_EMPTY => 'Please enter maximal string length',
			Form_Field_Int::ERROR_CODE_OUT_OF_RANGE => 'Minimal value is 1, maximal is unlimited'
		] );
		$max_len_field->setCatcher( function( $value ) {
			$this->max_len = $value;
		} );


		$default_value_field = new Form_Field_Input( 'default_value', 'Default value', $this->getDefaultValue() );
		$default_value_field->setCatcher( function( $value ) {
			$this->default_value = $value;
		} );


		$fields[$max_len_field->getName()] = $max_len_field;
		$fields[$default_value_field->getName()] = $default_value_field;
	}

	/**
	 *
	 */
	public function showEditFormFields(): void
	{
		$form = $this->getEditForm();

		echo $form->field( 'max_len' );
		echo $form->field( 'default_value' );
	}

	/**
	 *
	 * @param ClassCreator_Class $class
	 *
	 * @return ClassCreator_Class_Property
	 */
	public function createClassProperty( ClassCreator_Class $class ): ClassCreator_Class_Property
	{
		$attributes = [];

		$attributes[] = [
			'DataModel_Definition',
			'max_len',
			$this->max_len
		];

		if( $this->default_value ) {
			$attributes[] = [
				'DataModel_Definition',
				'default_value',
				$this->default_value
			];
		}


		$property = $this->createClassProperty_main( $class, 'string', 'DataModel::TYPE_STRING', $attributes );

		return $property;
	}

	/**
	 * @param ClassCreator_Class $class
	 *
	 * @return array
	 */
	public function createClassMethods( ClassCreator_Class $class ): array
	{

		$s_g_method_name = $this->getSetterGetterMethodName();

		$setter = $class->createMethod( 'set' . $s_g_method_name );
		$setter->addParameter( 'value' )
			->setType( 'string' );
		$setter->line( 1, '$this->' . $this->getName() . ' = $value;' );


		$getter = $class->createMethod( 'get' . $s_g_method_name );
		$getter->setReturnType( 'string' );
		$getter->line( 1, 'return $this->' . $this->getName() . ';' );

		return [
			'set' . $s_g_method_name,
			'get' . $s_g_method_name
		];
	}

}