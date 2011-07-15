<?php
/**
 * Config condition
 *
 * @author		Dmitriy Belyaev <admin@cogear.ru>
 * @copyright		Copyright (c) 2011, Dmitriy Belyaev
 * @license		http://cogear.ru/license.html
 * @link		http://cogear.ru
 * @package		Core
 * @subpackage	Form
 * @version		$Id$
 */
class Form_Condition_Config extends Form_Condition_Abstract {
	/**
	 * Check
	 *
	 * @param	array	$options
	 * @return	boolean
	 */
	public function check(){
            $cogear = getInstance();
            return $cogear->get($this->options[0]);
	} 
} 