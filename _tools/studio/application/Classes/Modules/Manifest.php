<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetStudio;

use Jet\Application_Module_Manifest;
use Jet\Data_Array;
use Jet\Form;
use Jet\Form_Field_Checkbox;
use Jet\Form_Field_Input;
use Jet\Form_Field_Select;
use Jet\IO_File;
use Jet\Mvc_Layout;
use Jet\Tr;

class Modules_Manifest extends Application_Module_Manifest
{
	const MAX_ACL_ACTION_COUNT = 100;


	/**
	 * @var Form
	 */
	protected $__edit_form;

	/**
	 * @var Modules_Module_Controller[]
	 */
	protected $controllers = [];

	/**
	 * @var Pages_Page[][]
	 */
	protected $pages = [];

	/**
	 * @var bool
	 */
	protected $is_active = false;

	/**
	 * @var bool
	 */
	protected $is_installed = false;



	/**
	 * @var Form
	 */
	protected static $create_form;

	/**
	 * @var Form
	 */
	protected static $page_create_form;

	/**
	 * @var Form
	 */
	protected static $menu_item_create_form;


	/**
	 *
	 */
	public function save()
	{
		//TODO:
	}


	/**
	 * @param string $name
	 */
	public function setName($name)
	{
		$this->_name = $name;
	}

	/**
	 * @param string $vendor
	 */
	public function setVendor($vendor)
	{
		$this->vendor = $vendor;
	}

	/**
	 * @param string $version
	 */
	public function setVersion($version)
	{
		$this->version = $version;
	}

	/**
	 * @param string $label
	 */
	public function setLabel($label)
	{
		$this->label = $label;
	}

	/**
	 * @param string $description
	 */
	public function setDescription($description)
	{
		$this->description = $description;
	}

	/**
	 * @param bool $is_mandatory
	 */
	public function setIsMandatory($is_mandatory)
	{
		$this->is_mandatory = $is_mandatory;
	}

	/**
	 * @return bool
	 */
	public function getIsActive()
	{
		return $this->is_active;
	}

	/**
	 * @param bool $is_active
	 */
	public function setIsActive( $is_active )
	{
		$this->is_active = $is_active;
	}

	/**
	 * @return bool
	 */
	public function getIsInstalled()
	{
		return $this->is_installed;
	}

	/**
	 * @param bool $is_installed
	 */
	public function setIsInstalled( $is_installed )
	{
		$this->is_installed = $is_installed;
	}

	/**
	 * @param array $ACL_actions
	 */
	public function setACLActions( $ACL_actions )
	{
		$this->ACL_actions = $ACL_actions;
	}


	/**
	 * @return Pages_Page[][]
	 */
	public function getPagesList()
	{
		return $this->pages;
	}

	/**
	 * @param $site_id
	 * @param $page_id
	 *
	 * @return null|Pages_Page
	 */
	public function getPage( $site_id, $page_id )
	{
		if(
			!isset($this->pages[$site_id]) ||
			!isset($this->pages[$site_id][$page_id])
		) {
			return null;
		}

		$page = $this->pages[$site_id][$page_id];

		$site = Sites::getSite($page->getSiteId());
		$page->setLocale( $site->getDefaultLocale() );

		return $page;
	}



	/**
	 * @return Form
	 */
	public static function getCreateForm()
	{
		if(!static::$create_form) {


			$module_name = new Form_Field_Input('module_name', 'Name:', '' );
			$module_label = new Form_Field_Input('module_label', 'Label:', '' );


			$module_name->setIsRequired(true);
			$module_name->setErrorMessages([
				Form_Field_Input::ERROR_CODE_EMPTY => 'Please enter module name',
				Form_Field_Input::ERROR_CODE_INVALID_FORMAT => 'Invalid module name format'
			]);
			$module_name->setValidator( function( Form_Field_Input $field ) {
				$name = $field->getValue();

				return Modules_Manifest::checkModuleName( $field, $name );
			} );

			$module_label->setIsRequired(true);
			$module_label->setErrorMessages([
				Form_Field_Input::ERROR_CODE_EMPTY => 'Please enter module label'
			]);

			$fields = [
				$module_name,
				$module_label,
			];

			$form = new Form('create_module_form', $fields );


			$form->setAction( Modules::getActionUrl('add') );

			static::$create_form = $form;
		}

		return static::$create_form;
	}

