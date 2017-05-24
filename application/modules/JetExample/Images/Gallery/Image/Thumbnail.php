<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplicationModule\JetExample\Images;

use Jet\DataModel;
use Jet\DataModel_Id_UniqueString;
use Jet\DataModel_Related_1toN;
use Jet\Data_Image;
use Jet\Data_Image_Exception;
use Jet\IO_File;

/**
 *
 * @JetDataModel:name = 'Image_Thumbnails'
 * @JetDataModel:database_table_name = 'image_galleries_images_thumbnails'
 * @JetDataModel:parent_model_class_name = 'Gallery_Image'
 * @JetDataModel:id_class_name = 'DataModel_Id_UniqueString'
 */
class Gallery_Image_Thumbnail extends DataModel_Related_1toN
{

	/**
	 * @JetDataModel:related_to = 'main.id'
	 */
	protected $image_id;

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_ID
	 * @JetDataModel:form_field_is_required = true
	 * @JetDataModel:is_id = true
	 *
	 * @var string
	 */
	protected $id = '';

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_INT
	 * @JetDataModel:form_field_is_required = true
	 * @JetDataModel:form_field_type = false
	 *
	 * @var int
	 */
	protected $maximal_size_w = 0;

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_INT
	 * @JetDataModel:form_field_is_required = true
	 * @JetDataModel:form_field_type = false
	 *
	 * @var int
	 */
	protected $maximal_size_h = 0;

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_INT
	 * @JetDataModel:form_field_is_required = true
	 * @JetDataModel:form_field_type = false
	 *
	 * @var int
	 */
	protected $real_size_w = 0;

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_INT
	 * @JetDataModel:form_field_is_required = true
	 * @JetDataModel:form_field_type = false
	 *
	 * @var int
	 */
	protected $real_size_h = 0;

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_INT
	 * @JetDataModel:form_field_is_required = true
	 * @JetDataModel:form_field_type = false
	 *
	 * @var int
	 */
	protected $file_size = 0;

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_STRING
	 * @JetDataModel:max_len = 255
	 * @JetDataModel:form_field_type = false
	 *
	 * @var string
	 */
	protected $file_mime_type = '';

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_STRING
	 * @JetDataModel:max_len = 255
	 * @JetDataModel:form_field_type = false
	 *
	 * @var string
	 */
	protected $file_name = '';

	/**
	 * @var Gallery_Image
	 */
	protected $__image;

	/**
	 * @param Gallery_Image $image
	 * @param int           $maximal_size_w
	 * @param int           $maximal_size_h
	 *
	 * @throws Exception
	 * @return Gallery_Image_Thumbnail
	 */
	public static function getNewThumbnail( Gallery_Image $image, $maximal_size_w, $maximal_size_h )
	{

		$maximal_size_w = (int)$maximal_size_w;
		$maximal_size_h = (int)$maximal_size_h;

		$key = static::createKey( $maximal_size_w, $maximal_size_h );

		/**
		 * @var Gallery_Image_Thumbnail $thumbnail
		 */
		$thumbnail = new static();

		$thumbnail->file_name = $key.'_'.$image->getFileName();

		try {
			$image_file = new Data_Image( $image->getFilePath() );

			$target_path = $image->getThumbnailsDirPath().$thumbnail->file_name;

			$created_image_file = $image_file->createThumbnail( $target_path, $maximal_size_w, $maximal_size_h );

			$thumbnail->real_size_w = $created_image_file->getWidth();
			$thumbnail->real_size_h = $created_image_file->getHeight();

			$thumbnail->maximal_size_w = $maximal_size_w;
			$thumbnail->maximal_size_h = $maximal_size_h;

			$thumbnail->file_name = $created_image_file->getFileName();
			$thumbnail->file_mime_type = $created_image_file->getMimeType();
			$thumbnail->file_size = IO_File::getSize( $target_path );

			$thumbnail->__image = $image;

		} catch( Data_Image_Exception $e ) {
			return null;
		}

		return $thumbnail;
	}

	/**
	 * @param int $maximal_size_w
	 * @param int $maximal_size_h
	 *
	 * @return string
	 * @throws Exception
	 */
	public static function createKey( $maximal_size_w, $maximal_size_h )
	{
		$maximal_size_w = (int)$maximal_size_w;
		$maximal_size_h = (int)$maximal_size_h;

		if( !$maximal_size_w || !$maximal_size_h ) {
			throw new Exception(
				'Dimensions of Image thumbnail must be greater then 0! Given values: w:'.$maximal_size_w.', h:'.$maximal_size_h,
				Exception::CODE_ILLEGAL_THUMBNAIL_DIMENSION
			);
		}

		return $maximal_size_w.'x'.$maximal_size_h;
	}

	/**
	 * @return string
	 */
	public function getArrayKeyValue()
	{
		return static::createKey( $this->maximal_size_w, $this->maximal_size_h );
	}

	/**
	 * @return int
	 */
	public function getFileMimeType()
	{
		return $this->file_mime_type;
	}

	/**
	 * @return int
	 */
	public function getFileSize()
	{
		return $this->file_size;
	}

	/**
	 * @return int
	 */
	public function getMaximalSizeH()
	{
		return $this->maximal_size_h;
	}

	/**
	 * @return int
	 */
	public function getMaximalSizeW()
	{
		return $this->maximal_size_w;
	}

	/**
	 * @return int
	 */
	public function getRealSizeH()
	{
		return $this->real_size_h;
	}

	/**
	 * @return int
	 */
	public function getRealSizeW()
	{
		return $this->real_size_w;
	}

	/**
	 * @return string
	 */
	public function getURI()
	{
		return $this->__image->getThumbnailsBaseURI().rawurlencode( $this->getFileName() );
	}

	/**
	 * @return string
	 */
	public function getFileName()
	{
		return $this->file_name;
	}

	/**
	 * @param Gallery_Image $image
	 */
	public function setImage( Gallery_Image $image )
	{
		$this->__image = $image;
	}

	/**
	 *
	 */
	public function recreate()
	{

		$image_file = new Data_Image( $this->__image->getFilePath() );

		$target_path = $this->getPath();

		$created_image_file = $image_file->createThumbnail(
			$target_path, $this->maximal_size_w, $this->maximal_size_h
		);

		$this->real_size_w = $created_image_file->getWidth();
		$this->real_size_h = $created_image_file->getHeight();
		$this->file_mime_type = $created_image_file->getMimeType();
		$this->file_size = IO_File::getSize( $target_path );

	}

	/**
	 * @return string
	 */
	public function getPath()
	{
		return $this->__image->getThumbnailsDirPath().$this->getFileName();
	}

}