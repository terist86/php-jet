<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetStudio;

/**
 *
 */
class ClassParser_Namespace extends ClassParser_Element{
	/**
	 * @var string
	 */
	public $namespace = '';

	/**
	 * @param ClassParser $parser
	 */
	public static function parse( ClassParser $parser )
	{
		$namespace = new static( $parser );

		$token = $parser->tokens[$parser->index];
		$namespace->start_token = $token;

		do {

			if( !($token=$namespace->nextToken()) ) {
				break;
			}

			if($token->ignore()) {
				continue;
			}

			switch( $token->id ) {
				case T_STRING:
				case T_NS_SEPARATOR:
					$namespace->namespace .= $token->text;
					break;
				case ';':
					$namespace->end_token = $parser->tokens[$parser->index];

					$parser->namespace = $namespace;
					return;
				default:
					static::parse( $parser );
					return;

			}

		} while( true );

	}

	/**
	 *
	 */
	public function debug_showResult()
	{
		$parser = $this->parser;

		echo 'Namespace: '.$this->namespace;

		echo PHP_EOL.' Code: '.$parser->getTokenText( $this->start_token, $this->end_token );
		echo PHP_EOL.' Tokens: '.$this->start_token->index.' - '.$this->end_token->index;
		echo PHP_EOL.PHP_EOL;
	}

}