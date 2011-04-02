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
        View::factory('contact/contact');
        $validation = Validation::factory()->rule('title2', 'required');
        
        if (Input::post('submit_contact'))
        {
            if (!$validation->validate())
            {
                echo $validation->errors();
            }
        }
    }
    
    private function _validate()
    {
        $validation = Validation::factory()
                        ->label('title2', 'Τίτλος2')
                        ->label('content2', 'Περιεχόμενο')
                        ->rule('title2', 'required')
                        ->rule('content2', 'required');
        
        return $validation->validate();
    }
}