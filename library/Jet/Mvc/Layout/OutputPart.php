<?php
/**
 *
 *
 *
 * Layout output data class
 *
 * @see Mvc_Layout
 *
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Mvc
 * @subpackage Mvc_Layout
 */
namespace Jet;

class Mvc_Layout_OutputPart extends BaseObject{
	/**
	 * @var string
	 */
	protected $output_ID = '';

	/**
	 *
	 * @var string
	 */
	protected $output = '';

	/**
	 *
	 * @var string
	 */
	protected $position = '';

	/**
	 *
	 * @var bool
	 */
	protected $position_required = false;

	/**
	 *
	 * @var float
	 */
	protected $position_order = 0.0;

	/**
	 *
	 * @var string
	 */
	protected $module_name = '';

	/**
	 * @param string $content_ID
	 * @param string $output
	 * @param string $position
	 * @param bool $position_required
	 * @param int $position_order
	 * @param string $module_name
	 */
	public function __construct($content_ID, $output, $position, $position_required, $position_order, $module_name) {
		$this->output_ID = $content_ID;
		$this->output = $output;
		$this->position = $position;
		$this->position_required = $position_required;
		$this->position_order = $position_order;
		$this->module_name = $module_name;
	}

	/**
	 * @return string
	 */
	public function getOutputID() {
		return $this->output_ID;
	}

	/**
	 * @return string
	 */
	public function getOutput() {
		return $this->output;
	}

	/**
	 * @param $output
	 */
	public function setOutput($output) {
		$this->output = $output;
	}

	/**
	 * @return string
	 */
	public function getPosition() {
		return $this->position;
	}

	/**
	 * @param string $position
	 */
	public function setPosition($position) {
		$this->position = $position;
	}

	/**
	 * @return bool
	 */
	public function getPositionRequired() {
		return $this->position_required;
	}

	/**
	 * @param bool $position_required
	 */
	public function setPositionRequired($position_required) {
		$this->position_required = (bool)$position_required;
	}

	/**
	 * @return int
	 */
	public function getPositionOrder() {
		return $this->position_order;
	}

	/**
	 * @param int $position_order
	 */
	public function setPositionOrder($position_order) {
		$this->position_order = (int)$position_order;
	}

	/**
	 * @return string
	 */
	public function getModuleName() {
		return $this->module_name;
	}

	/**
	 * @param string $module_name
	 */
	public function setModuleName($module_name) {
		$this->module_name = $module_name;
	}

}