	/**
	 * @return bool|Modules_Manifest
	 */
	public static function catchCreateForm()
	{
		$form = static::getCreateForm();
		if(
			!$form->catchInput() ||
			!$form->validate()
		) {
			return false;
		}


		$new_module = Modules::createModule(
			$form->field('module_name')->getValue(),
			$form->field('module_label')->getValue()
		);
		//TODO: $new_module->setVendor( Projects::getCurrentProject()->getAuthor() );

		return $new_module;
	}


	/**
	 *
	 * @return Form
	 */
	public function getEditForm()
	{
		if( !$this->__edit_form ) {

			$module_name = new Form_Field_Input('module_name', 'Name:', $this->getName() );
			$module_name->setIsRequired(true);
			$module_name->setErrorMessages([
				Form_Field_Input::ERROR_CODE_EMPTY => 'Please enter module name',
				Form_Field_Input::ERROR_CODE_INVALID_FORMAT => 'Invalid module name format'
			]);
			$module_name->setValidator( function( Form_Field_Input $field ) {
				$name = $field->getValue();
				$old_module_name = $this->getName();

				return Modules_Manifest::checkModuleName( $field, $name, $old_module_name );
			} );
			$module_name->setCatcher( function( $value ) {
				$this->setName( $value );
			} );



			$module_label = new Form_Field_Input('module_label', 'Label:', $this->getLabel() );
			$module_label->setIsRequired(true);
			$module_label->setErrorMessages([
				Form_Field_Input::ERROR_CODE_EMPTY => 'Please enter module label'
			]);
			$module_label->setCatcher( function( $value ) {
				$this->setLabel( $value );
			} );


			$vendor = new Form_Field_Input('vendor', 'Vendor:', $this->getVendor() );
			$vendor->setCatcher( function( $value ) {
				$this->setVendor( $value );
			} );

			$version = new Form_Field_Input('version', 'Version:', $this->getVersion() );
			$version->setCatcher( function( $value ) {
				$this->setVersion( $value );
			} );

			$description = new Form_Field_Input('description', 'Description:', $this->getDescription() );
			$description->setCatcher( function( $value ) {
				$this->setDescription( $value );
			} );

			$is_mandatory = new Form_Field_Checkbox('is_mandatory', 'Is mandatory', $this->isMandatory() );
			$is_mandatory->setCatcher( function( $value ) {
				$this->setIsMandatory( $value );
			} );

			$is_active = new Form_Field_Checkbox('is_active', 'Is active', $this->getIsActive() );
			$is_active->setIsReadonly(true);

			$is_installed = new Form_Field_Checkbox('is_installed', 'Is installed', $this->getIsInstalled() );
			$is_installed->setIsReadonly(true);



			$fields = [
				$module_name,
				$module_label,
				$vendor,
				$version,
				$description,
				$is_mandatory,
				$is_active,
				$is_installed,
			];


			$m = 0;
			foreach( $this->getACLActions( false ) as $action=>$description) {

				$acl_action = new Form_Field_Input('/ACL_action/'.$m.'/action', 'Action:', $action );
				$fields[] = $acl_action;

				$acl_action_description = new Form_Field_Input('/ACL_action/'.$m.'/description', 'Label:', $description );
				$fields[] = $acl_action_description;

				$m++;
			}

			for( $c=0;$c<8;$c++ ) {

				$acl_action = new Form_Field_Input('/ACL_action/'.$m.'/action', 'Action:', '' );
				$fields[] = $acl_action;

				$acl_action_description = new Form_Field_Input('/ACL_action/'.$m.'/description', 'Label:', '' );
				$fields[] = $acl_action_description;

				$m++;
			}



			$form = new Form('edit_module_form', $fields );


			$form->setAction( Modules::getActionUrl('edit') );

			$this->__edit_form = $form;
		}

		return $this->__edit_form;
	}

