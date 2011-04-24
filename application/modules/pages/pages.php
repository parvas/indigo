<?php defined('SYSTEM') or exit('No direct script access allowed');

class Pages extends Controller {
    
    public function __construct()
    {
        parent::__construct();
    }
    
    public function index()
    {
        echo 'Pages Initialized!';
    }
    
    public function add()
    {
        if ($this->_validate())
        {
            $new = Model::factory('pages')->insert(Input::post());
            header('Location: ' . WEB . 'pages/show/' . $new);
        }
        
        Template::instance()
                ->title(_ADD_PAGE_)
                ->render('pages/page_add', $this->_data);
        
        /*Email::instance()
                ->to('parvas.webdev@gmail.com')
                ->subject('test')
                ->message('test')
                ->send();*/
    }
    
    public function show($id)
    {
        $this->_data = Model::factory('pages')->get($id);
        
        Template::instance()
                ->title($this->_data['title'])
                ->render('pages/page_show', $this->_data);
    }
    
    public function edit($id)
    {
        $this->_data = Model::factory('pages')->get($id);
        
        if ($this->_validate())
        {
            Model::factory('pages')->update($id, Input::post());
            header('Location: ' . WEB . 'pages/show/' . $id);
        }

        Template::instance()
                ->render('pages/page_edit', $this->_data);
    }
    
    private function _validate()
    {
        return Validation::factory()
                    ->label('title', 'Τίτλος')
                    ->label('content', 'Περιεχόμενο')
                    ->rule('title', 'required')
                    ->rule('content', 'required')
                    ->validate();
    }
}