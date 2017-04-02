<?php
/**
 *
 *
 *
 * @copyright Copyright (c) 2011-2016 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Installer
 */
namespace JetExampleApp;

class CompatibilityTester_TestResult {
	/**
	 * @var bool
	 */
	protected $required = true;
	/**
	 * @var string
	 */
	protected $title = '';
	/**
	 * @var string
	 */
	protected $description = '';

	/**
	 * @var bool
	 */
	protected $passed = false;
	/**
	 * @var string
	 */
	protected $result_message = '';

	public function __construct( $required, $title, $description ) {
		$this->required = (bool)$required;
		$this->title = (string)$title;
		$this->description = (string)$description;
	}

	/**
	 * @param string $description
	 */
	public function setDescription($description) {
		$this->description = (string)$description;
	}

	/**
	 * @return string
	 */
	public function getDescription() {
		return $this->description;
	}

	/**
	 * @param bool $passed
	 */
	public function setPassed($passed) {
		$this->passed = (bool)$passed;
	}

	/**
	 * @return bool
	 */
	public function getPassed() {
		return $this->passed;
	}

	/**
	 * @param bool $required
	 */
	public function setRequired($required) {
		$this->required = (bool)$required;
	}

	/**
	 * @return bool
	 */
	public function getRequired() {
		return $this->required;
	}

	/**
	 * @param string $result_message
	 */
	public function setResultMessage($result_message) {
		$this->result_message = (string)$result_message;
	}

	/**
	 * @return string
	 */
	public function getResultMessage() {
		return $this->result_message;
	}

	/**
	 * @param string $title
	 */
	public function setTitle($title) {
		$this->title = (string)$title;
	}

	/**
	 * @return string
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * @return bool
	 */
	public function getIsError() {
		return ($this->required && !$this->passed);
	}

	/**
	 * @return bool
	 */
	public function getIsWarning() {
		return (!$this->required && !$this->passed);
	}

