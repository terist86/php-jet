<?php
define('JET_BASE_PATH', dirname(dirname(dirname(__DIR__))).'/');

const JET_TRANSLATOR_DEFAULT_BACKEND = 'PHPFiles';
const JET_TRANSLATOR_AUTO_APPEND_UNKNOWN_PHRASE = true;
const JET_TRANSLATOR_DICTIONARIES_PATH = '%JET_DATA_PATH%/dictionaries/%TRANSLATOR_NAMESPACE%/%TRANSLATOR_LOCALE%.php';

const JET_DEFAULT_AUTH_CONTROLLER_MODULE_NAME = 'JetExample.AuthController';
//const JET_DEFAULT_AUTH_CONTROLLER_CLASS_NAME = 'JetApplicationModule\JetExample\AuthController\Main';

define('JET_APPLICATION_PATH', JET_BASE_PATH.'application/');

define('JET_LIBRARY_PATH', JET_BASE_PATH.'library/');


define('JET_ERROR_PAGES_PATH', JET_APPLICATION_PATH.'error_pages/');
define('JET_CONFIG_PATH', JET_APPLICATION_PATH.'config/');
define('JET_MODULES_PATH', JET_APPLICATION_PATH.'modules/');
define('JET_SITES_PATH', JET_BASE_PATH.'sites/');
define('JET_DATA_PATH', JET_APPLICATION_PATH.'data/');
define('JET_LOGS_PATH', JET_APPLICATION_PATH.'logs/');
define('JET_TMP_PATH', JET_APPLICATION_PATH.'tmp/');

define('JET_TEMPLATES_PATH', JET_BASE_PATH.'_templates/');
define('JET_TEMPLATES_SITES_PATH', JET_TEMPLATES_PATH.'sites/');
define('JET_TEMPLATES_MODULES_PATH', JET_TEMPLATES_PATH.'modules/');

define('JET_PUBLIC_PATH', JET_BASE_PATH.'public/');
define('JET_PUBLIC_IMAGES_PATH', JET_BASE_PATH.'public/images/');
define('JET_PUBLIC_DATA_PATH', JET_BASE_PATH.'public/data/');
define('JET_PUBLIC_SCRIPTS_PATH', JET_BASE_PATH.'public/scripts/');
define('JET_PUBLIC_STYLES_PATH', JET_BASE_PATH.'public/styles/');

const JET_APPLICATION_CONFIGURATION_NAME = 'config';


const JET_APPLICATION_MODULE_NAMESPACE = 'JetApplicationModule';
define('JET_APPLICATION_MODULES_LIST_PATH', JET_DATA_PATH.'modules_list.php');

define('JET_OBJECT_REFLECTION_CACHE_PATH', JET_DATA_PATH.'reflections/' );

define('JET_DATAMODEL_DEFINITION_CACHE_LOAD', JET_OBJECT_REFLECTION_CACHE_LOAD );
define('JET_DATAMODEL_DEFINITION_CACHE_SAVE', JET_OBJECT_REFLECTION_CACHE_SAVE );
define('JET_DATAMODEL_DEFINITION_CACHE_PATH', JET_DATA_PATH.'datamodel_definitions/' );

define('JET_CONFIG_DEFINITION_CACHE_LOAD', JET_OBJECT_REFLECTION_CACHE_LOAD );
define('JET_CONFIG_DEFINITION_CACHE_SAVE', JET_OBJECT_REFLECTION_CACHE_SAVE );
define('JET_CONFIG_DEFINITION_CACHE_PATH', JET_DATA_PATH.'config_definitions/' );

const JET_AUTOLOADER_CACHE_LOAD = JET_OBJECT_REFLECTION_CACHE_LOAD;
const JET_AUTOLOADER_CACHE_SAVE = JET_OBJECT_REFLECTION_CACHE_SAVE;
const JET_AUTOLOADER_CACHE_PATH = JET_DATA_PATH;


const JET_IO_CHMOD_MASK_DIR = 0777;
const JET_IO_CHMOD_MASK_FILE = 0666;

const JET_HIDE_HTTP_REQUEST = true;

const JET_CHARSET = 'UTF-8';

const JET_TIMEZONE = 'Europe/Prague';

const JET_TAB ="\t";
const JET_EOL = PHP_EOL;

const BOOTSTRAP_CSS_URI = '//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css';