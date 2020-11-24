<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetStudio;

use Jet\Data_Text;
use Jet\Exception;
use Jet\Form_Field_Select;
use Jet\Form_Field_Textarea;
use Jet\Form_Field_Hidden;
use Jet\IO_Dir;
use Jet\Mvc_Page;
use Jet\Form;
use Jet\Form_Field_Input;
use Jet\Form_Field_Checkbox;
use Jet\Mvc_Factory;
use Jet\Mvc_Page_Interface;
use Jet\Mvc_Site_Interface;
use Jet\Tr;
use Jet\Locale;
use Jet\IO_File;
use Jet\Data_Array;
use Jet\Mvc_Page_Content_Interface;
use Jet\Mvc_Page_MetaTag;

/**
 *
 */
class Pages_Page extends Mvc_Page
{
	const MAX_META_TAGS_COUNT = 100;
	const MAX_HTT_HEADERS_COUNT = 100;

	/**
	 * @var Form
	 */
	protected $__edit_form_main;

	/**
	 * @var Form
	 */
	protected $__edit_form_content;

	/**
	 * @var Form
	 */
	protected $__edit_form_static_content;

	/**
	 * @var Form
	 */
	protected $__edit_form_callback;


	/**
	 * @var Form
	 */
	protected $__delete_content_form;


	/**
	 *
	 * @var Pages_Page_Content[]
	 */
	protected $content = [];

	/**
	 * @var string
	 */
	protected $original_relative_path_fragment = '';


	/**
	 * @var Form
	 */
	protected static $create_form;

	/**
	 * @param Mvc_Site_Interface      $site
	 * @param Locale                  $locale
	 * @param array                   $data
	 * @param Mvc_Page_Interface|null $parent_page
	 *
	 * @return Mvc_Page_Interface
	 */
	public static function createByData( Mvc_Site_Interface $site, Locale $locale, array $data, Mvc_Page_Interface $parent_page = null )
	{
		$page = new static();

		$page->setSite( $site );
		$page->setLocale( $locale );
		$page->setId( $data['id'] );

		if( $parent_page ) {
			$page->setParent( $parent_page );
		}

		unset( $data['id'] );

		$page->setData( $data );

		$page->original_relative_path_fragment = $page->relative_path_fragment;

		return $page;
	}


	/**
	 *
	 * @param string|null        $page_id (optional, null = current)
	 * @param string|Locale|null $locale (optional, null = current)
	 * @param string|null        $site_id (optional, null = current)
	 *
	 * @return Pages_Page
	 */
	public static function get( $page_id = null, $locale = null, $site_id = null )
	{
		return Pages::getPage( $page_id, $locale, $site_id );
	}

	/**
	 * @param string $relative_path_fragment
	 */
	public function setRelativePathFragment( $relative_path_fragment )
	{
		$this->relative_path_fragment = $relative_path_fragment;


		$parent = $this->getParent();
		if(
			$parent &&
			$parent->getRelativePath()
		) {
			$this->relative_path = $parent->getRelativePath().'/'.$this->relative_path_fragment;
		} else {
			$this->relative_path = $this->relative_path_fragment;

		}

		foreach( $this->getChildren() as $ch ) {
			$ch->setRelativePathFragment( $ch->getRelativePathFragment() );

		}
	}


	/**
	 *
	 * @return Form
	 */
	public static function getCreateForm()
	{
		if(!static::$create_form) {

			$name_field = new Form_Field_Input('name', 'Name:');
			$name_field->setIsRequired( true );
			$name_field->setErrorMessages([
				Form_Field_Input::ERROR_CODE_EMPTY => 'Please enter page name'
			]);

			$id_field = new Form_Field_Input('id', 'Identifier:');
			$id_field->setIsRequired( true );
			$id_field->setErrorMessages([
				Form_Field_Input::ERROR_CODE_EMPTY => 'Please enter page identifier',
				Form_Field_Input::ERROR_CODE_INVALID_FORMAT => 'Invalid page identifier format',
			]);
			$id_field->setValidator( function( Form_Field_Input $field ) {
				$id = $field->getValue();

				if(!$id)	{
					$field->setError( Form_Field_Input::ERROR_CODE_EMPTY );
					return false;
				}

				if(
					!preg_match('/^[a-zA-Z0-9\-]{2,}$/i', $id) ||
					strpos( $id, '--' )!==false
				) {
					$field->setError(Form_Field_Input::ERROR_CODE_INVALID_FORMAT);

					return false;
				}

				if(
				Pages::exists( $id )
				) {
					$field->setCustomError(
						Tr::_('Page with the identifier already exists'),
						'site_id_is_not_unique'
					);

					return false;
				}

				return true;

			} );

			$form = new Form(
				'page_create_form',
				[
					$name_field,
					$id_field
				]
			);

			$form->setAction( Pages::getActionUrl('add') );

			static::$create_form = $form;
		}

		return static::$create_form;
	}

