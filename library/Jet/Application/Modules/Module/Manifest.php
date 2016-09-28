<?php
/**
 *
 *
 *
 * Class that contains basic information about the module
 *
 * @see Application_Modules_Module_Abstract
 *
 * Each module has manifest file (~/application/modules/Module/manifest.php), that contains these specifications:
 *  - label (required)
 *  - API_version (required)
 *  - type (required)
 *  - description (optional)
 *  - require (optional)
 *  - signals_callbacks (optional)
 *
 * See class variables description for more details
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Application
 * @subpackage Application_Modules
 */
namespace Jet;

class Application_Modules_Module_Manifest extends BaseObject implements \JsonSerializable {
	const MANIFEST_FILE_NAME = 'manifest.php';

	const MODULE_TYPE_GENERAL = 'general';
	const MODULE_TYPE_AUTH_CONTROLLER = 'auth_controller';
	const MODULE_TYPE_SYSTEM = 'system';

	/**
	 * Format:
	 * <code>
	 * array(
	 *      'type_key' => 'Type description'
	 * )
	 * </code>
	 *
	 * @var array
	 */
	protected static $module_types_list = [
		self::MODULE_TYPE_GENERAL => 'General module',
		self::MODULE_TYPE_AUTH_CONTROLLER => 'Authentication and Authorization Controller module',
		self::MODULE_TYPE_SYSTEM => 'System module',
	];

	/**
	*
	* @var string
	*/
	protected $name = '';

	/**
	 * @var string
	 */
	protected $vendor = '';

	/**
	 * @var string
	 */
	protected $version = '';

	//--------------------------------------------------------------------------

	/**
	 * Manifest value
	 *
	 * Human readable module name
	 *
	 * @var string
	 */
	protected $label = '';

	/**
	 * Manifest value
	 *
	 * Module Description
	 *
	 * @var string
	 */
	protected $description = '';

	/**
	 * Manifest value
	 *
	 * Required API version
	 *
	 * @var int
	 */
	protected $API_version = 201208;

	/**
	 * Manifest value
	 *
	 * Module type - one of Application_Modules_Module_Manifest::MODULE_TYPE_
	 *
	 * @var string[]
	 */
	protected $types = [self::MODULE_TYPE_GENERAL];

	/**
	 * Manifest value
	 *
	 * List of modules (module names) that the module requires
	 *
	 * @var string[]
	 */
	protected $require = [];

	/**
	 * Manifest value
	 *
	 * Module callbacks for signals.
	 * 
	 * array(
	 *	  'signal1' => 'moduleMethod1',  // route signal 'signal1' to method 'moduleMethod1'
	 *	  'signal2' => array(            // route signal 'signal2' to methods 'method2' and 'method3'
	 *                       'method2',
	 *                       'method3'
	 *                     )
	 * )
	 *
	 * @var array 
	 */
	protected $signals_callbacks = [];


	//--------------------------------------------------------------------------

	/**
	* Module root directory
	*
	* @var string
	*/
	protected $module_dir = '';


	/**
	*
	* @var bool
	*/
	protected $is_installed = false;

	/**
	*
	* @var bool
	*/
	protected $is_activated = false;

	/**
	 * @param string $module_name (optional)
	 *
	 * @throws Application_Modules_Exception
	 */
	public function  __construct( $module_name=null ) {
		if(!$module_name) {
			return;
		}

		$this->name = $module_name;

		$manifest_data = $this->readManifestData();
		$this->checkManifestData( $manifest_data );
		$this->setupProperties( $manifest_data );


	}

	/**
	 * @return array
	 */
	public function jsonSerialize() {
		return get_object_vars($this);
	}

	/**
	 * @return array
	 *
	 * @throws Application_Modules_Exception
	 */
	public function readManifestData() {
		$module_dir = $this->getModuleDir();

		if( !IO_Dir::exists($module_dir) ) {
			throw new Application_Modules_Exception(
				'Directory \''.$module_dir.'\' does not exist',
				Application_Modules_Exception::CODE_MODULE_DOES_NOT_EXIST
			);
		}


		$manifest_file = $module_dir . static::MANIFEST_FILE_NAME;

		if( !IO_File::isReadable($manifest_file) ) {
			throw new Application_Modules_Exception(
				'Module manifest file \''.$manifest_file.'\' does not exist or is not readable. ',
				Application_Modules_Exception::CODE_MANIFEST_IS_NOT_READABLE
			);
		}

		/** @noinspection PhpIncludeInspection */
		$manifest_data = require $manifest_file;
		
		return $manifest_data;
	}

