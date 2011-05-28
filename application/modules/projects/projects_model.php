<?php defined('SYSTEM') or exit('No direct script access allowed');

class Projects_Model extends Model {
    
    private $_col;
    
    public function __construct() 
    {
        $this->_col = DB::instance()->projects;
    }
    
    public function add(array $input)
    {
        // Get all data from $_POST
        $data = array(
            'title'         => $input['title'],
            'description'   => $input['description'],
            'submit_date'   => DB::date()   
                );
        
        // Insert into collection
        return $this->_col->insert($data);
    }
    
    public function get($id = null)
    {
        return !is_null($id) ?
                $this->_col->findOne(array('_id' => DB::id($id))) :
                $this->_col->find();   
    }
    
    public function update($id, array $input)
    {
        $data = array(
            'title'         => $input['title'],
            'description'   => $input['description'],
            'last_edit'     => DB::date()   
                );
        
        $this->_col->update(array('_id' => DB::id($id)), 
                            array('$set' => $data));
    }
    
    public function delete($id)
    {
        $this->_col->remove(array('_id' => DB::id($id)));
    }
}