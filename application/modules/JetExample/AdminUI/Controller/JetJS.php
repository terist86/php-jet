<?php
/**
 *
 *
 *
 *
 *
 *
 * @copyright Copyright (c) 2012-2015 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 */
namespace JetApplicationModule\JetExample\AdminUI;
use Jet\Application;
use Jet\Mvc;
use Jet\Mvc_Controller_Abstract;
use Jet\Application_Modules;
use Jet\Translator;
use Jet\IO_File;
use Jet\Data_Text;
use Jet\Tr;
use Jet\Http_Headers;
use Jet\Debug_Profiler;

class Controller_JetJS extends Mvc_Controller_Abstract {
    /**
     *
     * @var Main
     */
    protected $module_instance;

    /**
     * @var array
     */
    protected static $ACL_actions_check_map = [
        'default' => false
    ];

    public function default_Action() {
    }

    /**
     *
     */
    public function initialize() {
    }


    /**
     *
     *
     * @return bool
     */
    public function parseRequestURL() {

        $path_fragments = Mvc::getCurrentRouter()->getPathFragments();


        $group = array_shift($path_fragments);

        if($group!='Jet' && $group!='Jet.js' && $group!='modules') {
            return false;
        }

        foreach($path_fragments as $pf) {
            if(strpos($pf, '..')!==false) {
                return false;
            }
        }

        $JS_file_path = null;

        $base_dir = $this->module_manifest->getModuleDir().'public/javascript/';

        if($group=='Jet.js') {
            $JS_file_path = $base_dir.'Jet.js';
        } else {
            if($group=='Jet') {
                $JS_file_path = $base_dir.'Jet/'.implode('/',$path_fragments);
            } else {
                if($group=='modules') {
                    $module_name = array_shift($path_fragments);

                    if($module_name) {
                        $module_manifest = Application_Modules::getModuleManifest( $module_name );

                        if($module_manifest) {
                            Translator::setCurrentNamespace( $module_manifest->getName() );

                            if(!$path_fragments) {
                                $path_fragments[] = 'Main';
                            }


                            $JS_file_path = $module_manifest->getModuleDir().'public/javascript/'.implode('/', $path_fragments).'.js';

                        }

                    }

                }
            }

        }

        if(!$JS_file_path) {
            return false;
        }

        if(!IO_File::exists($JS_file_path)){
            return false;
        }

        $JS = IO_File::read($JS_file_path);


        $JS = static::translateJavaScript($JS);

        /*
        if($get_as_string){
            return $JS;
        }
        */

        Http_Headers::responseOK(
            [
                'Content-type' => 'text/javascript;charset=utf-8'
            ]
        );

        echo $JS;

        Debug_Profiler::setOutputIsJSON(true);
        Application::end();

        return true;
    }

    /**
     * @param string $JS
     * @return string
     */
    public static function translateJavaScript( $JS ) {
        preg_match_all('~Jet.translate\((".*"|\'.*\')\)~isU', $JS, $matches, PREG_SET_ORDER);

        $replacements = [];
        foreach($matches as $match){
            list($search, $text) = $match;
            $text = stripslashes(trim($text, $text[0] == '\'' ? '\'' : '"'));

            $text = json_encode(Tr::_($text));
            $JS = str_replace($search, $text, $JS);
        }
        $JS = Data_Text::replaceData( $JS, $replacements );

        return $JS;
    }


    /**
     * @param string $module_action
     * @param string $controller_action
     * @param array $action_parameters
     *
     */
    public function responseAclAccessDenied($module_action, $controller_action, $action_parameters)
    {
    }

}