<?php
/**
 * Word validator
 *
 * @author		Dmitriy Belyaev <admin@cogear.ru>
 * @copyright		Copyright (c) 2011, Dmitriy Belyaev
 * @license		http://cogear.ru/license.html
 * @link		http://cogear.ru
 * @package		Core
 * @subpackage	Form
 * @version		$Id$
 */
class Form_Validate_Alpha extends Form_Validate_Regexp{
	/**
	 * Constructor
	 */
	public function __construct(){
		parent::__construct('([\w_-]+)',t('Value must contain only alphabetic characters, digits or "_-" symbols.'));
	} 
} 