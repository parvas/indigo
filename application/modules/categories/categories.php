<?php if (!defined('SYSTEM')) exit('No direct script access allowed');

class Categories extends Controller {
    
    public function __construct() 
    {
        parent::__construct();
    }
    
    public function add()
    {
        $cmodel = Model::factory('categories');
        
        $this->_data['categories'] = $cmodel->get_all();
        
        if ($this->_validate())
        {
            $last = $cmodel->add(Input::post());
            header('Location: /categories/show/' . $last);
        }
        
        Template::instance()
                ->title('Προσθήκη Κατηγορίας')
                ->render('categories/category_add', $this->_data);
    }
    
    public function edit()
    {
        
    }
    
    public function delete()
    {
        
    }
    
    public function show($id)
    {
        $this->_data = Model::factory('categories')->show($id);
        
        Template::instance()
                ->title($this->_data['title'])
                ->render('categories/category_add', $this->_data);
    }
    
    private function _validate()
    {
        return Validation::factory()
                        ->label('category_name', _NAME_)
                        ->label('category_description', _DESCRIPTION_)
                        ->rule('category_name', 'required')
                        ->rule('category_name', 'max_length', 100)
                        ->validate();
    }
}