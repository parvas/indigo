<?php if (!defined('SYSTEM')) exit('No direct script access allowed');

class Categories_Model extends Model {
    
    public function add(array $input)
    {
        $data = array(
            'name'         => $input['category_name'],
            'description'  => $input['category_description'],
            'parent'       => $input['parent_category']
                );
        
        DB::instance()
                ->categories
                ->insert($data);
    }
    
    public function edit()
    {
        
    }
    
    public function show()
    {
        
    }
    
    public function delete()
    {
        
    }
    
    public function get_all()
    {
        $cursor = DB::instance()->categories->find();
        
        while ($cursor->hasNext())
        {
            $categories[] = $cursor->getNext();
        }
        
        foreach ($categories as $index => $details)
        {
            $id = $details['_id'];
            $data[$id['_id']] = $details['description'];
        }
        
        
        Debug::pre_print($categories);
        return $data;
    }
}
