<?php defined('SYSTEM') or exit('No direct script access allowed');

class Pages extends Controller {
    
    private $_model;
    private $_template;
    
    public function __construct()
    {
        $this->_template = Template::instance();
        $this->_model = Model::factory('pages');
        
        parent::__construct();
    }
    
    public function index()
    {
        $this->_data['pages'] = $this->_model->get();

        $this->_template
             ->title('Προβολή Όλων')
             ->render('pages/pages_all', $this->_data);
    }
    
    public function add()
    {
        if ($this->_validate())
        {
            $this->_model->insert(Input::post());
            header('Location: ' . WEB . 'pages');
        }
        
        $this->_template
             ->title(_ADD_PAGE_)
             ->render('pages/page_add', $this->_data);
    }
    
    public function show($id)
    {
        $this->_data = $this->_model->get($id);
        
        $this->_template
             ->title($this->_data['title'])
             ->render('pages/page_show', $this->_data);
    }
    
    public function edit($id)
    {
        $this->_data = $this->_model->get($id);
        
        if ($this->_validate())
        {
            $this->_model->update($id, Input::post());
            header('Location: ' . WEB . 'pages');
        }

        $this->_template
             ->title(_EDIT_PAGE_)
             ->render('pages/page_edit', $this->_data);
    }
    
    public function delete($id)
    {
        $this->_model->delete($id);
        header('Location: ' . WEB . 'pages');
    }
    
    private function _validate()
    {
        return Validation::factory()
                    ->label('title', _TITLE_)
                    ->label('content', _CONTENT_)
                    ->rule('title', 'required')
                    ->rule('content', 'required')
                    ->validate();
    }
}