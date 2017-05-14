<?php
const JET_DEVEL_MODE = true;
const JET_DEBUG_PROFILER_ENABLED = true;

const JET_LAYOUT_CSS_PACKAGER_ENABLED = true;
const JET_LAYOUT_JS_PACKAGER_ENABLED = true;

const JET_OBJECT_REFLECTION_CACHE_LOAD = false;
const JET_OBJECT_REFLECTION_CACHE_SAVE = true;

const JET_DATAMODEL_DEFINITION_CACHE_LOAD = JET_OBJECT_REFLECTION_CACHE_LOAD;
const JET_DATAMODEL_DEFINITION_CACHE_SAVE = JET_OBJECT_REFLECTION_CACHE_SAVE;

const JET_CONFIG_DEFINITION_CACHE_LOAD = JET_OBJECT_REFLECTION_CACHE_LOAD;
const JET_CONFIG_DEFINITION_CACHE_SAVE = JET_OBJECT_REFLECTION_CACHE_SAVE;

const JET_AUTOLOADER_CACHE_LOAD = JET_OBJECT_REFLECTION_CACHE_LOAD;
const JET_AUTOLOADER_CACHE_SAVE = JET_OBJECT_REFLECTION_CACHE_SAVE;

const JET_TRANSLATOR_AUTO_APPEND_UNKNOWN_PHRASE = true;


/** @noinspection PhpIncludeInspection */
require realpath( __DIR__.'/../_common/jet.php' );