	/**
	 *
	 * @return bool|Pages_Page
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

		$new_page = static::createPage(
			Pages::getCurrentSiteId(),
			Pages::getCurrentLocale(),
			$form->field('id')->getValue(),
			$form->field('name')->getValue(),
			Pages::getCurrentPage()
		);

		return $new_page;
	}

	/**
	 * @param string $site_id
	 * @param Locale|string $locale
	 * @param string $id
	 * @param string $name
	 * @param Pages_Page|null $parent
	 *
	 * @return Pages_Page
	 */
	public static function createPage( $site_id, $locale, $id, $name, Pages_Page $parent=null )
	{

		if(!is_object($locale)) {
			$locale = new Locale($locale);
		}


		$page = new Pages_Page();
		$page->setSiteId( $site_id );
		$page->setLocale( $locale );
		$page->setId( $id );
		$page->setName( $name );
		$page->setTitle( $name );
		$page->setLayoutScriptName('default');

		if($parent) {
			$page->setRelativePathFragment( $id );
			$page->original_relative_path_fragment = $id;
			$page->setParent( $parent );
		}

		return $page;
	}




	/**
	 * @return Form
	 */
	public function getEditForm_main()
	{
		if(!$this->__edit_form_main) {

			$page = $this;

			$name_field = new Form_Field_Input('name', 'Name:', $page->getName());
			$name_field->setIsRequired( true );
			$name_field->setErrorMessages([
				Form_Field_Input::ERROR_CODE_EMPTY => 'Please enter page name'
			]);
			$name_field->setCatcher( function( $value ) use ($page) {
				$page->setName( $value );
			} );

			$title_field = new Form_Field_Input('title', 'Title:', $page->getTitle());
			$title_field->setIsRequired( true );
			$title_field->setErrorMessages([
				Form_Field_Input::ERROR_CODE_EMPTY => 'Please enter page title'
			]);
			$title_field->setCatcher( function( $value ) use ($page) {
				$page->setTitle( $value );
			} );


			$menu_title_field = new Form_Field_Input('menu_title', 'Menu item title:', $page->getMenuTitle());
			$menu_title_field->setCatcher( function( $value ) use ($page) {
				$page->setMenuTitle( $value );
			} );


			$breadcrumb_title_field = new Form_Field_Input('breadcrumb_title', 'Breadcrumb title:', $page->getBreadcrumbTitle());
			$breadcrumb_title_field->setCatcher( function( $value ) use ($page) {
				$page->setBreadcrumbTitle( $value );
			} );


			$icon_field = new Form_Field_Input('icon', 'Icon:', $page->getIcon());
			$icon_field->setCatcher( function( $value ) use ($page) {
				$page->setIcon( $value );
			} );




			$is_secret_field = new Form_Field_Checkbox('is_secret', 'is secret', $page->getIsSecret() );
			$is_secret_field->setCatcher( function( $value ) use ($page) {
				if(!$page->isSecretByDefault()) {
					$page->setIsSecret( $value );
				}
			} );
			if($this->isSecretByDefault()) {
				$is_secret_field->setIsReadonly(true);
				$is_secret_field->setDefaultValue(true);
			}


			$is_active_field = new Form_Field_Checkbox('is_active', 'is active', $page->getIsActive() );
			$is_active_field->setCatcher( function( $value ) use ($page) {
				if(!$page->getIsDeactivatedByDefault()) {
					$page->setIsActive( $value );
				}
			} );
			if($this->getIsDeactivatedByDefault()) {
				$is_active_field->setIsReadonly(true);
				$is_active_field->setDefaultValue(false);
			}

			$SSL_required_field = new Form_Field_Checkbox('SSL_required', 'SSL required', $page->getSSLRequired() );
			$SSL_required_field->setCatcher( function( $value ) use ($page) {
				if(!$page->isSSLRequiredByDefault()) {
					$page->setSSLRequired( $value );
				}
			} );
			if($this->isSSLRequiredByDefault()) {
				$SSL_required_field->setIsReadonly(true);
				$SSL_required_field->setDefaultValue(true);
			}

			$fields = [
				$name_field,
				$title_field,
				$menu_title_field,
				$breadcrumb_title_field,
				$icon_field,

				$is_secret_field,
				$is_active_field,
				$SSL_required_field
			];

			if($this->getId()!=static::HOMEPAGE_ID) {
				$relative_path_fragment_field = new Form_Field_Input('relative_path_fragment', 'URL:', rawurldecode($page->getRelativePathFragment()));
				$relative_path_fragment_field->setIsRequired(true);
				$relative_path_fragment_field->setCatcher( function( $value ) use ($page) {
					$page->setRelativePathFragment( $value );
				} );
				$relative_path_fragment_field->setIsRequired( true );
				$relative_path_fragment_field->setErrorMessages([
					Form_Field_Input::ERROR_CODE_EMPTY => 'Please enter URL part'
				]);
				$relative_path_fragment_field->setValidator( function( Form_Field_Input $field  ) use ($page) {
					$value = $field->getValue();

					$value = Data_Text::removeAccents( $value );
					$value = strtolower( $value );

					$value = str_replace(' ', '-', $value);
					$value = preg_replace( '/[^a-z0-9-]/i', '', $value );
					$value = preg_replace( '~([-]{2,})~', '-', $value );

					$field->setValue( $value );


					if(!$value) {
						$field->setError( Form_Field_Input::ERROR_CODE_EMPTY );
						return false;
					}

					$parent = $page->getParent();
					if($parent) {
						foreach( $parent->getChildren() as $ch ) {
							if( $ch->getId()==$page->getId() ) {
								continue;
							}

							if( $ch->getRelativePathFragment()==$value ) {
								$field->setCustomError(
									Tr::_('URL conflicts with page <b>%page%</b>', [
										'page' => $ch->getName()
									]),
									'uri_is_not_unique'
								);

								return false;
							}
						}
					}

					return true;

				} );


				$fields[] = $relative_path_fragment_field;
			}



			$m = 0;
			foreach( $page->getMetaTags() as $meta_tag) {

				$ld_meta_tag_attribute = new Form_Field_Input('/meta_tag/'.$m.'/attribute', 'Attribute:', $meta_tag->getAttribute() );
				$fields[] = $ld_meta_tag_attribute;


				$ld_meta_tag_attribute_value = new Form_Field_Input('/meta_tag/'.$m.'/attribute_value', 'Attribute value:', $meta_tag->getAttributeValue() );
				$fields[] = $ld_meta_tag_attribute_value;


				$ld_meta_tag_content = new Form_Field_Input('/meta_tag/'.$m.'/content', 'Attribute value:', $meta_tag->getContent() );
				$fields[] = $ld_meta_tag_content;

				$m++;
			}

			for( $c=0;$c<5;$c++ ) {

				$ld_meta_tag_attribute = new Form_Field_Input('/meta_tag/'.$m.'/attribute', 'Attribute:', '' );
				$fields[] = $ld_meta_tag_attribute;


				$ld_meta_tag_attribute_value = new Form_Field_Input('/meta_tag/'.$m.'/attribute_value', 'Attribute value:', '' );
				$fields[] = $ld_meta_tag_attribute_value;



				$ld_meta_tag_content = new Form_Field_Input('/meta_tag/'.$m.'/content', 'Attribute value:', '' );
				$fields[] = $ld_meta_tag_content;

				$m++;
			}


			$u = 0;
			foreach( $page->getHttpHeaders() as $header ) {
				if(!$header) {
					continue;
				}

				$http_header_field = new Form_Field_Input('/http_headers/'.$u, '', $header);
				$fields[] = $http_header_field;

				$u++;
			}

			for( $c=0;$c<3;$c++ ) {
				$http_header_field = new Form_Field_Input('/http_headers/'.$u, '', '');
				$fields[] = $http_header_field;

				$u++;
			}

			$form = new Form(
				'page_edit_form_main',
				$fields
			);

			$form->setAction( Pages::getActionUrl('edit') );

			$this->__edit_form_main = $form;
		}

		return $this->__edit_form_main;
	}