	/**
	 * @return bool
	 */
	public function catchEditForm()
	{
		$form = $this->getEditForm();
		if(
			!$form->catchInput() ||
			!$form->validate()
		) {
			return false;
		}

		$form->catchData();
		$this->catchEditForm_ACLAction( $form );


		return true;
	}

	/**
	 * @param Form $form
	 *
	 */
	public function catchEditForm_ACLAction( Form $form )
	{
		$ACL_actions = [];
		for( $m=0;$m<static::MAX_ACL_ACTION_COUNT;$m++ ) {
			if(!$form->fieldExists('/ACL_action/'.$m.'/action')) {
				break;
			}

			$action = $form->field('/ACL_action/'.$m.'/action')->getValue();
			$description = $form->field('/ACL_action/'.$m.'/description')->getValue();

			if(
				!$action
			) {
				continue;
			}

			if(!$description) {
				$description = $action;
			}

			$ACL_actions[$action] = $description;
		}

		$this->ACL_actions = $ACL_actions;

	}



	/**
	 * @param Form_Field_Input $field
	 * @param string $name
	 * @param string $old_module_name
	 *
	 * @return bool
	 */
	public static function checkModuleName(Form_Field_Input $field, $name, $old_module_name='' )
	{


		if(!$name)	{
			$field->setError( Form_Field_Input::ERROR_CODE_EMPTY );
			return false;
		}

		if(
			!preg_match('/^[a-z0-9.]{3,}$/i', $name) ||
			strpos( $name, '..' )!==false ||
			$name[0]=='.' ||
			$name[strlen($name)-1]=='.'
		) {
			$field->setError(Form_Field_Input::ERROR_CODE_INVALID_FORMAT);

			return false;
		}

		if(
			(
				!$old_module_name &&
				Modules::exists($name)
			)
			||
			(
				$old_module_name &&
				$old_module_name!=$name &&
				Modules::exists($name)
			)
		) {
			$field->setCustomError(
				Tr::_('Module with the same name already exists'),
				'module_name_is_not_unique'
			);

			return false;
		}

		return true;

	}

	/**
	 * @param string $site_id
	 *
	 * @param Pages_Page $page
	 */
	public function addPage( $site_id, Pages_Page $page )
	{
		if(!isset($this->pages[$site_id])) {
			$this->pages[$site_id] = [];
		}

		$this->pages[$site_id][$page->getId()] = $page;

	}

	/**
	 * @return Form
	 */
	public static function getPageCreateForm()
	{
		if(!static::$page_create_form) {
			$sites = [''=>''];
			foreach( Sites::getSites() as $site ) {
				$sites[$site->getId()] = $site->getName();
			}

			$site_id = new Form_Field_Select('site_id', 'Site: ', '');
			$site_id->setSelectOptions( $sites );
			$site_id->setIsRequired( true );
			$site_id->setErrorMessages([
				Form_Field_Select::ERROR_CODE_EMPTY => 'Please select site',
				Form_Field_Select::ERROR_CODE_INVALID_VALUE => 'Please select site',
			]);
			
			$page_name = new Form_Field_Input('page_name', 'Page name:', '');
			$page_name->setIsRequired(true);
			$page_name->setErrorMessages([
				Form_Field_Input::ERROR_CODE_EMPTY => 'Please enter page name'
			]);

			$page_id = new Form_Field_Input('page_id', 'Page ID:', '');
			$page_id->setIsRequired(true);
			$page_id->setErrorMessages([
				Form_Field_Input::ERROR_CODE_EMPTY => 'Please enter page ID'
			]);
			

			$form = new Form('add_page_form', [
				$site_id,
				$page_name,
				$page_id
			]);

			$form->setAction( Modules::getActionUrl('page/add') );


			static::$page_create_form = $form;
		}

		return static::$page_create_form;
	}

