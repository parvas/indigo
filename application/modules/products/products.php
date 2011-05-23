<?php if (!defined('SYSTEM')) exit('No direct script access allowed');

class Products extends Controller {
    
    public function index()
    {
        $this->data['products'] = $this->model->get();

        $this->template
             ->title(_SHOW_ALL_)
             ->render('products_all', $this->data);
    }
    
    public function add()
    {
        $this->data['categories'] = Model::factory('categories')->get_categories_select();

        $image = Image::instance('picture');
        
        if ($this->_validate($image))
        {
            $image->set_directory('products', true)
                  ->upload();
			
            $this->model->add($this->module->post());
            URL::redirect('/products');
        }
        
        //Module::factory('products/edit');
        $this->template
             ->title('Προσθήκη Προϊόντος')
             ->render('product_form', $this->data);
    }
    
    public function edit($id)
    {
        $this->data = $this->model->get($id);
        $this->_check_if_null($this->data);

        $this->data['categories'] = Model::factory('categories')->get_categories_select();
        
        if ($this->_validate())
        {
            $this->model->update($id, $this->module->post());
            URL::redirect('/products');
        }

        $this->template
             ->title(_EDIT_PAGE_)
             ->render('product_form', $this->data);
    }
    
    public function delete($id)
    {
        $this->model->delete($id);
        URL::redirect('/products');
    }
    
    public function show($id)
    {
        $this->data = $this->model->get($id);
        $this->_check_if_null($this->data);
        
        $this->template
             ->title($this->data['name'])
             ->render('product_show', $this->data);
    }
    
    private function _validate($image)
    {
        $fields = Validation::factory()
                        ->label('name', _NAME_)
                        ->label('description', _DESCRIPTION_)
                        ->rule('name', 'required')
                        ->rule('name', 'max_length', 100)
                        ->rule('description', 'max_length', 300)
                        ->validate();

        $images = $image->set_types(array('jpg', 'png', 'gif'))
                        ->validate();

        return $fields && $images;
    }
    
    private function _check_if_null($resource)
    {
        if ($resource === null)
        {
            Exceptions::error_404(URL::fetch_full());
        }
    }
}