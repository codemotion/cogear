<?php

/**
 *  Form Element Input
 *
 * @author		Dmitriy Belyaev <admin@cogear.ru>
 * @copyright		Copyright (c) 2011, Dmitriy Belyaev
 * @license		http://cogear.ru/license.html
 * @link		http://cogear.ru
 * @package		Core
 * @subpackage          Form
 * @version		$Id$
 */
class Form_Element_File extends Form_Element_Abstract {

    protected $type = 'file';
    protected $path;
    protected $allowed_types;
    protected $maxsize;
    protected $overwrite = TRUE;
    protected $rename;

    /**
     * Process elements value from request
     *
     * @return
     */
    public function result() {
        $cogear = getInstance();
        $file = new File($this->name, $this->getAttributes(), $this->validators->findByValue('Required'));
        if ($this->value = $file->upload()) {
            $this->is_fetched = TRUE;
        } else {
            $this->errors = $file->errors;
        }
        return $this->value;
    }

    /**
     * Render
     */
    public function render() {
        $this->getAttributes();
        if ($this->value && $this->value = Url::link(Url::toUri(UPLOADS . $this->value, ROOT, FALSE))) {
            $tpl = new Template('Form.file');
            $tpl->assign($this->attributes);
            $tpl->value = $this->value;
            $this->code = $tpl->render();
        }
        return parent::render();
    }

}