<?php defined('SYSTEM') or exit('No direct script access allowed');

class Pages_Model extends Model {
    
    public function insert(array $input)
    {
        $data = array(
            'title'         => $input['title'],
            'summary'       => $input['summary'],
            'content'       => $input['content'],
            'submit_date'   => DB::date()   
                );
        
        DB::instance()
                ->pages
                ->insert($data);
        
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
        
        //$id = $this->_get_id_by_title($prev);
        
        DB::instance()
                ->pages
                ->update(array('_id' => DB::id($id)), 
                         array('$set' => $data));
    }
    
    private function _get_id_by_title($title)
    {
        return DB::instance()
                ->pages
                ->findOne(array('title' => $title));
    }
}