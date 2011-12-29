<?php

/**
 * Config
 *
 *
 * @author		Dmitriy Belyaev <admin@cogear.ru>
 * @copyright		Copyright (c) 2010, Dmitriy Belyaev
 * @license		http://cogear.ru/license.html
 * @link		http://cogear.ru
 * @package		Core
 * @subpackage
 * @version		$Id$
 */
class Config extends Core_ArrayObject {

    protected $file;
    const AS_ARRAY = 1;
    const AS_OBJECT = 21;

    /**
     * Constructor
     * 
     * @param string $path
     * @param string $section 
     */
    public function __construct($path = '', $section = '') {
        if ($path) {
            $this->file = $path;
            $this->load($path, $section);
        }
    }

    /**
     * Load file into internal config
     * 
     * @param   string  $path
     * @param   string  $section
     */
    public function load($path, $section = '') {
        file_exists($path) && ($section && $this->$section->mix(self::read($path))) OR $this->mix(self::read($path));
    }

    /**
     * Read config from file
     *
     * @param string $file
     */
    public static function read($file, $mode=NULL) {
        $mode OR $mode = self::AS_OBJECT;
        if (!file_exists($file)) {
            return NULL;
        } elseif ($mode === self::AS_OBJECT) {
            return Core_ArrayObject::transform(include $file);
        } elseif ($mode === self::AS_ARRAY) {
            return include $file;
        }
    }

    /**
     * Save config to file
     * 
     * @param string $file
     * @param array $data
     */
    public function store($file = NULL, $data = NULL) {
        $file OR $file = $this->file;
        $data OR $data = $this->toArray();
//        debug($data);
//        die();
        if(self::write($file,$data)){
            return TRUE;
        }
        else {
            error(t('Cannot write file:<br/>
                <b>%s</b><br/>
                Please, check the permissions (must be 0755 at least.',NULL,$file));
            return FALSE;
        }
    }
    /**
     * Write data
     * 
     * @param string $file
     * @param mixed $data
     * @return  mixed 
     */
    public static function write($file, $data) {
        Filesystem::makeDir(dirname($file));
        $data = var_export($data,TRUE);
        // Now we need to replace paths with constants
        $constants = get_defined_constants(true);
        $paths = array();
        foreach($constants['user'] as $key=>$value){
            if(is_string($value) && is_dir($value) && strlen($value) > 5){
                $paths["'".$value] = $key.'.\'';
            }
        }
        $paths = array_reverse($paths);
        $data = str_replace(DS.DS,DS,$data);
        $data = str_replace(array_keys($paths), array_values($paths), $data);
        // Done
        return file_put_contents($file, PHP_FILE_PREFIX . "return " . $data . ';');
    }

}