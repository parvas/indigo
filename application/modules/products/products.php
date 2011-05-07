<?php if (!defined('SYSTEM')) exit('No direct script access allowed');

class Products extends Controller {
    
    private $_model;
    private $_template;
    
    public function __construct()
    {
        $this->_template = Template::instance();
        $this->_model = Model::factory('products');
        
        parent::__construct();
    }
    
    public function index()
    {
        $this->_data['products'] = $this->_model->get();

        $this->_template
             ->title(_SHOW_ALL_)
             ->render('products/products_all', $this->_data);
    }
    
    public function add()
    {
        $this->_data['categories'] = Model::factory('categories')->get_categories_select();
        
        if ($this->_validate())
        {
        	Debug::pre_print(Input::files());
			
			Image::instance()->upload(Input::files('product_picture'), 'products');
            //$this->_model->add(Input::post());
            //header('Location: /products');
        }

        $this->_template
             ->title('Προσθήκη Προϊόντος')
             ->render('products/product_add', $this->_data);
    }
    
    public function edit($id)
    {
        $this->_data = $this->_model->get($id);
        $this->_check_if_null($this->_data);

        $this->_data['categories'] = $this->_model->get_categories_select();
        
        if ($this->_validate())
        {
            $this->_model->update($id, Input::post());
            header('Location: /products');
        }

        $this->_template
             ->title(_EDIT_PAGE_)
             ->render('products/product_edit', $this->_data);
    }
    
    public function delete($id)
    {
        $this->_model->delete($id);
        header('Location: /products');
    }
    
    public function show($id)
    {
        $this->_data = $this->_model->get($id);
        $this->_check_if_null($this->_data);
        
        $this->_template
             ->title($this->_data['name'])
             ->render('categories/category_show', $this->_data);
    }
    
    private function _validate()
    {
        return Validation::factory()
                        ->label('product_name', _NAME_)
                        ->label('product_description', _DESCRIPTION_)
                        ->rule('product_name', 'required')
                        ->rule('product_name', 'max_length', 100)
                        ->rule('product_description', 'max_length', 300)
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