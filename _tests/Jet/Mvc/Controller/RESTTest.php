<?php
namespace Jet;

function header_test_rest( $header, $replace=true, $http_response_code=0 ) {
	$GLOBALS['_test_Http_Headers_sent_headers'][] = [
		'header' => $header,
		'replace' => $replace,
		'http_response_code' => $http_response_code
	];
}

/** @noinspection PhpMultipleClassesDeclarationsInOneFile */
class Mvc_Controller_REST_Test extends Mvc_Controller_REST {
	/** @noinspection PhpMissingParentConstructorInspection */
	public function __construct() {

	}

	/**
	 * Is called after controller instance is created
	 */
	public function initialize() {
	}
}

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.0 on 2013-12-13 at 13:46:46.
 */

/** @noinspection PhpMultipleClassesDeclarationsInOneFile */
class Mvc_Controller_RESTTest extends \PHPUnit_Framework_TestCase {
	/**
	 * @var Mvc_Controller_REST_Test
	 */
	protected $object;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp() {

		Http_Headers::setHeaderFunctionName('\Jet\header_test_rest');
		$GLOBALS['_test_Http_Headers_sent_headers'] = [];

		$this->object = new Mvc_Controller_REST_Test;
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 */
	protected function tearDown() {
	}

	/**
	 * @covers Jet\Mvc_Controller_REST::decodeRequestDataJSON
	 */
	public function testDecodeRequestDataJSON() {
		$data = [];
		$data['a'] = [
			'int' => 1234,
			'float' => 3.14,
			'array' => [
					'string' => 'String "string" \'string\' ',
			]
		];

		$json = json_encode( $data );

		$this->assertEquals( $data, $this->object->decodeRequestDataJSON( $json ) );
	}

	/**
	 * @covers Jet\Mvc_Controller_REST::decodeRequestDataXML
	 */
	public function testDecodeRequestDataXML() {

		$data = $this->object->decodeRequestDataXML('<Jet_ImageGallery>
											<!--  Type: ID, required: no, is ID  -->
											<ID>linux_2m_MF137301720954251d69479847362_23093172</ID>
											<!--  Type: ID, required: yes  -->
											<parent_ID>_root_</parent_ID>
											<!--  Type: String, max length: 100, required: yes  -->
											<title>Gellery 3</title>
											<comment>aaa</comment>

											<item>
												<str_val>string</str_val>
												<number>number</number>
												<comment>aaa aaa</comment>

												<title/>
											</item>
										</Jet_ImageGallery>');

		$this->assertEquals([
			'comment' => 'aaa',
			'ID' => 'linux_2m_MF137301720954251d69479847362_23093172',
			'parent_ID' => '_root_',
			'title' => 'Gellery 3',
			'item' =>
			[
				'str_val' => 'string',
				'number' => 'number',
				'comment' => 'aaa aaa',
				'title' => '',
			],
		], $data);

		$data = $this->object->decodeRequestDataXML('<list model_name=\'Jet_ImageGallery_Images\'>
											<item>
												<ID>linux_2m_MF137301722026651d69484411740_57557045</ID>
												<gallery_ID>linux_2m_MF137301720954251d69479847362_23093172</gallery_ID>
												<offset>1</offset>
												<title/>
												<file_name>Autumn Leaves.jpg</file_name>
												<file_mime_type>image/jpeg</file_mime_type>
												<file_size>276216</file_size>
												<image_size_w>1024</image_size_w>
												<image_size_h>768</image_size_h>
												<thumbnail_URI>
												/public/imagegallery/1/linux_2m_MF137301722026651d69484411740_57557045/_t_/100x100_Autumn%20Leaves.jpg
												</thumbnail_URI>
											</item>
											<item>
												<ID>linux_2m_MF137301723749651d69495794e07_69873685</ID>
												<gallery_ID>linux_2m_MF137301720954251d69479847362_23093172</gallery_ID>
												<offset>1</offset>
												<title/>
												<file_name>Creek.jpg</file_name>
												<file_mime_type>image/jpeg</file_mime_type>
												<file_size>264409</file_size>
												<image_size_w>1024</image_size_w>
												<image_size_h>768</image_size_h>
												<thumbnail_URI>
												/public/imagegallery/1/linux_2m_MF137301723749651d69495794e07_69873685/_t_/100x100_Creek.jpg
												</thumbnail_URI>
											</item>
											<item>
												<ID>linux_2m_MF137301913198551d69bfbf0a916_83671300</ID>
												<gallery_ID>linux_2m_MF137301720954251d69479847362_23093172</gallery_ID>
												<offset>1</offset>
												<title/>
												<file_name>Green Sea Turtle.jpg</file_name>
												<file_mime_type>image/jpeg</file_mime_type>
												<file_size>378729</file_size>
												<image_size_w>1024</image_size_w>
												<image_size_h>768</image_size_h>
												<thumbnail_URI>
												/public/imagegallery/1/linux_2m_MF137301913198551d69bfbf0a916_83671300/_t_/100x100_Green%20Sea%20Turtle.jpg
												</thumbnail_URI>
											</item>
											<item>
												<ID>linux_2m_MF137301915725751d69c153ef4a6_93300639</ID>
												<gallery_ID>linux_2m_MF137301720954251d69479847362_23093172</gallery_ID>
												<offset>1</offset>
												<title/>
												<file_name>Forest Flowers.jpg</file_name>
												<file_mime_type>image/jpeg</file_mime_type>
												<file_size>128755</file_size>
												<image_size_w>1024</image_size_w>
												<image_size_h>768</image_size_h>
												<thumbnail_URI>
												/public/imagegallery/1/linux_2m_MF137301915725751d69c153ef4a6_93300639/_t_/100x100_Forest%20Flowers.jpg
												</thumbnail_URI>
											</item>
											<item>
												<ID>linux_2m_MF137301923837551d69c665b95f3_32899254</ID>
												<gallery_ID>linux_2m_MF137301720954251d69479847362_23093172</gallery_ID>
												<offset>1</offset>
												<title/>
												<file_name>Waterfall.jpg</file_name>
												<file_mime_type>image/jpeg</file_mime_type>
												<file_size>287631</file_size>
												<image_size_w>1024</image_size_w>
												<image_size_h>768</image_size_h>
												<thumbnail_URI>
												/public/imagegallery/1/linux_2m_MF137301923837551d69c665b95f3_32899254/_t_/100x100_Waterfall.jpg
												</thumbnail_URI>
											</item>
											<item>
												<ID>linux_2m_MF137301928548251d69c9575b7c1_93699139</ID>
												<gallery_ID>linux_2m_MF137301720954251d69479847362_23093172</gallery_ID>
												<offset>1</offset>
												<title/>
												<file_name>Forest.jpg</file_name>
												<file_mime_type>image/jpeg</file_mime_type>
												<file_size>664489</file_size>
												<image_size_w>1024</image_size_w>
												<image_size_h>768</image_size_h>
												<thumbnail_URI>
												/public/imagegallery/1/linux_2m_MF137301928548251d69c9575b7c1_93699139/_t_/100x100_Forest.jpg
												</thumbnail_URI>
											</item>
											</list>');


		$this->assertEquals([
			0 =>
			[
				'ID' => 'linux_2m_MF137301722026651d69484411740_57557045',
				'gallery_ID' => 'linux_2m_MF137301720954251d69479847362_23093172',
				'offset' => '1',
				'title' => '',
				'file_name' => 'Autumn Leaves.jpg',
				'file_mime_type' => 'image/jpeg',
				'file_size' => '276216',
				'image_size_w' => '1024',
				'image_size_h' => '768',
				'thumbnail_URI' => '/public/imagegallery/1/linux_2m_MF137301722026651d69484411740_57557045/_t_/100x100_Autumn%20Leaves.jpg',
			],
			1 =>
			[
				'ID' => 'linux_2m_MF137301723749651d69495794e07_69873685',
				'gallery_ID' => 'linux_2m_MF137301720954251d69479847362_23093172',
				'offset' => '1',
				'title' => '',
				'file_name' => 'Creek.jpg',
				'file_mime_type' => 'image/jpeg',
				'file_size' => '264409',
				'image_size_w' => '1024',
				'image_size_h' => '768',
				'thumbnail_URI' => '/public/imagegallery/1/linux_2m_MF137301723749651d69495794e07_69873685/_t_/100x100_Creek.jpg',
			],
			2 =>
			[
				'ID' => 'linux_2m_MF137301913198551d69bfbf0a916_83671300',
				'gallery_ID' => 'linux_2m_MF137301720954251d69479847362_23093172',
				'offset' => '1',
				'title' => '',
				'file_name' => 'Green Sea Turtle.jpg',
				'file_mime_type' => 'image/jpeg',
				'file_size' => '378729',
				'image_size_w' => '1024',
				'image_size_h' => '768',
				'thumbnail_URI' => '/public/imagegallery/1/linux_2m_MF137301913198551d69bfbf0a916_83671300/_t_/100x100_Green%20Sea%20Turtle.jpg',
			],
			3 =>
			[
				'ID' => 'linux_2m_MF137301915725751d69c153ef4a6_93300639',
				'gallery_ID' => 'linux_2m_MF137301720954251d69479847362_23093172',
				'offset' => '1',
				'title' => '',
				'file_name' => 'Forest Flowers.jpg',
				'file_mime_type' => 'image/jpeg',
				'file_size' => '128755',
				'image_size_w' => '1024',
				'image_size_h' => '768',
				'thumbnail_URI' => '/public/imagegallery/1/linux_2m_MF137301915725751d69c153ef4a6_93300639/_t_/100x100_Forest%20Flowers.jpg',
			],
			4 =>
			[
				'ID' => 'linux_2m_MF137301923837551d69c665b95f3_32899254',
				'gallery_ID' => 'linux_2m_MF137301720954251d69479847362_23093172',
				'offset' => '1',
				'title' => '',
				'file_name' => 'Waterfall.jpg',
				'file_mime_type' => 'image/jpeg',
				'file_size' => '287631',
				'image_size_w' => '1024',
				'image_size_h' => '768',
				'thumbnail_URI' => '/public/imagegallery/1/linux_2m_MF137301923837551d69c665b95f3_32899254/_t_/100x100_Waterfall.jpg',
			],
			5 =>
			[
				'ID' => 'linux_2m_MF137301928548251d69c9575b7c1_93699139',
				'gallery_ID' => 'linux_2m_MF137301720954251d69479847362_23093172',
				'offset' => '1',
				'title' => '',
				'file_name' => 'Forest.jpg',
				'file_mime_type' => 'image/jpeg',
				'file_size' => '664489',
				'image_size_w' => '1024',
				'image_size_h' => '768',
				'thumbnail_URI' => '/public/imagegallery/1/linux_2m_MF137301928548251d69c9575b7c1_93699139/_t_/100x100_Forest.jpg',
			],
		], $data);
	}

	/**
	 * @covers Jet\Mvc_Controller_REST::getRequestData
	 * @todo   Implement testGetRequestData().
	 */
	public function testGetRequestData() {
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}

	/**
	 * @covers Jet\Mvc_Controller_REST::responseOK
	 */
	public function testResponseOKJSON() {
		$this->object->setResponseFormat( Mvc_Controller_REST::RESPONSE_FORMAT_JSON );

		ob_start();
		$this->object->responseOK();
		$d = ob_get_contents();
		ob_end_clean();

		//var_export($GLOBALS['_test_Http_Headers_sent_headers']);
		//var_export( $d );

		$this->assertEquals( [
			[
				'header' => 'HTTP/1.1 200 OK',
				'replace' => true,
				'http_response_code' => 200,
			],
			[
				'header' => 'Content-type:application/json;charset=UTF-8',
				'replace' => true,
				'http_response_code' => 0,
			],
		], $GLOBALS['_test_Http_Headers_sent_headers'] );

		$this->assertEquals('"OK"', $d );
	}

	/**
	 * @covers Jet\Mvc_Controller_REST::responseOK
	 */
	public function testResponseOKXML() {
		$this->object->setResponseFormat( Mvc_Controller_REST::RESPONSE_FORMAT_XML );

		ob_start();
		$this->object->responseOK();
		$d = ob_get_contents();
		ob_end_clean();

		$this->assertEquals( [
			[
				'header' => 'HTTP/1.1 200 OK',
				'replace' => true,
				'http_response_code' => 200,
			],
			[
				'header' => 'Content-type:text/xml;charset=UTF-8',
				'replace' => true,
				'http_response_code' => 0,
			],
		], $GLOBALS['_test_Http_Headers_sent_headers'] );

		$this->assertEquals('<?xml version="1.0" encoding="UTF-8" ?>'.JET_EOL.'<result>OK</result>', $d );
	}


	/**
	 * @covers Jet\Mvc_Controller_REST::responseData
	 * @todo   Implement testResponseData().
	 */
	public function testResponseData() {
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}

	/**
	 * @covers Jet\Mvc_Controller_REST::responseDataModelsList
	 * @todo   Implement testResponseDataModelsList().
	 */
	public function testResponseDataModelsList() {
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}

	/**
	 * @covers Jet\Mvc_Controller_REST::responseAclAccessDenied
	 * @todo   Implement testResponseAclAccessDenied().
	 */
	public function testResponseAclAccessDenied() {
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}

	/**
	 * @covers Jet\Mvc_Controller_REST::responseFormErrors
	 * @todo   Implement testResponseFormErrors().
	 */
	public function testResponseFormErrors() {
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}

	/**
	 * @covers Jet\Mvc_Controller_REST::responseUnknownItem
	 * @covers Jet\Mvc_Controller_REST::responseError
	 */
	public function testResponseUnknownItemJSON() {

		$this->object->setResponseFormat( Mvc_Controller_REST::RESPONSE_FORMAT_JSON );

		ob_start();
		$this->object->responseUnknownItem('Item_ID');
		$d = ob_get_contents();
		ob_end_clean();

		//var_export($GLOBALS['_test_Http_Headers_sent_headers']);
		//var_export( $d );

		$this->assertEquals( [
			[
				'header' => 'HTTP/1.1 404 Unknown item',
				'replace' => true,
				'http_response_code' => 404,
			],
			[
				'header' => 'Content-type:application/json;charset=UTF-8',
				'replace' => true,
				'http_response_code' => 0,
			],
		], $GLOBALS['_test_Http_Headers_sent_headers'] );

		$this->assertEquals('{"error_code":"Jet\\\\Mvc_Controller_REST_Test:UnknownItem","error_msg":"Unknown item","error_data":{"ID":"Item_ID"}}', $d );
	}

	/**
	 * @covers Jet\Mvc_Controller_REST::responseUnknownItem
	 * @covers Jet\Mvc_Controller_REST::responseError
	 */
	public function testResponseUnknownItemXML() {
		$this->object->setResponseFormat( Mvc_Controller_REST::RESPONSE_FORMAT_XML );

		ob_start();
		$this->object->responseUnknownItem('Item_ID');
		$d = ob_get_contents();
		ob_end_clean();

		$this->assertEquals( [
			[
				'header' => 'HTTP/1.1 404 Unknown item',
				'replace' => true,
				'http_response_code' => 404,
			],
			[
				'header' => 'Content-type:text/xml;charset=UTF-8',
				'replace' => true,
				'http_response_code' => 0,
			],
		], $GLOBALS['_test_Http_Headers_sent_headers'] );

		$this->assertEquals('<?xml version="1.0" encoding="UTF-8" ?>'.JET_EOL
					.'<error>'.JET_EOL
					.JET_TAB.'<error_code>Jet\\Mvc_Controller_REST_Test:UnknownItem</error_code>'.JET_EOL
					.JET_TAB.'<error_msg>Unknown item</error_msg>'.JET_EOL
					.JET_TAB.'<error_data>'.JET_EOL
					.JET_TAB.JET_TAB.'<ID>Item_ID</ID>'.JET_EOL
					.JET_TAB.'</error_data>'.JET_EOL
					.'</error>'.JET_EOL , $d );
	}
}
