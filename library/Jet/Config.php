<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

	/**
	 * Available annotation:
	 *      Config:
	 * @JetConfig:name = 'configuration_name'
	 *
	 *
	 *      Config Property Definition:
	 *          /**
	 *           * @JetConfig:type = Config::TYPE_*,
	 *           *
	 *           * @JetConfig:description = 'Some description ...',
	 *           * @JetConfig:is_required = true
	 *           * @JetConfig:default_value = 'some default value'
	 *           *
	 *           *
	 *           * @JetConfig:form_field_type = Form::TYPE_*
	 *           *     - (optional, default: autodetect)
	 *           * @JetConfig:form_field_label = 'Some form filed label:'
	 *           * @JetConfig:form_field_options = ['option1' => 'Option 1', 'option2' => 'Option 1', 'option3'=>'Option 3' ]
	 *           *      - optional
	 *           * @JetConfig:form_field_error_messages = ['error_code' => 'Message' ]
	 *           * @JetConfig:form_field_get_select_options_callback = callable
	 *           *     - optional
	 *           *
	 *           * @var string          //some PHP type ...
	 *           * /
	 *          protected $some_property;
	 *
	 */


/**
 *
 */
abstract class Config extends BaseObject
{

	const TYPE_STRING = 'String';
	const TYPE_BOOL = 'Bool';
	const TYPE_INT = 'Int';
	const TYPE_FLOAT = 'Float';
	const TYPE_ARRAY = 'Array';
	const TYPE_SECTION = 'Section';
	const TYPE_SECTIONS = 'Sections';


	/**
	 * Ignore non-existent config file and non-existent config section. Usable for installer or setup.
	 *
	 * @var string
	 */
	protected static $config_dir_path;

	/**
	 * @var bool
	 */
	protected static $be_tolerant = false;

	/**
	 *
	 * @var array
	 */
	protected static $_config_file_data = [];

	/**
	 *
	 * @var string
	 */
	protected $_config_file_path = '';

	/**
	 * @var Config_Definition_Config
	 */
	private $definition;

	/**
	 * @var Config_Definition_Property[]
	 */
	private $properties_definition;


	/**
	 * @return string
	 */
	public static function getConfigDirPath()
	{
		if( !static::$config_dir_path ) {
			static::$config_dir_path = SysConf_PATH::CONFIG();
		}

		return static::$config_dir_path;
	}

	/**
	 * @param string $path
	 */
	public static function setConfigDirPath( $path )
	{
		static::$config_dir_path = $path;
	}

	/**
	 * @return bool
	 */
	public static function beTolerant()
	{
		return self::$be_tolerant;
	}

	/**
	 * @param bool $be_tolerant
	 */
	public static function setBeTolerant( $be_tolerant )
	{
		self::$be_tolerant = $be_tolerant;
	}


	/**
	 * @param array|null $data
	 */
	public function __construct( array $data=null )
	{
		if($data===null) {
			$data = $this->readConfigFileData();
		}

		$this->setData( $data );
	}


	/**
	 *
	 * @param array $data
	 *
	 * @throws Config_Exception
	 */
	public function setData( array $data )
	{

		foreach( $this->getPropertiesDefinition() as $property_name => $property_definition ) {

			if( !array_key_exists( $property_name, $data ) ) {

				if(
					$property_definition->getIsRequired() &&
					!static::beTolerant()
				) {

					throw new Config_Exception(
						'Configuration property '.get_class( $this ).'::'.$property_name.' is required by definition, but value is missing!',
						Config_Exception::CODE_CONFIG_CHECK_ERROR
					);
				}

				$this->{$property_name} = $property_definition->getDefaultValue( $this );

				continue;
			}

			$this->{$property_name} = $property_definition->prepareValue( $data[$property_name], $this );
		}
	}

	/**
	 * @return Config_Definition_Config
	 */
	public function getDefinition()
	{
		if( !$this->definition ) {
			$this->definition = Config_Definition::getMainConfigDefinition( get_called_class() );
		}

		return $this->definition;
	}

	/**
	 *
	 * @return Config_Definition_Property[]
	 */
	public function getPropertiesDefinition()
	{
		if( $this->properties_definition!==null ) {
			return $this->properties_definition;
		}

		$definition = $this->getDefinition()->getPropertiesDefinition();

		foreach( $definition as $property ) {
			$property->setConfiguration( $this );
		}

		$this->properties_definition = $definition;

		return $definition;
	}


	/**
	 * @param string $form_name
	 *
	 * @return Form
	 */
	public function getCommonForm( $form_name = '' )
	{
		$properties_list = $this->getCommonFormPropertiesList();

		if( !$form_name ) {
			$form_name = str_replace( '\\', '_', get_class( $this ) );
		}

		return $this->getForm( $form_name, $properties_list );
	}

