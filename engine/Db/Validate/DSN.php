<?php

/**
 * Validate user email
 *
 * @author		Dmitriy Belyaev <admin@cogear.ru>
 * @copyright		Copyright (c) 2011, Dmitriy Belyaev
 * @license		http://cogear.ru/license.html
 * @link		http://cogear.ru
 * @package		Core
 * @subpackage          User
 * @version		$Id$
 */
class Db_Validate_DSN extends Form_Validate_Abstract {
    const OPTIONAL = 0;
    const REQUIRED = 1;

    /**
     * Validate user email.
     * 
     * @param string $value 
     */
    public function validate($value = NULL, $type = 0) {
        if ($value OR $type) {
            $db_is_active = !is_null(cogear()->db);
            $db_is_active OR cogear()->activate('db');
            if (!cogear()->db->checkDSN($value)){
                $db_is_active OR cogear()->deactivate('db');
                $this->element->addError(t('Look at the database errors and fix them.', 'Db.errors'));
                return FALSE;
            }
            return TRUE;
        }
        return TRUE;
    }

}