	/**
	 * @return bool
	 */
	public function catchEditForm_main()
	{
		$form = $this->getEditForm_main();

		if(
			$form->catchInput() &&
			$form->validate()
		) {
			$form->catchData();

			$this->catchEditForm_metaTags( $form );
			$this->catchEditForm_httpHeaders( $form );

			return true;
		}

		return false;
	}

	/**
	 * @param Form $form
	 * @param string $p_f_prefix
	 *
	 */
	public function catchEditForm_metaTags( Form $form, $p_f_prefix='' )
	{
		$meta_tags = [];
		for( $m=0;$m<static::MAX_META_TAGS_COUNT;$m++ ) {
			if(!$form->fieldExists($p_f_prefix.'/meta_tag/'.$m.'/attribute')) {
				break;
			}

			$attribute = $form->field($p_f_prefix.'/meta_tag/'.$m.'/attribute')->getValue();
			$attribute_value = $form->field($p_f_prefix.'/meta_tag/'.$m.'/attribute_value')->getValue();
			$content = $form->field($p_f_prefix.'/meta_tag/'.$m.'/content')->getValue();

			if(
				!$attribute && !$attribute_value && !$content
			) {
				continue;
			}

			$meta_tag = Mvc_Factory::getPageMetaTagInstance();

			$meta_tag->setAttribute( $attribute );
			$meta_tag->setAttributeValue( $attribute_value );
			$meta_tag->setContent( $content );

			$meta_tags[] = $meta_tag;
		}

		$this->setMetaTags( $meta_tags );

	}

