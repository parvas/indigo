<?php defined('SYSTEM') or exit('No direct script access allowed');

class Pages_Model extends Model {
    
    public function __construct() 
    {
        
    }
    
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
    }
    
    public function get($title)
    {
        $data = array(
            'title' => $title
        );
        
        return DB::instance()
                ->pages
                ->findOne($data);
    }
    
    public function update($prev, array $input)
    {
        $data = array(
            'title'         => $input['title'],
            'summary'       => $input['summary'],
            'content'       => $input['content'],
            'last_edit'     => DB::date()   
                );
        
        $id = $this->_get_id_by_title($prev);
        
        DB::instance()
                ->pages
                ->update(array('_id' => $id['_id']), 
                         array('$set' => $data));
        
        return $data['title'];
    }
    
    private function _get_id_by_title($title)
    {
        return DB::instance()
                ->pages
                ->findOne(array('title' => $title));
    }
}