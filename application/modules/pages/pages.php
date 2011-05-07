<?php defined('SYSTEM') or exit('No direct script access allowed');

class Pages extends Controller {
    
    public function index()
    {
        $this->data['pages'] = $this->model->get();

        $this->template
             ->keywords('Προβολή σελίδων, indigo')
             ->description('Λίστα με όλες τις σελίδες του website')
             ->title(_SHOW_ALL_)
             ->render('pages_all', $this->data);
    }
    
    public function add()
    {
        Module::factory('categories/add');
        
        if ($this->_validate())
        {
            $this->model->insert($this->module->post());
            URL::redirect('/pages');
        }

        $this->template
             ->title(_ADD_PAGE_)
             ->render('page_add', $this->data);
    }
    
    public function show($id)
    {      
        $this->data = $this->model->get($id);
        $this->_check_if_null($this->data);
        
        $this->template
             ->keywords($this->data['keywords'])
             ->description($this->data['description'])
             ->title($this->data['title'])
             ->render('page_show', $this->data);
    }
    
    public function edit($id)
    {
        $this->data = $this->model->get($id);
        $this->_check_if_null($this->data);
        
        if ($this->_validate())
        {
            $this->model->update($id, $this->module->post());
            URL::redirect('/pages');
        }
		
        $this->template
             ->title(_EDIT_PAGE_)
             ->render('pages/page_edit', $this->data);
    }
    
    public function delete($id)
    {
        $this->model->delete($id);
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