	/**
	 * @param array $manifest_data
	 *
	 * @throws Application_Modules_Exception
	 */
	protected function checkManifestData( $manifest_data ) {
		if(!is_array($manifest_data)) {
			throw new Application_Modules_Exception(
				'Manifest data must be array (Module: \''.$this->name.'\')',
				Application_Modules_Exception::CODE_MANIFEST_NONSENSE
			);
		}

		if( empty( $manifest_data['API_version']) ) {
			throw new Application_Modules_Exception(
				'Required API version not set! (\'API_version\' array key does not exist, or is empty) (Module: \''.$this->name.'\')',
				Application_Modules_Exception::CODE_MANIFEST_NONSENSE
			);
		}

		if( empty( $manifest_data['label']) ) {
			throw new Application_Modules_Exception(
				'Module label not set! (\'label\' array key does not exist, or is empty) (Module: \''.$this->name.'\')',
				Application_Modules_Exception::CODE_MANIFEST_NONSENSE
			);
		}

		if( empty( $manifest_data['types']) ) {
			throw new Application_Modules_Exception(
				'Module types not set! (\'type\' array key does not exist, or is empty) (Module: \''.$this->name.'\')',
				Application_Modules_Exception::CODE_MANIFEST_NONSENSE
			);
		}

		$modules_types = static::getModuleTypesList();
		foreach($manifest_data['types'] as $type) {

			if(!isset($modules_types[$type])){
				throw new Application_Modules_Exception(
					'Invalid module type \''.$type.'\' ! See '.__NAMESPACE__.'\Application_Modules_Module_Manifest::getModulesTypesList() (Module: \''.$this->name.'\')',
					Application_Modules_Exception::CODE_MANIFEST_NONSENSE
				);
			}
		}

		if(
			isset($manifest_data['require']) &&
			!is_array($manifest_data['require'])
		){
			throw new Application_Modules_Exception(
				'Required modules (\'require\' key) must be an array like [required_module1, required_module2, ...]! (Module: \''.$this->name.'\')',
				Application_Modules_Exception::CODE_MANIFEST_NONSENSE
			);
		}

		if(
			isset($manifest_data['signals_callbacks']) &&
			!is_array($manifest_data['signals_callbacks'])
		){
			throw new Application_Modules_Exception(
				'Signal callbacks must be an array like [signal_name => module_method_name, signal_name2 => array(method2, method3), ...]! (Module: \''.$this->name.'\')',
				Application_Modules_Exception::CODE_MANIFEST_NONSENSE
			);
		}

	}

	/**
	 * Sets the values ​​according to the manifest data
	 *
	 * @param $manifest_data
	 *
	 * @throws Application_Modules_Exception
	 */
	protected function setupProperties( $manifest_data ) {

		foreach( $manifest_data as $key=>$val ) {
			if(!$this->getHasProperty($key)) {
				throw new Application_Modules_Exception(
					'Unknown manifest property \''.$key.'\' (Module: \''.$this->name.'\') ',
					Application_Modules_Exception::CODE_MANIFEST_NONSENSE
				);
			}


			$this->{$key} = $val;
		}

	}

	/**
	 * @return bool
	 */
	public function getIsCompatible() {
		return Version::getAPIIsCompatible($this->API_version);
	}

	/**
	 * Returns module root directory
	 *
	 * @return string
	 */
	public function getModuleDir() {
		return JET_MODULES_PATH . str_replace('.', '/', $this->name) . '/';
	}


	/**
	 * @return string
	 */
	public function getPublicURI() {
		return JET_MODULES_URI.str_replace('.', '/', $this->name).'/public/';
	}


	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @return string
	 */
	public function getNamespace() {
		return JET_APPLICATION_MODULE_NAMESPACE.'\\'.str_replace('.','\\',$this->name).'\\';
	}

	/**
	 * @return string
	 */
	public function getVendor() {
		return $this->vendor;
	}


	/**
	 * @return string
	 */
	public function getVersion() {
		return $this->version;
	}

	/**
	 * @return string
	 */
	public function getLabel() {
		return $this->label;
	}

	/**
	 * @return string
	 */
	public function getDescription() {
		return $this->description;
	}

	/**
	 * Returns required API version
	 *
	 * @return int
	 */
	public function getAPIVersion() {
		return $this->API_version;
	}

	/**
	 * Returns types list ( array )
	 *
	 * @return string[]
	 */
	public function getTypes() {
		return $this->types;
	}

	/**
	 * @param string $type
	 *
	 * @return bool
	 */
	public function getHasType( $type ) {
		return in_array( $type, $this->types );
	}

	/**
	 * @return bool
	 */
	public function getIsAuthController() {
		return $this->getHasType( static::MODULE_TYPE_AUTH_CONTROLLER );
	}



	/**
	 * Returns required module names ([module1, module2, ....])
	 *
	 * @return string[]
	 */
	public function getRequire() {
		return $this->require;
	}


	/**
	 * array(
	 *	  'signal1' => 'moduleMethod1',  // route signal 'signal1' to method 'moduleMethod1'
	 *	  'signal2' => array(            // route signal 'signal2' to methods 'method2' and 'method3'
	 *                      'method2',
	 *                      'method3'
	 *                  )
	 * )
	 *
	 * @return array
	 */
	public function getSignalCallbacks() {
		return $this->signals_callbacks;
	}

	/**
	 * @return bool
	 */
	public function getIsInstalled() {
		return $this->is_installed;
	}

	/**
	 * @return bool
	 */
	public function getIsActivated() {
		return $this->is_activated;
	}

	/**
	 * @param bool $is_installed
	 */
	public function setIsInstalled($is_installed) {
		$this->is_installed = (bool)$is_installed;
	}

	/**
	 * @param bool $is_activated
	 */
	public function setIsActivated($is_activated) {
		$this->is_activated = (bool)$is_activated;
	}

	/**
	 * Get available modules types list
	 *
	 * @return array 
	 */
	public static function getModuleTypesList() {
		return static::$module_types_list;
	}

	/**
	 * Format:
	 * array(
	 *      'module_type' => 'Module type description'
	 * )
	 *
	 * @param array $list
	 */
	public static function setModuleTypesList( array $list ) {
		static::$module_types_list = $list;
	}


	/**
	 *
	 * @param array $data
	 *
	 * @return Application_Modules_Module_Manifest
	 */
	public static function __set_state(array $data) {
		$i = new static();

		foreach($data as $key=>$val) {
			$i->{$key} = $val;
		}
		return $i;
	}
}