	/**
	 * @param Form $form
	 * @param string $p_f_prefix
	 *
	 */
	public function catchEditForm_httpHeaders( Form $form, $p_f_prefix='' )
	{
		$http_headers = [];

		for( $u=0;$u<static::MAX_HTT_HEADERS_COUNT;$u++ ) {
			if(!$form->fieldExists($p_f_prefix.'/http_headers/'.$u)) {
				break;
			}

			$http_header = $form->field($p_f_prefix.'/http_headers/'.$u)->getValue();

			if(
				$http_header &&
				!in_array($http_header, $http_headers)
			) {
				$http_headers[] = $http_header;
			}
		}

		$this->setHttpHeaders($http_headers);
	}


	/**
	 * @return Form
	 */
	public function getEditForm_content()
	{
		if(!$this->__edit_form_content) {

			$page = $this;
			/**
			 * @var Sites_Site $site
			 */
			$site = $this->getSite();

			$layout_script_name_field = new Form_Field_Select('layout_script_name', 'Layout script name:', $page->getLayoutScriptName());
			$layout_script_name_field->setErrorMessages([
				Form_Field_Select::ERROR_CODE_INVALID_VALUE => 'Invalid value'
			]);
			$layout_script_name_field->setCatcher( function( $value ) use ($page) {
				$page->setLayoutScriptName( $value );
			} );
			$layout_script_name_field->setSelectOptions( $site->getLayoutsList() );

			$fields = [
				$layout_script_name_field
			];

			$form = new Form(
				'page_edit_form_content',
				$fields
			);

			$i = 0;
			foreach( $this->content as $content ) {
				$content_form = $content->getEditForm( $this );

				foreach( $content_form->getFields() as $field ) {
					if(substr($field->getName(), 0, 9)!='/content/') {
						if($field->getName()[0]!='/') {
							$field->setName( '/content/'.$i.'/'.$field->getName() );
						} else {
							$field->setName( '/content/'.$i.$field->getName() );
						}
					}


					$form->addField( $field );
				}

				$i++;
			}

			$form->setAction( Pages::getActionUrl('edit') );

			$this->__edit_form_content = $form;

		}

		return $this->__edit_form_content;
	}