	/**
	 *
	 * @return Pages_Page|bool
	 */
	public function catchCratePageForm()
	{
		$form = static::getPageCreateForm();

		if(
			!$form->catchInput() ||
			!$form->validate()
		) {
			return false;
		}

		$site_id = $form->getField('site_id')->getValue();
		$page_name = $form->getField('page_name')->getValue();
		$page_id = $form->getField('page_id')->getValue();


		$page_id = static::generatePageId( $page_id, $site_id );

		$page = new Pages_Page();

		$page->setSiteId( $site_id );
		$page->setId( $page_id );
		$page->setName( $page_name );
		$page->setTitle( $page_name );
		$page->setRelativePathFragment( $page_id );

		$content = new Pages_Page_Content();
		$content->setModuleName( $this->getName() );
		$content->setControllerName( 'Main' );
		$content->setControllerAction( 'default' );
		$content->setOutputPosition( Mvc_Layout::DEFAULT_OUTPUT_POSITION );

		$page->setContent([
			$content
		]);

		$this->addPage( $site_id, $page );

		return $page;
	}

	/**
	 * @param string $name
	 * @param string $site_id
	 * @return string
	 */
	public static function generatePageId( $name, $site_id )
	{
		$site = Sites::getSite( $site_id );

		$id = Project::generateIdentifier( $name, function( $id ) use ($site) {

			foreach( $site->getLocales() as $locale ) {
				if( Pages::exists( $id, $locale, $site->getId() ) ) {
					return true;
				}
			}

			return false;
		} );

		return $id;
	}

	/**
	 * @param string $site_id
	 * @param string $page_id
	 * @return Pages_Page|null
	 */
	public function deletePage( $site_id, $page_id )
	{
		if( !isset($this->pages[$site_id][$page_id]) ) {
			return null;
		}

		$old_page = $this->pages[$site_id][$page_id];

		unset($this->pages[$site_id][$page_id]);

		if(!count($this->pages[$site_id])) {
			unset($this->pages[$site_id]);
		}

		return $old_page;
	}

	/**
	 * @param Pages_Page $page
	 *
	 * @return Form
	 */
	public static function getPageContentCreateForm( Pages_Page $page )
	{
		$form = Pages_Page_Content::getCreateForm( $page );

		$form->setAction( Modules::getActionUrl('page/content/add', [
			'site' => $page->getSiteId(),
			'page' => $page->getId()
		]) );

		return $form;
	}

	/**
	 * @return Modules_Module_Controller[]
	 */
	public function getControllers()
	{
		return $this->controllers;
	}

	/**
	 * @param string $internal_id
	 * @return Modules_Module_Controller|null
	 */
	public function getController( $internal_id )
	{
		if(!isset($this->controllers[$internal_id])) {
			return null;
		}

		return $this->controllers[$internal_id];
	}

	/**
	 * @param Modules_Module_Controller $controller
	 */
	public function addController( Modules_Module_Controller $controller )
	{
		$this->controllers[$controller->getInternalId()] = $controller;
	}

	/**
	 * @param string $controller_id
	 *
	 * @return Modules_Module_Controller|null
	 */
	public function deleteController( $controller_id )
	{
		if(!isset($this->controllers[$controller_id])) {
			return null;
		}

		$old_controller = $this->controllers[$controller_id];

		unset( $this->controllers[$controller_id] );

		return $old_controller;
	}


	/**
	 * @return Form
	 */
	public static function getCreateMenuItemForm()
	{
		if(!static::$menu_item_create_form) {
			$form = Menus_MenuNamespace_Menu_Item::getCreateForm();

			$target_menus = [''=>''];
			foreach( Menus::getMenuNamespaces() as $menu_namespace ) {
				foreach( $menu_namespace->getMenus() as $menu ) {

					$key = $menu_namespace->getInternalId().':'.$menu->getId();

					$target_menus[$key] = $menu_namespace->getName().' / '.$menu->getLabel().' ('.$menu->getId().')';
				}
			}


			$target_menu = new Form_Field_Select('target_menu', 'Menu', '');
			$target_menu->setIsRequired( true );
			$target_menu->setErrorMessages([
				Form_Field_Select::ERROR_CODE_EMPTY=> 'Please select target menu',
				Form_Field_Select::ERROR_CODE_INVALID_VALUE => 'Please select target menu'
			]);
			$target_menu->setSelectOptions($target_menus);


			$form->addField( $target_menu );

			$form->setAction( Modules::getActionUrl('menu_item/add') );

			static::$menu_item_create_form = $form;
		}

		return static::$menu_item_create_form;
	}

