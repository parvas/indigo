<?php if (!defined('SYSTEM')) exit('No direct script access allowed');

class Filesystem {
    
    /**
     * Renames a file in filesystem.
     * 
     * @access public
     * @param  string $old  Path to old file name.
     * @param  string $new  Path to new file name.
     * @return boolean      True on successful rename, false otherwise.
     * @uses   Filesystem::file_check  Writes into log file in case file is absent (or wrong path passed).
     * @static 
     */
    public static function rename_file($old, $new)
	{
        if (static::file_check($file) === false)
        {
            return false;
        }
        
		return rename($old, $new);
	}
	
    /**
     * Renames a folder in filesystem.
     * 
     * @access public
     * @param  string $old  Path to old folder name.
     * @param  string $new  Path to new folder name.
     * @return boolean      True on successful rename, false otherwise.
     * @uses   Filesystem::dir_check  Writes into log file in case folder is absent (or wrong path passed).
     * @static 
     */
	public static function rename_folder($old, $new)
	{
        if (static::dir_check($old) === false)
        {
            return false;
        }
        
        return rename($old, $new);
	} 
	
    /**
     * Deletes a file from filesystem.
     * 
     * @access public
     * @param  string $file  Path of file to be deleted.
     * @return boolean       True on successful delete, false otherwise.
     * @uses   Filesystem::file_check  Writes into log file in case file is absent (or wrong path passed).
     * @static 
     */
	public static function delete_file($file)
	{
        if (static::file_check($file) === false)
        {
            return false;
        }
        
        return unlink($file);
	}
    
    /**
     * Deletes a folder from filesystem.
     * 
     * @access public
     * @param  string $dir  Path of folder to be deleted.
     * @return boolean      True on successful delete, false otherwise.
     * @uses   Filesystem::dir_check  Writes into log file in case file is absent (or wrong path passed).
     * @static 
     */
    public static function delete_folder($dir)
    {
        if (static::dir_check($dir) === false)
        {
            return false;
        }
        
        // must make sure slash is appended
        if (substr($dir, 0, -1) !== '/')
        {
            $dir .= '/';
        }
        
        // we cannot delete a folder if it is not empty. So:
        // first, get contents of folder
        $objects = scandir($dir);
        
        // now scan contents one by one
        foreach ($objects as $object) 
        {
            // skip current/parent dir objects
            if ($object != '.' && $object != '..') 
            {
                if (is_dir($path . $object)) 
                {
                    // recurse if content is directory
                    static::_delete_folder($object); 
                } 
                else 
                { 
                    // just delete if content is file
                    unlink($path . $object);
                }
            }
        }
     
        // by now, directory is empty, delete!
        return rmdir($dir);
    }
    
    /**
     * Renames a file in filesystem.
     * 
     * @access public
     * @param  string $old_path  Old file path.
     * @param  string $new_path  New file path.
     * @return boolean           True on successful check, false otherwise. 
     * @uses   Filesystem::rename_folder  Renames file.
     * @static
     */
    public static function move_file($old_path, $new_path)
    {
        return static::rename_file($old_path, $new_path);
    }
    
    /**
     * Renames a folder in filesystem.
     * 
     * @access public
     * @param  string $old_path  Old folder path.
     * @param  string $new_path  New folder path.
     * @return boolean           True on successful check, false otherwise. 
     * @uses   Filesystem::rename_folder  Renames folder.
     * @static
     */
    public static function move_folder($old_path, $new_path)
    {
        return static::rename_folder($old_path, $new_path);
    }
    
    /**
     * Checks if directory exists and is writeable.
     * 
     * @access public
     * @param  string $dir  Directory path to be checked.
     * @return boolean      True on successful check, false otherwise. 
     * @uses   Log::write   Writes in log file if directory is absent or not writeable.
     * @static
     */
    public static function dir_check($dir)
    {
        if (!is_dir($dir))
        {
            Log::write('warning', "Folder '{$dir}' does not exist");
            return false;
        }
        elseif (!is_writable($dir))
        {
            Log::write('warning', "Folder '{$dir}' is not writeable");
            return false;
        }
        
        return true;
    }
    
    /**
     * Checks if file exists and is writeable.
     * 
     * @access public
     * @param  string $file  Directory path to be checked.
     * @return boolean      True on successful check, false otherwise. 
     * @uses   Log::write   Writes in log file if file is absent or not writeable.
     * @static
     */
    public static function file_check($file)
    {
        if (!file_exists($file))
        {
            Log::write('warning', "File '{$file}' does not exist");
            return false;
        }
        elseif (!is_writable($dir))
        {
            Log::write('warning', "File '{$file}' is not writeable");
            return false;
        }
        
        return true;
    }
}