	/**
	 * @return bool
	 */
	public function catchEditForm_content()
	{
		$form = $this->getEditForm_content();

		if(
			$form->catchInput() &&
			$form->validate()
		) {

			$form->catchData();

			$this->output = '';

			foreach($this->content as $i=>$content) {
				$content->setParameters(
					Pages_Page_Content::catchParams( $form, '/content/'.$i )
				);
			}

			$this->sortContent();

			return true;
		}

		return false;
	}


	/**
	 *
	 */
	public function sortContent()
	{
		$i = 0;
		$positions = [];

		foreach( $this->getContent() as $content ) {

			$position = $content->getOutputPosition();

			if( !isset( $positions[$position] ) ) {
				$positions[$position] = [];
			}

			$positions[$position][$i] = $content;

			$i++;
		}

		foreach( $positions as $position=>$pd ) {
			uasort(
				$positions[$position],
				function( Pages_Page_Content $a, Pages_Page_Content $b ) {
					$a_p = $a->getOutputPositionOrder();
					$b_p = $b->getOutputPositionOrder();

					if($a_p==$b_p) {
						return 0;
					}

					if($a_p>$b_p) {
						return 1;
					}

					return -1;
				}
			);

			$c=0;
			foreach( $positions[$position] as $content ) {
				/**
				 * @var Pages_Page_Content $content
				 */
				$c++;
				$content->setOutputPositionOrder($c);
			}
		}

	}





	/**
	 * @return Form
	 */
	public function getDeleteContentForm()
	{
		if(!$this->__delete_content_form) {
			$index_field = new Form_Field_Hidden('index');

			$form = new Form('delete_content_form', [$index_field]);

			$form->setAction( Pages::getActionUrl('content/delete') );

			$this->__delete_content_form = $form;
		}

		return $this->__delete_content_form;
	}

	/**
	 * @return Pages_Page_Content|null
	 */
	public function catchDeleteContentForm()
	{
		$form = $this->getDeleteContentForm();

		$old_content = null;

		if(
			$form->catchInput() &&
			$form->validate()
		) {
			$index = (int)$form->getField('index')->getValue();

			if( isset($this->content[$index]) ) {
				$old_content = $this->content[$index];
			}

			unset( $this->content[$index] );

			$this->content = array_values( $this->content );

			$this->sortContent();
		}

		return $old_content;
	}



	/**
	 * @return Form
	 */
	public function getEditForm_static_content()
	{
		if(!$this->__edit_form_static_content) {

			$output = $this->getOutput();

			$output_field = new Form_Field_Textarea('output', 'Static page content:', is_string($output) ? $output : '');
			$output_field->setCatcher( function( $value ) use ( $output_field) {
				$value = $output_field->getValueRaw();

				$this->content = [];
				$this->output = $value;
			} );

			$form = new Form(
				'page_edit_form_main',
				[
					$output_field
				]
			);

			$form->setAction( Pages::getActionUrl('edit') );

			$this->__edit_form_static_content = $form;

		}

		return $this->__edit_form_static_content;
	}

	/**
	 * @return bool
	 */
	public function catchEditForm_static_content()
	{
		$form = $this->getEditForm_static_content();

		if(
			$form->catchInput() &&
			$form->validate()
		) {
			$form->catchData();

			return true;
		}

		return false;
	}



	/**
	 * @return Form
	 */
	public function getEditForm_callback()
	{
		if(!$this->__edit_form_callback) {

			$output = $this->getOutput();

			$output_callback_class_field = new Form_Field_Input('output_callback_class', 'Output callback class:',is_array($output) && isset($output[0]) ? $output[0] : '');
			$output_callback_method_field = new Form_Field_Input('output_callback_method', 'Output callback method:',is_array($output) && isset($output[1]) ? $output[1] : '');
			$output_callback_method_field->setCatcher( function( $value ) use ($output_callback_class_field, $output_callback_method_field) {

				$this->content = [];

				$class = $output_callback_class_field->getValue();
				$method = $output_callback_method_field->getValue();

				if( $class && $method ) {
					$this->setOutput( [$class, $method] );
				} else {
					$this->setOutput( '' );
				}
			} );

			$form = new Form(
				'page_edit_form_callback',
				[
					$output_callback_class_field,
					$output_callback_method_field
				]
			);

			$form->setAction( Pages::getActionUrl('edit') );

			$this->__edit_form_callback = $form;

		}

		return $this->__edit_form_callback;
	}

