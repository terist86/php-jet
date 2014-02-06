<?php
/**
 *
 *
 * @copyright Copyright (c) 2012-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category JetApplicationModule
 * @package JetApplicationModule\Articles
 */
namespace JetApplicationModule\JetExample\Articles;
use Jet;

class Controller_Public_Standard extends Jet\Mvc_Controller_Standard {
	/**
	 *
	 * @var Main
	 */
	protected $module_instance = NULL;

	protected static $ACL_actions_check_map = array(
		'default' => false
	);


	public function default_Action() {
		$article = new Article();
		$current_article = $article->resolveArticleByURL( $this->router );

		if($current_article) {
			Jet\Mvc::getCurrentUIManagerModuleInstance()->addBreadcrumbNavigationData($current_article->getTitle());

			$this->view->setVar('article', $current_article);

			$this->render('detail');
		} else {

			$paginator = new Jet\Data_Paginator(
				Jet\Mvc::parsePathFragmentIntValue( 'page:%VAL%', 1 ),
				5,
				Jet\Mvc::getCurrentURI().'page:'.Jet\Data_Paginator::URL_PAGE_NO_KEY.'/'
			);

			$paginator->setDataSource( $article->getListForCurrentLocale() );

			$this->view->setVar('articles_list', $paginator->getData());
			$this->view->setVar('paginator', $paginator);

			$this->render('list');
		}

	}
}