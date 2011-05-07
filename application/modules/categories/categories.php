<?php if (!defined('SYSTEM')) exit('No direct script access allowed');

class Categories extends Controller {
    
    public function index()
    {
        $this->data['categories'] = $this->model->get();

        $this->template
             ->title(_SHOW_ALL_)
             ->render('categories_all', $this->data);
    }
    
    public function add()
    {
        $this->data['categories'] = $this->model->get_categories_select();
        
        if ($this->_validate())
        {
            $this->model->add($this->module->post());
            URL::redirect('/categories');
        }

        $this->template
             ->title('Προσθήκη Κατηγορίας')
             ->render('category_add', $this->data);
    }
    
    public function edit($id)
    {
        $this->data = $this->model->get($id);
        $this->_check_if_null($this->data);

        $this->data['categories'] = $this->model->get_categories_select();
        
        if ($this->_validate())
        {
            $this->model->update($id, $this->module->post());
            URL::redirect('/categories');
        }

        $this->template
             ->title(_EDIT_PAGE_)
             ->render('category_edit', $this->data);
    }
    
    public function delete($id)
    {
        $this->model->delete($id);
        URL::redirect('/categories');
    }
    
    public function show($id)
    {
        $this->data = $this->model->get($id);
        $this->_check_if_null($this->data);
        
        $this->template
             ->title($this->data['name'])
             ->render('category_show', $this->data);
    }
    
    private function _validate()
    {
        return Validation::factory()
                        ->label('name', _NAME_)
                        ->label('description', _DESCRIPTION_)
                        ->rule('name', 'required')
                        ->rule('name', 'max_length', 100)
                        ->rule('description', 'max_length', 300)
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