	/**
	 * @return bool
	 */
	public function catchEditForm_callback()
	{
		$form = $this->getEditForm_callback();

		if(
			$form->catchInput() &&
			$form->validate()
		) {
			$form->catchData();

			return true;
		}

		return false;
	}


	/**
	 * @return string
	 */
	public function getDataDirPath()
	{
		if(!$this->getParent()) {
			return Sites_Site::get( $this->site_id )->getPagesDataPath( $this->locale );
		} else {
			return $this->getParent()->getDataDirPath().rawurldecode($this->relative_path_fragment).'/';
		}
	}

	/**
	 * @return string
	 */
	public function getOriginalDataDirPath()
	{
		if(!$this->getParent()) {
			return Sites_Site::get( $this->site_id )->getPagesDataPath( $this->locale );
		} else {
			return $this->getParent()->getDataDirPath().rawurldecode($this->original_relative_path_fragment).'/';
		}
	}


	/**
	 *
	 */
	public function saveDataFile()
	{
		if($this->relative_path_fragment!=$this->original_relative_path_fragment) {

			$page_dir = $this->getDataDirPath();
			$original_page_dir = $this->getOriginalDataDirPath();
			var_dump($original_page_dir, $page_dir);

			IO_Dir::rename( $original_page_dir, $page_dir );
		}

		$data = $this->toArray();
		unset( $data['relative_path_fragment'] );
		unset( $data['original_relative_path_fragment'] );

		$page_dir = $this->getDataDirPath();

		$data_file_path = $page_dir.static::getPageDataFileName();

		IO_File::write(
			$data_file_path,
			'<?php'.PHP_EOL.'return '.(new Data_Array( $data ))->export()
		);

		if(function_exists('opcache_reset')) {
			opcache_reset();
		}
	}

	/**
	 * @return bool
	 */
	public function save()
	{
		$ok = true;
		try {
			$this->saveDataFile();
		} catch( Exception $e ) {
			$ok = false;
			Application::handleError( $e );
		}

		return $ok;
	}


	/**
	 * @param Mvc_Page_Content_Interface $content
	 */
	public function addContent( Mvc_Page_Content_Interface $content )
	{

		parent::addContent( $content );
		$this->sortContent();
	}


	/**
	 * @param int $index
	 */
	public function removeContent( $index )
	{
		parent::removeContent( $index );

		$this->sortContent();
	}

	/**
	 * @param string $site_id
	 * @param string $page_id
	 * @param array $data
	 *
	 * @return Pages_Page
	 */
	public static function fromArray( $site_id, $page_id, array $data )
	{
		$page = new Pages_Page();

		$page->setSiteId( $site_id );
		$page->setId( $page_id );

		foreach( $data as $key=>$val ) {
			if(
				$key=='contents'
			) {
				$page->content = [];
				foreach( $val as $c_d ) {
					$page->content[] = Pages_Page_Content::fromArray( $c_d );
				}
				continue;
			}

			if(
				$key=='meta_tags'
			) {
				$page->meta_tags = [];
				foreach( $val as $m_d ) {
					$meta_tag = new Mvc_Page_MetaTag();
					$meta_tag->setAttribute( $m_d['attribute'] );
					$meta_tag->setAttributeValue( $m_d['attribute_value'] );
					$meta_tag->setContent( $m_d['content'] );

					$page->meta_tags[] = $meta_tag;
				}

				continue;
			}

			$page->{$key} = $val;
		}

		if(!$page->name) {
			$page->name = $page->title;
		}

		if(!$page->title) {
			$page->title = $page->name;
		}


		return $page;
	}

}