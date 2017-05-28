<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplicationModule\JetExample\Articles;

use Jet\DataModel;
use Jet\DataModel_Related_1toN;
use Jet\DataModel_Related_1toN_Iterator;
use Jet\Locale;
use Jet\Data_DateTime;
use Jet\Mvc;
use Jet\Mvc_Site;
use Jet\Mvc_Router_Interface;
use Jet\Data_Text;
use Jet\DataModel_Fetch_Object_Assoc;
use Jet\Data_Paginator_DataSource;
use Jet\DataModel_Id_UniqueString;
use Jet\Form;
use Jet\Form_Field_Input;
use Jet\Form_Field_Select;
use Jet\Form_Field_DateTime;

/**
 *
 * @JetDataModel:name = 'articles'
 * @JetDataModel:database_table_name = 'articles'
 * @JetDataModel:id_class_name = 'DataModel_Id_UniqueString'
 */
class Article extends DataModel
{


	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_ID
	 * @JetDataModel:is_id = true
	 *
	 * @var string
	 */
	protected $id = '';

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_DATE_TIME
	 * @JetDataModel:form_field_label = 'Date and time'
	 * @JetDataModel:form_field_error_messages = [Form_Field_DateTime::ERROR_CODE_INVALID_FORMAT => 'Invalid date and time format']
	 *
	 * @var Data_DateTime
	 */
	protected $date_time;

	/**
	 * @JetDataModel:type = DataModel::TYPE_DATA_MODEL
	 * @JetDataModel:data_model_class = 'Article_Localized'
	 *
	 * @var Article_Localized[]|DataModel_Related_1toN|DataModel_Related_1toN_Iterator
	 */
	protected $localized;


	/**
	 *
	 */
	public function __construct()
	{
		parent::__construct();
		$this->afterLoad();
	}

	/**
	 *
	 */
	public function afterLoad()
	{

		foreach( Mvc_Site::getAllLocalesList(false) as $lc_str => $locale) {

			if (!isset($this->localized[$lc_str])) {

				$this->localized[$lc_str] = new Article_Localized($this->getId(), $locale);
			}

			$this->localized[$lc_str]->setArticle( $this );
		}

	}



	/**
	 *
	 * @param string $id
	 *
	 * @return Article
	 */
	public static function get( $id )
	{

		/** @noinspection PhpIncompatibleReturnTypeInspection */
		return static::load( $id );
	}

	/**
	 *
	 * @param string $search
	 *
	 * @return Article[]|DataModel_Fetch_Object_Assoc
	 */
	public static function getList( $search = '' )
	{

		$where = [];

		if( $search ) {
			$search = '%'.$search.'%';

			$where[] = [
				'articles_localized.title *' => $search,
				'OR',
				'articles_localized.text *' => $search,
				'OR',
				'articles_localized.annotation *' => $search,
			];
		}

		/**
		 * @var DataModel_Fetch_Object_Assoc $list
		 */
		$list = static::fetchObjects(
			$where,
			[
				'articles.id',
				'articles.date_time',
				'articles_localized.title',
			]
		);

		return $list;
	}

	/**
	 * @return string
	 */
	public function getId()
	{
		if(!$this->id) {
			$this->getIdObject()->generate();
		}

		return $this->id;
	}



	/**
	 * @return Data_DateTime
	 */
	public function getDateTime()
	{
		return $this->date_time;
	}

	/**
	 * @param Locale|null $locale
	 *
	 * @return Article_Localized
	 */
	public function getLocalized( Locale $locale=null )
	{
		if(!$locale) {
			$locale = Mvc::getCurrentLocale();
		}
		return $this->localized[$locale->toString()];
	}

	/**
	 * @param Data_DateTime|string $date_time
	 */
	public function setDateTime( $date_time )
	{
		if( !( $date_time instanceof Data_DateTime ) ) {
			$date_time = new Data_DateTime( $date_time );
		}
		$this->date_time = $date_time;
	}

	/**
	 * @return Article[]|Data_Paginator_DataSource
	 */
	public static function getListForCurrentLocale()
	{
		$list = static::fetchObjects(
			[
				'articles_localized.locale' => Mvc::getCurrentLocale(),
			]
		);
		$list->getQuery()->setOrderBy( '-date_time' );

		return $list;
	}

	/**
	 * @param string        $path
	 * @param string|Locale $locale
	 *
	 * @return Article|null
	 */
	public static function resolveArticleByURL( $path, $locale )
	{
		$current_article = null;
		if( substr( $path, -5 )=='.html' ) {

			$current_article = static::fetchOneObject(
				[
					'articles_localized.URI_fragment' => $path,
					'AND',
					'articles_localized.locale' => $locale
				]
			);

		}

		/**
		 * @var Article $current_article
		 */
		return $current_article;
	}

	/**
	 * @return string
	 */
	public function getUrl()
	{
		return $this->getLocalized()->getURL();
	}

	/**
	 * @return string
	 */
	public function getTitle()
	{
		return $this->getLocalized()->getTitle();
	}



	/**
	 * @return string
	 */
	public function getAnnotation()
	{
		return $this->getLocalized()->getAnnotation();
	}


	/**
	 * @return string
	 */
	public function getText()
	{
		return $this->getLocalized()->getText();
	}

}