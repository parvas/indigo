<?php defined('SYSTEM') or exit('No direct script access allowed');

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of articles
 *
 * @author parvas
 */
class Articles extends Pages {
    
    public function __construct() 
    {
        parent::__construct();
    }
    
    public function index()
    {
        echo 'articles index!';
    }
    
    public function add($str1, $str2)
    {
        //parent::add($str1, $str2);
        $this->_data['errors'] = 'aaaaa';
        Template::instance()
                ->title('articles')
                ->render('pages/articles/article_add', $this->_data);
    }
}

?>
