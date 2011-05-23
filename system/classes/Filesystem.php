<?php if (!defined('SYSTEM')) exit('No direct script access allowed');

class Filesystem {
    
    public static function rename_file($old, $new)
	{
        if (!file_exists($old))
        {
            Log::write('warning', "File {$old} does not exist");
            return false;
        }
        
		return rename($old, $new);
	}
	
	public static function rename_folder($old, $new)
	{
        if (!is_dir($old))
        {
            Log::write('warning', "Folder {$old} does not exist");
            return false;
        }
        
        return rename($old, $new);
	} 
	
    /**
     * Deletes a file.
     * 
     * @access public
     * @param  string $file       Name of file to be deleted.
     * @param  string $directory  File directory.
     * @return boolean            True on successful delete, false otherwise.
     * @static 
     */
	public static function delete_file($file)
	{
        if (!file_exists($old))
        {
            Log::write('warning', "File {$old} does not exist");
            return false;
        }
        
        return unlink($directory . $file);
	}
    
    public static function delete_folder($path)
    {
        if (!is_dir($path))
        {
            Log::write('warning', "File {$old} does not exist");
            return false;
        }
        
        if (substr($path, 0, -1) !== '/')
        {
            $path .= '/';
        }
        
        $objects = scandir($path);
        
        foreach ($objects as $object) 
        {
            if ($object != '.' && $object != '..') 
            {
                if (is_dir($path . $object)) 
                {
                    static::_delete_folder($object); 
                } 
                else 
                { 
                    unlink($path . $object);
                }
            }
        }
     
        rmdir($path);
        
        return true;
    }
    
    public static function get_file($path)
    {
        return APP . 'assets/uploads/' . $path;
    }
}