	/**
	 * @return bool|Menus_MenuNamespace_Menu_Item
	 */
	public static function catchCreateMenuItemForm()
	{
		$form = static::getCreateMenuItemForm();
		if(
			!$form->catchInput() ||
			!$form->validate()
		) {
			return false;
		}

		$target_menu = $form->field('target_menu')->getValue();

		[$namespace_id, $menu_id] = explode(':', $target_menu);

		$menu_item = new Menus_MenuNamespace_Menu_Item(
			$form->field('id')->getValue(),
			$form->field('label')->getValue()
		);

		$menu_item->setNamespaceId( $namespace_id );
		$menu_item->setMenuId( $menu_id );

		$menu_item->setIndex( $form->field('index')->getValue() );
		$menu_item->setIcon( $form->field('icon')->getValue() );

		$menu_item->setSeparatorBefore( $form->field('separator_before')->getValue() );
		$menu_item->setSeparatorAfter( $form->field('separator_after')->getValue() );


		$menu_item->setURL( $form->field('URL')->getValue() );

		$menu_item->setPageId( $form->field('page_id')->getValue() );
		$menu_item->setSiteId( $form->field('site_id')->getValue() );
		$menu_item->setLocale( $form->field('locale')->getValue() );

		$menu_item->setUrlParts( Menus_MenuNamespace_Menu_Item::catchURLParts( $form ) );
		$menu_item->setGetParams( Menus_MenuNamespace_Menu_Item::catchGETparams( $form ) );

		return $menu_item;
	}

	/**
	 * @param Menus_MenuNamespace_Menu_Item $menu_item
	 */
	public function addMenuItem( Menus_MenuNamespace_Menu_Item $menu_item )
	{
		$namespace_id = $menu_item->getNamespaceId();
		$menu_id = $menu_item->getMenuId();
		$item_id = $menu_item->getId();

		if(!isset($this->menu_items[$namespace_id])) {
			$this->menu_items[$namespace_id] = [];
		}

		if(!isset($this->menu_items[$namespace_id][$menu_id])) {
			$this->menu_items[$namespace_id][$menu_id] = [];
		}

		$this->menu_items[$namespace_id][$menu_id][$item_id] = $menu_item;

		static::$menu_item_create_form = null;
	}

	/**
	 * @param string $namespace_id
	 * @param string $menu_id
	 *
	 * @return Menus_MenuNamespace_Menu_Item[]
	 */
	public function getMenuItemsList( $namespace_id='', $menu_id='' )
	{
		if(!$namespace_id) {
			return $this->menu_items;
		}

		if(!isset($this->menu_items[$namespace_id])) {
			return [];
		}

		if(!$menu_id) {
			return $this->menu_items[$namespace_id];
		}

		if( !isset($this->menu_items[$namespace_id][$menu_id]) ) {
			return [];
		}


		return $this->menu_items[$namespace_id][$menu_id];
	}

	/**
	 * @param string $namespace_id
	 * @param string $menu_id
	 * @param string $item_id
	 *
	 * @return Menus_MenuNamespace_Menu_Item|null
	 */
	public function getMenuItem( $namespace_id, $menu_id, $item_id )
	{
		if(!isset($this->menu_items[$namespace_id][$menu_id][$item_id])) {
			return null;
		}

		return $this->menu_items[$namespace_id][$menu_id][$item_id];
	}

	/**
	 *
	 * @param string $namespace_id
	 * @param string $menu_id
	 * @param string $item_id
	 *
	 * @return Menus_MenuNamespace_Menu_Item|null
	 */
	public function deleteMenuItem( $namespace_id, $menu_id, $item_id )
	{
		if(
			!isset($this->menu_items[$namespace_id]) ||
			!isset($this->menu_items[$namespace_id][$menu_id]) ||
			!isset($this->menu_items[$namespace_id][$menu_id][$item_id])
		) {
			return null;
		}

		$old_item = $this->menu_items[$namespace_id][$menu_id][$item_id];

		unset( $this->menu_items[$namespace_id][$menu_id][$item_id] );

		if(!count($this->menu_items[$namespace_id][$menu_id])) {
			unset( $this->menu_items[$namespace_id][$menu_id] );
		}

		if(!count($this->menu_items[$namespace_id])) {
			unset( $this->menu_items[$namespace_id] );
		}

		return $old_item;
	}

