<?php

/**
 * Internacionalization
 *
 * Translate site to different languages
 *
 * @author		Dmitriy Belyaev <admin@cogear.ru>
 * @copyright		Copyright (c) 2010, Dmitriy Belyaev
 * @license		http://cogear.ru/license.html
 * @link		http://cogear.ru
 * @package		Core
 * @subpackage  	Admin
 * @version		$Id$
 */
class I18n_Gear extends Gear {
    protected $name = 'Internacionalization';
    protected $description = 'Translate site interface to different languages.';
    protected $type = Gear::CORE;
    protected $author = 'Dmitriy Belyaev';
    protected $order = -1000;
    protected $domains = array();
    protected $date_format;
    /**
     * Locale
     * 
     * @var string 
     */
    protected $locale;

    /**
     * Constructor
     */
    public function __construct(){
        $cogear = getInstance();
        $this->locale = $cogear->get('site.locale','en');
        $this->date_format = $cogear->get('site.date_format','Y-m-d H:i');
        parent::__construct();
    }
    /**
     * Init gear
     */
    public function init() {

    }

    /**
     * Transliteration
     *
     * @param   string  $text
     * @return   string
     */
    public function transliterate($text){
        if(function_exists('transliterate_'.$this->locale)){
            $text = call_user_func_array('transliterate_'.$this->locale, array($text));
        }
        return $text;
    }

    /**
     * Translate
     * 
     * @param string $text
     * @param string $domain
     * @return string
     */
    public function translate($text,$domain = ''){
        $domain OR $this->domains && $domain = reset($this->domains);
        return $text;
    }
    /**
     * Format date
     * 
     * @param int $time
     * @param string $format
     * @return string 
     */
    public function date($time,$format = NULL){
        $format OR $format = $this->date_format;
        return date($format,$time);
    }
    /**
     * Set domain
     * 
     * @param   string  $domain If empty — return to previous domain
     */
    public function setDomain($domain = ''){
        if($domain){
            array_push($this->domains,$domain);
        }
        else {
            array_pop($this->domains);
        }
    }

}

/**
 * Simple translation
 *
 * @param   string  $text
 * @param   string  $domain
 * Optional params to parse via sprintf
 * @param   mixed   $param_1
 * …
 * @param   mixed   $param_N
 * @return  string
 */
function t($text, $domain = '') {
    $cogear = getInstance();
    $result = $cogear->I18n->translate($text,$domain);
    if(func_num_args () > 2){
        $args = func_get_args();
        $args = array_slice($args, 2);
        // Find all (one|some|many)  for creating correct plural forms
        preg_match_all('#\((.+)\)#imU', $result, $matches);
        if (sizeof($matches[0]) > 0) {
            foreach ($matches[0] as $key => $val) {
                if (count(explode('|', $matches[1][$key])) > 1)
                    $result = str_replace($val, declOfNum($args[$key], $matches[1][$key]), $result);
            }
        }
        array_unshift($args,$result);
        $result = call_user_func_array('sprintf',$args);
    }
    return $result;
}
/**
 * Set domain
 * 
 * @param string $domain 
 */
function d($domain = ''){
    $cogear = getInstance();
    $cogear->I18n->setDomain($domain);
}
/**
 * Plural forms for words
 *
 * @param       int $number number
 * @param       string $titles Array of words to make plural forms joined with |
 * @return      string
 **/
function declOfNum($number, $titles)
{
        if($number < 0) $number = -$number;

        $cases = array (2, 0, 1, 1, 1, 2);


        if(is_string($titles)) $titles = explode('|',$titles);
        if(count($titles) < 3){
                $titles = array_pad($titles,3,end($titles));
        }
        $offset = ($number%100>4 && $number%100<20)? 2 : $cases[min($number%10, 5)];
        return isset($titles[$offset]) ? $titles[$offset] : array_shift($offset);
}

/**
 * Transliteration from Russian language
 *
 * @param   string  $text
 * @return   string
 */
function transliterate_ru($text){
		$LettersFrom = explode(",","а,б,в,г,д,е,з,и,к,л,м,н,о,п,р,с,т,у,ф,ц,ы");

		$LettersTo   = explode(",","a,b,v,g,d,e,z,i,k,l,m,n,o,p,r,s,t,u,f,c,y");
		$BiLetters = array(
		"й" => "jj", "ё" => "jo", "ж" => "zh", "х" => "kh", "ч" => "ch",
		"ш" => "sh", "щ" => "shh", "э" => "je", "ю" => "ju", "я" => "ja",
		"ъ" => "", "ь" => "",
		);
		$Caps  = explode(",","А,Б,В,Г,Д,Е,Ё,Ж,З,И,Й,К,Л,М,Н,О,П,Р,С,Т,У,Ф,Х,Ц,Ч,Ш,Щ,Ь,Ъ,Ы,Э,Ю,Я");
		$Small = explode(",","а,б,в,г,д,е,ё,ж,з,и,й,к,л,м,н,о,п,р,с,т,у,ф,х,ц,ч,ш,щ,ь,ъ,ы,э,ю,я");
		$text = preg_replace( "/\s+/ms", $separator, $text );
		$text = str_replace($Caps,$Small,$text);
		$text = str_replace($LettersFrom,$LettersTo,$text);
		$text = strtr( $text, $BiLetters );
}
/**
 * Format date
 * 
 * @param   int $time
 * @param   string  $format
 * @return  string
 */
function df($time,$format = NULL){
    $cogear = getInstance();
    return $cogear->i18n->date($time,$format);
}