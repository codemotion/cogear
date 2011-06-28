<?php

/**
 * WYSIWYG gear
 *
 * @author		Dmitriy Belyaev <admin@cogear.ru>
 * @copyright		Copyright (c) 2011, Dmitriy Belyaev
 * @license		http://cogear.ru/license.html
 * @link		http://cogear.ru
 * @package		Core
 * @subpackage
 * @version		$Id$
 */
class Wysiwyg_Gear extends Gear {

    protected $name = 'WYSIWYG';
    protected $description = 'Visual editors manager.';
    public static $editors = array(
        'redactor' => 'Redactor_Editor',
    );
    protected $order = -10;

    /**
     * Init
     */
    public function init() {
        parent::init();
        hook('menu.admin.sidebar', array($this, 'adminMenuLink'));
        Form::$types['textarea'] = self::$editors[config('wysiwyg.editor', 'redactor')];
    }

    /**
     * Hook to add admin menu element
     * 
     * @param type $structure 
     */
    public function adminMenuLink($menu) {
        $root = Url::gear('admin');
        $menu['20'] = new Menu_Item($root . 'wysiwyg', icon('text_padding_bottom') . t('Editor'));
    }

    /**
     * Control Panel
     */
    public function admin() {
        $form = new Form("Wysiwyg.config");
        $options = new Core_ArrayObject;
        $options->editor = config('wysiwyg.editor');
        $form->init();
        $form->elements->type->setValues(self::$editors);
        $form->object($options);
        if($result = $form->result()){
            if(isset(self::$editors[$result['type']])){
                cogear()->set('wysiwyg.editor', $result['type']);
                success('Configuration saved successfully.');
            }
        }
        append('content',$form->render());
    }

}