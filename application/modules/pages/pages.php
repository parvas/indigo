<?php defined('SYSTEM') or exit('No direct script access allowed');

class Pages extends Controller {
    
    public function index()
    {
        $this->_data['pages'] = $this->_model->get();

        $this->_template
             ->keywords('Προβολή σελίδων, indigo')
             ->description('Λίστα με όλες τις σελίδες του website')
             ->title(_SHOW_ALL_)
             ->render('pages_all', $this->_data);
    }
    
    public function add()
    {
        Module::factory('categories/add');
        
        if ($this->_validate())
        {
            $this->_model->insert(Module::instance()->post);
            URL::redirect('/pages');
        }

        $this->_template
             ->title(_ADD_PAGE_)
             ->render('page_add', $this->_data);
    }
    
    public function show($id)
    {      
        $this->_data = $this->_model->get($id);
        $this->_check_if_null($this->_data);
        
        $this->_template
             ->keywords($this->_data['keywords'])
             ->description($this->_data['description'])
             ->title($this->_data['title'])
             ->render('page_show', $this->_data);
    }
    
    public function edit($id)
    {
        $this->_data = $this->_model->get($id);
        $this->_check_if_null($this->_data);
        
        if ($this->_validate())
        {
            $this->_model->update($id, Input::post());
            URL::redirect('/pages');
        }
		
        $this->_template
             ->title(_EDIT_PAGE_)
             ->render('pages/page_edit', $this->_data);
    }
    
    public function delete($id)
    {
        $this->_model->delete($id);
        URL::redirect('/pages');
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
    
    private function _check_if_null($resource)
    {
        if ($resource === null)
        {
            Exceptions::error_404(URL::fetch_full());
        }
    }
}