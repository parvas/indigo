<?php if (!defined('SYSTEM')) exit('No direct script access allowed');

class Products_Model extends Model {
    
    private $_col;
    
    public function __construct() 
    {
        $this->_col = DB::instance()->products;
    }
    
    public function add(array $input)
    {
        $data = array(
            'name'         => $input['name'],
            'description'  => $input['description'],
            'parent'       => $input['parent']
                );
        
        $this->_col->insert($data);
    }
    
    public function update($id, array $input)
    {
        $data = array(
            'name'          => $input['name'],
            'description'   => $input['description'],
            'parent'        => DB::id($input['parent'])
                );
        
        $this->_col->update(array('_id' => DB::id($id)), 
                            array('$set' => $data));
    }
    
    public function delete($id)
    {
        $this->_col->remove(array('_id' => DB::id($id)));
    }
    
    public function get($id = null)
    {
        return !is_null($id) ?
                $this->_col->findOne(array('_id' => DB::id($id))) :
                $this->_col->find();   
    }
    
    public function get_categories_select()
    {
        $data = DB::as_array($this->get());
        $categories[''] = '';
        
        foreach ($data as $cat)
        {
            $categories[(string)$cat['_id']] = $cat['name'];
        }
        
        return $categories;
    }
}
