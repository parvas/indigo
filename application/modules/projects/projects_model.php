<?php defined('SYSTEM') or exit('No direct script access allowed');

class Pages_Model extends Model {
    
    private $_col;
    
    public function __construct() 
    {
        $this->_col = DB::instance()->pages;
    }
    
    public function insert(array $input)
    {
        // Get all data from $_POST
        $data = array(
            'title'         => $input['title'],
            'summary'       => $input['summary'],
            'content'       => $input['content'],
            'submit_date'   => DB::date()   
                );
        
        // Insert into collection
        $this->_col->insert($data);
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
            'summary'       => $input['summary'],
            'content'       => $input['content'],
            'keywords'      => $input['keywords'],
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