	public function toArray()
	{
		$res = [
			'API_version'  => $this->getAPIVersion(),
			'vendor'       => $this->getVendor(),
			'version'      => $this->getVersion(),
			'label'        => $this->getLabel(),
			'description'  => $this->getDescription(),
			'is_mandatory' => $this->isMandatory()
		];

		foreach( $this->getACLActions(false) as $action=>$description ) {
			if(!isset($res['ACL_actions'])) {
				$res['ACL_actions'] = [];
			}

			$res['ACL_actions'][$action] = $description;
		}

		foreach( $this->pages as $site_id=>$pages ) {
			if(!isset($res['pages'])) {
				$res['pages'] = [];
			}

			if(!isset($res['pages'][$site_id])) {
				$res['pages'][$site_id] = [];
			}

			foreach( $pages as $page_id=>$page ) {
				$page = $page->toArray();
				unset($page['id']);

				$res['pages'][$site_id][$page_id] = $page;
			}

		}

		foreach( $this->menu_items as $namespace_id=>$menus ) {
			$namespace = Menus::getMenuNamespace( $namespace_id );
			if(!$namespace) {
				continue;
			}

			if(!isset($res['menu_items'])) {
				$res['menu_items'] = [];
			}

			$namespace = $namespace->getName();

			if(!isset($res['menu_items'][$namespace])) {
				$res['menu_items'][$namespace] = [];
			}

			foreach( $menus as $menu_id=>$items ) {

				if(!isset($res['menu_items'][$namespace][$menu_id])) {
					$res['menu_items'][$namespace][$menu_id] = [];
				}

				foreach( $items as $item_id=>$item ) {
					/**
					 * @var Menus_MenuNamespace_Menu_Item $item;
					 */
					$item = $item->toArray();

					$res['menu_items'][$namespace][$menu_id][$item_id] = $item;

				}

			}
		}

		return $res;
	}

	/**
	 *
	 */
	public function generate()
	{
		$this->generate_manifest();
		$this->generate_controllers();
		$this->generate_mainClass();
	}

	/**
	 *
	 */
	public function generate_manifest()
	{
		$module_dir = $this->getModuleDir();

		$data = new Data_Array($this->toArray());

		IO_File::write( $module_dir.static::getManifestFileName(), '<?php return '.$data->export() );

	}

	/**
	 *
	 */
	public function generate_controllers()
	{
		foreach( $this->getControllers() as $controller ) {
			$this->generate_controller( $controller );
		}
	}

	/**
	 * @param Modules_Module_Controller $controller
	 */
	public function generate_controller( Modules_Module_Controller $controller )
	{
		$class = $controller->createClass( $this );

		$class->write( $this->getModuleDir().$controller->getScriptName() );
	}

	/**
	 *
	 */
	public function generate_mainClass()
	{
		$class_name = 'Main';

		$class = new ClassCreator_Class();

		$class->setNamespace( rtrim($this->getNamespace(), '\\') );
		$class->setName( $class_name );

		$extends_ns = 'Jet';
		$extends_class = 'Application_Module';


		$class->addUse( new ClassCreator_UseClass($extends_ns, $extends_class) );
		$class->setExtends( $extends_class );

		$class->write( $this->getModuleDir().'Main.php');


	}

	/**
	 * @param Menus_MenuNamespace $namespace
	 *
	 * @return bool
	 */
	public function event_menuNamespaceDeleted( Menus_MenuNamespace $namespace )
	{
		if(isset($this->menu_items[$namespace->getInternalId()])) {
			unset($this->menu_items[$namespace->getInternalId()]);

			return true;
		}


		return false;
	}

	/**
	 * @param Menus_MenuNamespace_Menu $menu
	 *
	 * @return bool
	 */
	public function event_menuDeleted( Menus_MenuNamespace_Menu $menu )
	{
		if(isset($this->menu_items[$menu->getNamespaceName()][$menu->getId()])) {
			unset($this->menu_items[$menu->getNamespaceName()][$menu->getId()]);

			return true;
		}


		return false;
	}

}