	/**
	 * @return array
	 */
	public function getCommonFormPropertiesList()
	{
		$definition = $this->getPropertiesDefinition();
		$properties_list = [];

		foreach( $definition as $property_name => $property_definition ) {
			if( $property_definition->getFormFieldType()===false ) {
				continue;
			}

			$properties_list[] = $property_name;
		}

		return $properties_list;

	}

	/**
	 *
	 * @param string $form_name
	 * @param array  $properties_list
	 *
	 * @throws DataModel_Exception
	 * @return Form
	 */
	protected function getForm( $form_name, array $properties_list )
	{
		$properties_definition = $this->getPropertiesDefinition();

		$form_fields = [];

		foreach( $properties_list as $property_name ) {

			$property_definition = $properties_definition[$property_name];
			$property = &$this->{$property_name};


			if( ( $field_creator_method_name = $property_definition->getFormFieldCreatorMethodName() ) ) {
				$created_field = $this->{$field_creator_method_name}( $property_definition );
			} else {
				$created_field = $property_definition->createFormField( $property );
			}

			if( !$created_field ) {
				continue;
			}


			if(is_array($created_field)) {

				foreach( $created_field as $field ) {
					$form_fields[] = $field;
				}

			} else {
				$created_field->setCatcher(
					function( $value ) use ( $property_definition, &$property ) {
						$property_definition->catchFormField( $this, $property, $value );
					}
				);

				$form_fields[] = $created_field;

			}



		}

		return new Form( $form_name, $form_fields );

	}

	/**
	 * @param Form  $form
	 *
	 * @param array|null $data
	 * @param bool  $force_catch
	 *
	 * @return bool;
	 */
	public function catchForm( Form $form, $data = null, $force_catch = false )
	{

		if(
			!$form->catchInput( $data, $force_catch ) ||
			!$form->validate()
		) {
			return false;
		}

		return $form->catchData();

	}

	/**
	 *
	 * @return array
	 */
	public function toArray()
	{
		$definition = $this->getPropertiesDefinition();

		$result = [];

		foreach( $definition as $name => $def ) {
			if( is_array($this->{$name}) ) {
				$result[$name] = [];

				foreach( $this->{$name} as $k=>$v ) {
					if(is_object($v)) {
						/**
						 * @var Config $v
						 */
						$result[$name][$k] = $v->toArray();
					} else {
						$result[$name][$k] = $v;
					}
				}

			} else {
				if( is_object( $this->{$name} ) ) {
					/**
					 * @var Config $prop
					 */
					$prop = $this->{$name};
					$result[$name] = $prop->toArray();
				} else {
					$result[$name] = $this->{$name};
				}

			}
		}

		return $result;
	}


	/**
	 * @return string
	 */
	public function getConfigFilePath()
	{
		if(!$this->_config_file_path) {
			$this->_config_file_path = static::getConfigDirPath().$this->getDefinition()->getName().'.php';
		}

		return $this->_config_file_path;
	}

	/**
	 * @param string $config_file_path
	 */
	public function setConfigFilePath( $config_file_path )
	{
		$this->_config_file_path = $config_file_path;
	}


	/**
	 *
	 * @throws Config_Exception
	 *
	 * @return array
	 */
	public function readConfigFileData()
	{
		$config_file_path = $this->getConfigFilePath();

		if(!isset(Config::$_config_file_data[$config_file_path])) {

			if( !IO_File::isReadable( $config_file_path ) ) {
				if( static::beTolerant() ) {
					Config::$_config_file_data[$config_file_path] = [];

					return Config::$_config_file_data[$config_file_path];
				}

				throw new Config_Exception(
					'Config file \''.$config_file_path.'\' does not exist or is not readable',
					Config_Exception::CODE_CONFIG_FILE_IS_NOT_READABLE
				);

			}

			/** @noinspection PhpIncludeInspection */
			$data = require $config_file_path;
			if( !is_array( $data ) ) {
				throw new Config_Exception(
					'Config file \''.$config_file_path.'\' does not contain PHP array. Example: <?php return [\'option\' => \'value\']; ',
					Config_Exception::CODE_CONFIG_FILE_IS_NOT_VALID
				);

			}

			Config::$_config_file_data[$config_file_path] = $data;
		}

		return Config::$_config_file_data[$config_file_path];
	}


	/**
	 *
	 */
	public function writeConfigFile()
	{
		$config_file_path = $this->getConfigFilePath();

		$config_data = $this->toArray();

		$config_data = '<?php'.PHP_EOL.'return '.(new Data_Array( $config_data ))->export();

		IO_File::write( $config_file_path, $config_data );

		if(function_exists('opcache_reset')) {
			opcache_reset();
		}

		Config::$_config_file_data = [];
	}

}