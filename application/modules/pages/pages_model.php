<?php defined('SYSTEM') or exit('No direct script access allowed');

class Pages_Model extends Model {
    
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
        DB::instance()
                ->pages
                ->insert($data);
        
        // After insert, MongoDB returns _id. Sweet!
        return $data['_id'];
    }
    
    public function get($id)
    {
        return DB::instance()
                ->pages
                ->findOne(array('_id' => DB::id($id)));
    }
    
    public function update($id, array $input)
    {
        $data = array(
            'title'         => $input['title'],
            'summary'       => $input['summary'],
            'content'       => $input['content'],
            'last_edit'     => DB::date()   
                );
        
        DB::instance()
                ->pages
                ->update(array('_id' => DB::id($id)), 
                         array('$set' => $data));
    }
}