<?php
/**
 *
 *
 *
 * @copyright Copyright (c) 2014 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package DataModel
 * @subpackage DataModel_Cache
 */
namespace Jet;

class DataModel_Cache_Backend_Memcache extends DataModel_Cache_Backend_Abstract {
	const KEYS_LIST_KEY = "__keys__";

	/**
	 * @var DataModel_Cache_Backend_Memcache_Config
	 */
	protected $config;

	/**
	 *
	 * @var Memcache_Connection_Abstract
	 */
	private $memcache = null;

	/**
	 * @var string
	 */
	protected $key_prefix = "";


	public function initialize() {
		$this->memcache = Memcache::get($this->config->getConnection());

		$this->key_prefix = $this->config->getKeyPrefix().":";
	}

	/**
	 * @param DataModel $data_model
	 * @param $ID
	 *
	 * @return string
	 */
	protected function getCacheKey( DataModel $data_model, $ID ) {
		return $this->key_prefix.$data_model->getDataModelName().":".$ID;
	}

	/**
	 * @param DataModel $data_model
	 * @param string $ID
	 *
	 * @return bool|mixed
	 */
	public function get( DataModel $data_model, $ID) {
		$data = $this->memcache->get( $this->getCacheKey($data_model, $ID) );

		if(!$data) {
			return false;
		}

		return unserialize($data);
	}

	/**
	 * @param DataModel $data_model
	 * @param string $ID
	 * @param mixed $data
	 */
	public function save(DataModel $data_model, $ID, $data) {
		$key = $this->getCacheKey($data_model, $ID);
		$this->memcache->set(
				$key,
				serialize($data)
			);
		$this->storeKey($key);
	}

	/**
	 * @param DataModel $data_model
	 * @param string $ID
	 * @param mixed $data
	 */
	public function update(DataModel $data_model, $ID, $data) {
		$key = $this->getCacheKey($data_model, $ID);

		$this->memcache->replace(
			$key,
			serialize($data)
		);
		$this->storeKey($key);
	}

	/**
	 * @param DataModel $data_model
	 * @param string $ID
	 */
	public function delete(DataModel $data_model, $ID) {
		$key = $this->getCacheKey($data_model, $ID);
		$this->memcache->delete( $key );
		$this->removeKey( $key );
	}


	/**
	 * @param null|string $model_name (optional)
	 */
	public function truncate( $model_name=null ) {
		$list = $this->getKeysList();

		if(!$model_name) {
			foreach($list as $key) {
				$this->memcache->delete($key);
			}
		} else {
			$prefix = $this->key_prefix.$model_name.":";
			$prefix_len = strlen($prefix);

			foreach($list as $key) {
				if(substr($key, 0, $prefix_len)==$prefix) {
					$this->memcache->delete($key);
				}
			}
		}
	}

	/**
	 * @return string
	 */
	public function helper_getCreateCommand() {
		return "";
	}

	/**
	 *
	 */
	public function helper_create() {
	}

	/**
	 * @param string $key
	 */
	protected function storeKey( $key ) {

		$list = $this->getKeysList();

		if(!in_array($key, $list)) {
			$list[] = $key;

			$list_key = $this->key_prefix.static::KEYS_LIST_KEY;
			$this->memcache->set( $list_key, serialize($list) );
		}
	}

	/**
	 * @param string $key
	 */
	protected function removeKey( $key ) {
		$list = $this->getKeysList();
		if( ($index = array_search($key, $list))!==false ) {
			unset($list[$index]);
			$list_key = $this->key_prefix.static::KEYS_LIST_KEY;
			$this->memcache->set( $list_key, serialize($list) );
		}
	}

	/**
	 * @return array
	 */
	protected function getKeysList() {
		$list_key = $this->key_prefix.static::KEYS_LIST_KEY;

		$list = $this->memcache->get( $list_key );
		if(!$list) {
			$list = array();
		} else {
			$list = unserialize($list);
		}

		return $list;
	}

}