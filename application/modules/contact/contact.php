<?php defined('SYSTEM') or exit('No direct script access allowed');

class Contact extends Controller {
    
    public function index()
    {
        Module::factory('pages/add/paok/ole');
    }
    
    public function add()
    {
        if (!$this->_validate())
        {
        }
        
        Template::instance()
                ->head('')
                ->title('Προσθήκη Σελίδας')
                ->render('contact/contact', $this->_data);
    }
    
    public function load_page()
    {   
        if (!$this->_validate())
        {
        }
        
        Template::instance()
                ->head('')
                ->title('Προσθήκη Σελίδας')
                ->render('contact/contact', $this->_data);
    }
    
    private function _validate()
    {
        $validation = Validation::factory()
                                ->label('title2', 'Τίτλος2')
                                ->label('content2', 'Περιεχόμενο2')
                                ->rule('title2', 'required')
                                ->rule('content2', 'required');
       
        return $validation->validate();
    }
}