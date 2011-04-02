<?php defined('SYSTEM') or exit('No direct script access allowed');

class Pages extends Controller {
    
    private $_validation;
    
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
        Module::factory('contact/add');
        
        if ($this->_validate())
        {
            Model::factory('pages')->insert(Input::post());
            header('Location: ' . WEB . 'pages/add');
        }

        Template::instance()
                ->head('')
                ->title('Προσθήκη Σελίδας')
                ->render('pages/page_add', $this->_data);
    }
    
    public function show($title)
    {
        $this->_data = Model::factory('pages')->get($title);
        
        Template::instance()
                ->title($title)
                ->render('pages/page_show', $this->_data);
    }
    
    public function edit($title)
    {
        $this->_data = Model::factory('pages')->get($title);
        
        if ($this->_validate())
        {
            $new = Model::factory('pages')->update($title, Input::post());
            header('Location: ' . WEB . 'pages/show/' . $new);
        }

        Template::instance()
                ->render('pages/page_edit', $this->_data);
    }
    
    private function _validate()
    {
        $validation = Validation::factory()
                                ->label('title', 'Τίτλος')
                                ->label('content', 'Περιεχόμενο')
                                ->rule('title', 'required')
                                ->rule('content', 'required');
        
        return $validation->validate();
    }
}