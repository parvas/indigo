<?php if (!defined('SYSTEM')) exit('No direct script access allowed');

class Categories extends Controller {
    
    public function index()
    {
        $this->_data['categories'] = $this->_model->get();

        $this->_template
             ->title(_SHOW_ALL_)
             ->render('categories_all', $this->_data);
    }
    
    public function add()
    {
        $this->_data['categories'] = $this->_model->get_categories_select();
        
        if ($this->_validate())
        {
            $this->_model->add($this->_module->post());
            URL::redirect('/categories');
        }

        $this->_template
             ->title('Προσθήκη Κατηγορίας')
             ->render('category_add', $this->_data);
    }
    
    public function edit($id)
    {
        $this->_data = $this->_model->get($id);
        $this->_check_if_null($this->_data);

        $this->_data['categories'] = $this->_model->get_categories_select();
        
        if ($this->_validate())
        {
            $this->_model->update($id, $this->_module->post());
            URL::redirect('/categories');
        }

        $this->_template
             ->title(_EDIT_PAGE_)
             ->render('category_edit', $this->_data);
    }
    
    public function delete($id)
    {
        $this->_model->delete($id);
        URL::redirect('/categories');
    }
    
    public function show($id)
    {
        $this->_data = $this->_model->get($id);
        $this->_check_if_null($this->_data);
        
        $this->_template
             ->title($this->_data['name'])
             ->render('category_show', $this->_data);
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