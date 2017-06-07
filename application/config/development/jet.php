<?php
const JET_DEVEL_MODE = true;
const JET_DEBUG_PROFILER_ENABLED = true;

const JET_LAYOUT_CSS_PACKAGER_ENABLED = true;
const JET_LAYOUT_JS_PACKAGER_ENABLED = true;

const JET_TRANSLATOR_AUTO_APPEND_UNKNOWN_PHRASE = true;



const JET_CACHE_REFLECTION_LOAD = false;
const JET_CACHE_REFLECTION_SAVE = false;

const JET_CACHE_DATAMODEL_DEFINITION_LOAD = JET_CACHE_REFLECTION_LOAD;
const JET_CACHE_DATAMODEL_DEFINITION_SAVE = JET_CACHE_REFLECTION_SAVE;

const JET_CACHE_CONFIG_DEFINITION_LOAD = JET_CACHE_REFLECTION_LOAD;
const JET_CACHE_CONFIG_DEFINITION_SAVE = JET_CACHE_REFLECTION_SAVE;

const JET_CACHE_AUTOLOADER_LOAD = JET_CACHE_REFLECTION_LOAD;
const JET_CACHE_AUTOLOADER_SAVE = JET_CACHE_REFLECTION_SAVE;

const JET_CACHE_MVC_SITE_LOAD = JET_CACHE_REFLECTION_LOAD;
const JET_CACHE_MVC_SITE_SAVE = JET_CACHE_REFLECTION_SAVE;

const JET_CACHE_MVC_PAGE_LOAD = JET_CACHE_REFLECTION_LOAD;
const JET_CACHE_MVC_PAGE_SAVE = JET_CACHE_REFLECTION_SAVE;



/** @noinspection PhpIncludeInspection */
require realpath( __DIR__.'/../_common/jet.php' );