	/**
	 *
	 */
	public function showResultRow() {
		$css_class = 'isok';
		/** @noinspection SpellCheckingInspection */
		$icon = 'data:image/gif;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAADwklEQVRIidWVa0ybVRzGn/O29vLSdqX0ArQMGNahg0yBRaezuCxuLH5wyeaVlGVLFvWLS5YlJibbkqmJxkjiRFC8ZDMwpwNhBdoBdahsMjpQxm7C6EvtjdIL6woIwtsev7hlbhXnLh/2JOfTOef3/P/nnzyHUEpxN8XcVfo9ZSCrISVZu7U/Zr2r0t9xA/ZzUpwXzu9t2HLElMsYrdLXSObVTUrpbS3pZygpeMsY7zpvpzsPbaetg0eo1MycEW1CJqX09jpIqSVFuf68kx9uqmYsZ5swEOzD8wc24u2KdwrE8xKbeD1R3rJBSjUpzhlfcuqjl2oEluFGDE7/gp8nTyCRycPmaoGcUQri89fNQHdIolTsFdVqD0qKFoKzn5DixRM5jn3masbiOYxziQE4+B5IcoXQETUudIw6qXBuHW+n0asGbC2R8140H3ule5v6coYlZT9ZngyeWkdKcmK5jqotNUzreBOGE0M4O38aaXollCEVxmwTI5GpMZP/24gP+LsDTaVYrovqmhpfaCnl/5iD3XxcvyiaakmtZwqvhWc0sEVZIWPvx5s/ZdpDbfDwLozwv0GbroHULYenLcwRQWL1jJX6r9xhpLuJwsgXWt5/tnINjQOuORe6x7rg2DawWBFTWfXfyQoAIKcltUgXyD5VZa5hvp+wwU894OhFaNI1wLAIIxYfx8gSplkb9V5bFEP/hFpG5IXN3gb8EO2EUCzANDOFjnErujf3GTAkan+ic/magvAKx77yKqbrcicCxAc3GYUmXQN+GLhwlONE+QnTbCP1Xf+kzOx7lIshUh4dmA4f9bXAeqkZCUEcccE8rJFmnNjelykbVdt3PbdH0D11DEGBHz6BBxqtGjMXeQy2DjnZ1dQ09cGNcAAgV9I0e6d2vUTE1inXSlQGbRYela+CXKhAnOGxUvEk7DEbgjQAV9wJVipF0BlFzxd9nLLsvlL3hpg3GfwfBgAge4OUycSKuoc3LktjVVI8IHoIGSI9EiSOEALw0N+RImXhdQfRU9vH5WzQmPpXOZNWntQAAOSVZF1KTFlfan48TZIuhIqqIRPKESQBsGIWHOdBz5f93CPlRlP7g70LwoEkYTe5g7Ynlsy8fPxrR0g4KQEjI5gWT8EgNmDMFUb/gTPOp19feVPwpAYAEKyY7WALmQp73U8RySU5lpFC+N1h/PrVeW7rrhefqte23RQcwMJpuqJ7adnSyrzIq6e30vy99zu/ofv1/zdt//PA2nOPPWN4M+PkHroj+1bi/IYh32ndO3/yv+kvrBgu89iR6ZIAAAAASUVORK5CYII=';

		if(!$this->passed) {

			if($this->required) {
				/** @noinspection SpellCheckingInspection */
				$icon = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAGFElEQVRIiYWWXWwU1xmGn3POzOzueNde23EIkBgWiAGLFloKBOKmNEhtpVhWICCCoopWTUkglggXhFwQKReIQkRR1WTTQFGk1o2ASGliFypBSuuayhFEVVOgkKiqVFopCsbrtb2zP/N3Ti+8dtKGqK80Ohdz9D4z+r7zvUcYY7iTDhw8mFyo9a601o3KtrSSCiEABNoY4jgmDkPpKTX5D8i/sH+/fycfcSfA3uefT3X5/puPrH6g2woBYYFUIAQIA8RgIhCaCPjt8PDZIdfdcuTw4er/BTzd2+t+vVY7vamzszslLIgkqGQdIkBoIAIRTq3Kolar8eurV8/8MZXaeuzVVytfCHhi+/ZUV6325ncXL+5OA4QhWA5IG6T16R8IDWgwBrQGx6Hs+/zq2rXfDKZSW0/29VU/B+jZuNFdWy6ffmrJ0u6WKIQ4ntohJSSTU+ZSQr0OCAFBMAVQCmybsTDk51eunBlKp7ee7e+vzADWb9jgrhofP71zaWf3LKNRcYz2fYaiCKkU6xMJnHS6/klToLBS4Q+VCtoYHnJdRDJJ5DgUqlV+9sEHZ4az2a0XBwcrYvXatZncyMjJvfff/8g9lkKGEdRq/Nlx+Orx4wghuLZzJ11ak0qnQQiqlQoXhWBZPo8Qgr888wwrjAHXRTsOtyYn+en162dutLU9oRa0tPT+cH6ut6lUolouE1erfNjayldefpk5CxaQyWZxu7p4/+xZWgsFap7HcCLBl/N55syfT6apicy6dfx1cJBUoYBXrSKlpL21tePSrVsjaq5tf2eZH3zDD0OCKKJSKrHoySdp37BhpviZbBb3wQe5/M47/FsIVpw4wexcbuZ9OpvFqdW4feECNWMoV6t4o6MMF4vDlgjDeDTwmRCCNOAC3uHD2Nks927aNGMyO5dj9RtvgDG0zZv3X6090t/Pvw4dwgeCiQkCY/CDABFF2tJhyMdRhG8MzUAj0BBFTPT28pDWtG/eTBiGGGNoaW8HIAgChBDYts0nb73F1d27CYSgYlmUjKEMGGOIowhLa81IGOJpjQYMIIRAxTF/2rWLVaUSdz26ESEFlMszrWuMYWJggOv79lEDAsuiHARMAGOADXUAMDk+zi3fJ5QSTwg8KWkWgslKhYmjR/nWmjU0tLai62dDKkW5WOTC0aPo0VGM61KLIiaNYVxrPtGaTCKBUQpLK0WD73NXGNIASMAHioDd2cma115DpjN4k5NMH0ohBMp1WZnP8/sdO6hdv04MTAIeEAPatjGOg4wtixYhyAHzgXnALODujg6+mc+TarubUnEMz/MwjgOOg+d5lIpF3LY2uvJ5rI4OFNAsBLOAdqAZiG0bGViWymrNXKBNKbJC0Lh8OauOHMFuyjJRGKVUKqEyjcTnzxOdO4fMZCh5HhOFAnZTE1878mPSK1bQJAStts09QpAxhlApKe3m5nFn7r00Ow4NjkNKStZu2ULrkqUUb49MmTc14V4c4p+7d3Pz2WdJDw2hGpsolUoUb9+mZcli1j32GK6UNCQSZBMJmD0bu6VlUs1fsOBvH06ML1vkOIvnSEkykSC+fJk5uRylXA6ZTJK7coXCnj24lkWDUgTnzzPnS8sotLcjk0lmv/ceIwcP4tg2rm3zcTrNgBADprn5helhl1I3b556PAh6OqMI7fu4UjLr0CGUUnjPPQfGoGwbAegwRAhBw0svEccxY/v2UauP7Su2zSnH6a/ed9+2i4OD1Zlx/ejmzS4ffXRyu+f1LDeGMAxJGIMtBFIILMtCfiY7dByjtSYGAsCybd4Xgr6GhgF/4cJtZwcGPh3X03q6tzdZuHTp9PfGxnrWK4WvNTZgCYGqt6eo79eANobIGBwp+Z3W/DKb7XdXrnz89ePHa58LnGntf/FF98a7757cMTLS823H+UzQ1ENmWtNppjXngoATbW0Dix5+eNuPDhz44sic1k9eeSU19Pbbp34wPt4zr1wmJSVJIbDrgNgY/PpzM53mWGPjwAPd3Vv37tlT+1+vOwIAftHXl/j7jRtPmWIx06yUyQhBsl6PSGuqWlPSWpiWltKsjo5j39++/Y7Xlv8A1u7EN1gnUQ0AAAAASUVORK5CYII=';
				$css_class = 'fail';
			} else {
				/** @noinspection SpellCheckingInspection */
				$icon = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAEi0lEQVRIiZWUS2xVVRRA1zn33M+7931LaQVKoX+gQAXKN1ApIEQxMX4iJkYTNUajhBiNn4nBDwwc6VQdEIdGB0aHOFBJjANjEKOGRCQKRdsCfY/Xd9997957joMGtSnQupM1OMne2Vl7n3PERx9qFhrt6RtUQ0nY8drNk8zsowL47m25oAZDe4b6ypNX6me+PXpxIfl739UzDRYYXtui7FsqrpWBZxZapEwW6vX5E7s6naOjIz2HkrCNL7/+7fuf/9Dvz1fz0AAsbDbQvmPv4POWSnDzHvfe2XkMcBdSKNEJCG5JV2/mzf51A56eriKiCrtGBhZv6pYv2QJuRZIuzKBneOf6x1xliOsNdFQjU5DcvbfzCFCc38BT6CY3RABd/bmjK3pXeI3aFFFlkubUFDIqs2XL8sUDy+Ur5QRuRj6Y32DDxq1rHvRUQhQ20K39xKU+0lpIPq+5Z0/H48DSWxvcajarii/29C7LUB2HTJ7CgffwD3xAKnyojrNtuL19/6B6wbMhMnPJSJBCQmrmYrnsun3bwKFCFprVMsoYpHSxpEdqBGnlGsXcNCObSk8A/f/XwOlfXXp29cBSlYZXMEkTPX0FrRto06QZhsSTFcSlc2zrT0sbV6jnbt5AgxGz8QKxY+OWnofzbkJ07Rp2EiLjMgiDMZr4z4vE4w30ZJNiLuLAcOZpz2G9p+C/1Bs3Nsj09hUOr13bSfPyBRxh8LIWvm2QgNAJqlpGGDASCCN2DVruzpXWkzD365EmAsf5lyAQm4Y3r3wgsMroOMFvKeE3q3i1vxB2Aem1EhQcbBeEAEJNvpiyb8g9clvAmqwD1wnLcw3c7h7/1XWDrST1EC9QOOM/4lydQk2VMR9vx3w2gm2aOFkQEkBAo8nuIY++NnkYcGYZiFqdwGeGDMNbN3cc9J0Epi7hjP+CdfUyIm+ReBZRZgmRlSM1BlF0ELYFSkJkyLZoRtc5T+UdVvkKfAVRfbaB7OrOHhvsLRGf/wE1fgEZNaAQgOsgBkew953A3nMCObAZlANBAJY9cxmjmNENDr2LxcvXdyEESHQGV4DnijuG+73dauwMcnIKSwG5HARFyLViEaNsB6V8hIghaIWgNLM4IaChyRUMo4PqEU8xGDsKOf3NPwZWR86802VfwZRDLAfwPfAyYPuQXwKNBpy8H744BNKG0hLwAvByYEnQKUQxe9ZadOY5PrMcUHbtK4ot9uiONWYoIMHxQPgSsh4U8pBtg/xSyLbPNJM2EEN1AioXwRVgNWG6AklMkDU8elfx4OufhkNJVDmtXHOWoOR3WC0uY5MT+FmBo22ksbASjYpCRHQZKikyk8VYFqYxDWGZtDZNfC0mrVlQtzF1g0kSlq1YRHub1Vef+Om0OHlqjOP3LSu2FdX+OExsBEaCMYCZebgaIUgMQkq00SQalDZoKYyKU0yLh99dIGdZSAxy2sjG5+f0J5+fLk+Ik6fGAKhe/BVR2o5uXKUR/k5j6jxR5QJxXCap1xA0IRVoEZHGBm0MQrh4+RKZYCUZbw2O6gV3EXCW9TuXA/A3HrzUaJE73FIAAAAASUVORK5CYII=';
				$css_class = 'warning';
			}
		}
		?>
		<tr class="<?php echo $css_class; ?>">
			<td class="resicon"><img src="<?php echo $icon; ?>"/></td>
			<td class="restitle"><b><?php echo $this->title;?></b><br/><?php echo $this->description; ?></td>
			<td class="resresult"><?php echo $this->result_message;?></td>
		</tr>
		<?php
	}

}
