<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;


/**
 *
 */
class UI_tree extends BaseObject
{
	/**
	 * @var string
	 */
	protected static string $default_renderer_script = 'tree';

	/**
	 * @var ?string
	 */
	protected ?string $renderer_script = null;

	/**
	 * @var ?Data_Tree
	 */
	protected ?Data_Tree $data = null;

	/**
	 * @var string
	 */
	protected string $selected_id = '';

	/**
	 * @var string
	 */
	protected string $root_id = '';

	/**
	 * @var bool
	 */
	protected bool $show_all = false;

	/**
	 * @var callable
	 */
	protected $renderer_selected;

	/**
	 * @var callable
	 */
	protected $renderer_opened;

	/**
	 * @var callable
	 */
	protected $renderer_normal;

	/**
	 * @return string
	 */
	public static function getDefaultRendererScript() : string
	{
		return static::$default_renderer_script;
	}

	/**
	 * @param string $default_renderer_script
	 */
	public static function setDefaultRendererScript( string $default_renderer_script ) : void
	{
		static::$default_renderer_script = $default_renderer_script;
	}

	/**
	 * @return string
	 */
	public function getRendererScript() : string
	{
		if(!$this->renderer_script) {
			$this->renderer_script = static::getDefaultRendererScript();
		}

		return $this->renderer_script;
	}

	/**
	 * @param string $renderer_script
	 */
	public function setRendererScript( string $renderer_script ) : void
	{
		$this->renderer_script = $renderer_script;
	}

	/**
	 * @param Data_Tree_Node $node
	 *
	 * @return bool
	 */
	public function nodeFilter( Data_Tree_Node $node ) : bool
	{

		$tree_data = $this->getData();

		$selected_path = $this->getSelectedPath();

		$root_id = $this->getRootId();

		if( $root_id ) {

			$node_path = $tree_data->getPath( $node->getId() );

			if( !in_array( $root_id, $node_path ) ) {
				return false;
			}
		}


		if(
			!$this->getShowAll() &&
			$selected_path
		) {
			if(
				!(
					in_array( $node->getParentId(), $selected_path ) ||
					in_array( $node->getId(), $selected_path )
				)
			) {
				return false;
			}
		}

		return true;
	}

	/**
	 * @return Data_Tree
	 */
	public function getData() : Data_Tree
	{
		return $this->data;
	}

	/**
	 * @param Data_Tree $data
	 */
	public function setData( Data_Tree $data ) : void
	{
		$this->data = $data;
	}

	/**
	 * @return array|bool
	 */
	protected function getSelectedPath() : array|bool
	{

		$selected_id = $this->getSelectedId();

		$tree_data = $this->getData();

		$path = $selected_id ? $tree_data->getPath( $selected_id ) : false;
		$path = $path ? $path : [ $tree_data->getRootNode()->getId() ];

		return $path;
	}

	/**
	 * @return string
	 */
	public function getSelectedId() : string
	{
		return $this->selected_id;
	}

	/**
	 * @param string $selected_id
	 */
	public function setSelectedId( string $selected_id ) : void
	{
		$this->selected_id = $selected_id;
	}

	/**
	 * @return string
	 */
	public function getRootId() : string
	{
		return $this->root_id;
	}

	/**
	 * @param string $root_id
	 */
	public function setRootId( string $root_id )  :void
	{
		$this->root_id = $root_id;
	}

	/**
	 * @return bool
	 */
	public function getShowAll() : bool
	{
		return $this->show_all;
	}

	/**
	 * @param bool $show_all
	 */
	public function setShowAll( bool $show_all ) : void
	{
		$this->show_all = $show_all;
	}


	/**
	 * @param Data_Tree_Node $node
	 *
	 * @return bool
	 */
	public function nodeSelected( Data_Tree_Node $node ) : bool
	{
		return ( $node->getId()==$this->getSelectedId() );
	}

	/**
	 * @param Data_Tree_Node $node
	 *
	 * @return bool
	 */
	public function nodeOpened( Data_Tree_Node $node ) : bool
	{
		return in_array( $node->getId(), $this->getSelectedPath() );
	}

	/**
	 * @param Data_Tree_Node $node
	 *
	 * @return callable
	 *
	 * @throws Exception
	 */
	public function getNodeRenderer( Data_Tree_Node $node ) : callable
	{
		$renderer = $this->getRendererNormal();
		if(!$renderer) {
			throw new Exception('Renderer for normal tree node is not defined');
		}

		if( $this->nodeSelected( $node ) ) {
			$renderer = $this->getRendererSelected();
			if(!$renderer) {
				throw new Exception('Renderer for selected tree node is not defined');
			}
		} else if( $this->nodeOpened( $node ) ) {
			$renderer = $this->getRendererOpened();
			if(!$renderer) {
				throw new Exception('Renderer for opened tree node is not defined');
			}
		}

		return $renderer;
	}

	/**
	 * @return callable
	 */
	public function getRendererNormal() : callable
	{
		return $this->renderer_normal;
	}

	/**
	 * @param callable $renderer
	 */
	public function setRendererNormal( callable $renderer ) : void
	{
		$this->renderer_normal = $renderer;
	}

	/**
	 * @return callable
	 */
	public function getRendererSelected() : callable
	{
		return $this->renderer_selected;
	}

	/**
	 * @param callable $renderer
	 */
	public function setRendererSelected( callable $renderer ) : void
	{
		$this->renderer_selected = $renderer;
	}

	/**
	 * @return callable
	 */
	public function getRendererOpened() : callable
	{
		return $this->renderer_opened;
	}

	/**
	 * @param callable $renderer
	 */
	public function setRendererOpened( callable $renderer ) : void
	{
		$this->renderer_opened = $renderer;
	}

	/**
	 * @return Mvc_View
	 */
	public function getView() : Mvc_View
	{
		$view = UI::getView();
		$view->setVar( 'tree', $this );

		return $view;
	}

	/**
	 * @return string
	 */
	public function __toString() : string
	{
		return $this->toString();
	}

	/**
	 * @return string
	 */
	public function toString() : string
	{
		return $this->render();
	}

	/**
	 * @return string
	 */
	public function render() : string
	{
		return $this->getView()->render( $this->getRendererScript() );
	}

}