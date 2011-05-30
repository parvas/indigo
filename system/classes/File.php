<?php if (!defined('SYSTEM')) exit('No direct script access allowed');

class File {
    /**
     * @access protected
     * @var array Stores validation errors
     * @static
     */
    protected static $_errors = array();
    
    /**
     * @access protected
     * @var array Stores $_FILE info.
     */
    protected $_file = array();
    
    /**
     * @access protected
     * @var string Stores upload folder path.
     */
    protected $_directory;
    
    /**
     * @access protected
     * @var int Stores maximum upload filesize.
     */
    protected $_max_size;
    
    /**
     * @access protected
     * @var array Stores allowed upload filetypes.
     */
    protected $_types = array();
    
    /**
     * @access protected
     * @var array Stores allowed mime types.
     */
    protected $_allowed_mimes = array();
    
    /**
     * @access protected
     * @var boolean Whether to create a folder if it is missing.
     */
    protected $_create_folder = false;
    
    /**
     * @access protected
     * @var boolean Whether to remove whitespace from uploaded file name.
     */
    protected $_remove_whitespace = false;
    
    /**
     * @access protected
     * @var array Most common mime types.
     */
    protected $_mimes = array(
    'hqx'    =>    'application/mac-binhex40',
    'cpt'    =>    'application/mac-compactpro',
    'csv'    =>    array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel'),
    'bin'    =>    'application/macbinary',
    'dms'    =>    'application/octet-stream',
    'lha'    =>    'application/octet-stream',
    'lzh'    =>    'application/octet-stream',
    'exe'    =>    array('application/octet-stream', 'application/x-msdownload'),
    'class'  =>    'application/octet-stream',
    'psd'    =>    'application/x-photoshop',
    'so'     =>    'application/octet-stream',
    'sea'    =>    'application/octet-stream',
    'dll'    =>    'application/octet-stream',
    'oda'    =>    'application/oda',
    'pdf'    =>    array('application/pdf', 'application/x-download'),
    'ai'     =>    'application/postscript',
    'eps'    =>    'application/postscript',
    'ps'     =>    'application/postscript',
    'smi'    =>    'application/smil',
    'smil'   =>    'application/smil',
    'mif'    =>    'application/vnd.mif',
    'xls'    =>    array('application/excel', 'application/vnd.ms-excel', 'application/msexcel'),
    'ppt'    =>    array('application/powerpoint', 'application/vnd.ms-powerpoint'),
    'wbxml'  =>    'application/wbxml',
    'wmlc'   =>    'application/wmlc',
    'dcr'    =>    'application/x-director',
    'dir'    =>    'application/x-director',
    'dxr'    =>    'application/x-director',
    'dvi'    =>    'application/x-dvi',
    'gtar'   =>    'application/x-gtar',
    'gz'     =>    'application/x-gzip',
    'php'    =>    'application/x-httpd-php',
    'php4'   =>    'application/x-httpd-php',
    'php3'   =>    'application/x-httpd-php',
    'phtml'  =>    'application/x-httpd-php',
    'phps'   =>    'application/x-httpd-php-source',
    'js'     =>    'application/x-javascript',
    'swf'    =>    'application/x-shockwave-flash',
    'sit'    =>    'application/x-stuffit',
    'tar'    =>    'application/x-tar',
    'tgz'    =>    array('application/x-tar', 'application/x-gzip-compressed'),
    'xhtml'  =>    'application/xhtml+xml',
    'xht'    =>    'application/xhtml+xml',
    'zip'    =>    array('application/x-zip', 'application/zip', 'application/x-zip-compressed'),
    'mid'    =>    'audio/midi',
    'midi'   =>    'audio/midi',
    'mpga'   =>    'audio/mpeg',
    'mp2'    =>    'audio/mpeg',
    'mp3'    =>    array('audio/mpeg', 'audio/mpg', 'audio/mpeg3'),
    'aif'    =>    'audio/x-aiff',
    'aiff'   =>    'audio/x-aiff',
    'aifc'   =>    'audio/x-aiff',
    'ram'    =>    'audio/x-pn-realaudio',
    'rm'     =>    'audio/x-pn-realaudio',
    'rpm'    =>    'audio/x-pn-realaudio-plugin',
    'ra'     =>    'audio/x-realaudio',
    'rv'     =>    'video/vnd.rn-realvideo',
    'wav'    =>    'audio/x-wav',
    'bmp'    =>    'image/bmp',
    'gif'    =>    'image/gif',
    'jpeg'   =>    array('image/jpeg', 'image/pjpeg'),
    'jpg'    =>    array('image/jpeg', 'image/pjpeg'),
    'jpe'    =>    array('image/jpeg', 'image/pjpeg'),
    'png'    =>    array('image/png',  'image/x-png'),
    'tiff'   =>    'image/tiff',
    'tif'    =>    'image/tiff',
    'css'    =>    'text/css',
    'html'   =>    'text/html',
    'htm'    =>    'text/html',
    'shtml'  =>    'text/html',
    'txt'    =>    'text/plain',
    'text'   =>    'text/plain',
    'log'    =>    array('text/plain', 'text/x-log'),
    'rtx'    =>    'text/richtext',
    'rtf'    =>    'text/rtf',
    'xml'    =>    'text/xml',
    'xsl'    =>    'text/xml',
    'mpeg'   =>    'video/mpeg',
    'mpg'    =>    'video/mpeg',
    'mpe'    =>    'video/mpeg',
    'qt'     =>    'video/quicktime',
    'mov'    =>    'video/quicktime',
    'avi'    =>    'video/x-msvideo',
    'movie'  =>    'video/x-sgi-movie',
    'doc'    =>    'application/msword',
    'docx'   =>    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    'xlsx'   =>    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    'word'   =>    array('application/msword', 'application/octet-stream'),
    'xl'     =>    'application/excel',
    'eml'    =>    'message/rfc822'
    );
    
    /**
      * Creates a new class instance.
      *
      * @access public
      * @param  string $field                Form upload field.
      * @param  boolean $remove_whitespace   Whether to replace blanks with underscores.
      * @return File                         Current class object.
      */
    public static function factory($field, $remove_whitespace = true)
    {
        return new File($field, $remove_whitespace);
    }
    
    /**
     * Class constructor.
     * Sets blank removal and field name.
     *
     * @access protected
     * @param  string $field               Form upload field.
     * @param  boolean $remove_whitespace  Whether to replace blanks with underscores.
     */
    protected function __construct($field, $remove_whitespace)
    {
        $this->_remove_whitespace = $remove_whitespace;
        $this->_file = Module::instance()->files($field);
    }
    
    /**
     * Uploads a file on filesystem.
     *
     * @access public
     * @return boolean  True on successful upload, false otherwise.
     * @uses Log::write For various error logs.
     */
    public function upload()
    {
        // case a: directory missing
        if (!is_dir($this->_directory))
        {
            // directory create not requested, upload cannot continue
            if ($this->_create_folder === false)
            {
                Log::write('error', "Directory '{$this->_directory}' does not exist and folder creation not requested.");
                return false;
            }
            
            // create folder
            mkdir($this->_directory);
            $this->_create_folder = false;
        }
        
        // permissions issue
        if (!is_writable($this->_directory))
        {
            Log::write('error', "Directory '{$this->_directory}' is not writeable.");
            return false;
        }
        
        // has user requested  *NOT * to remove blanks?
        if ($this->_remove_whitespace === true)
        {
            $this->_file['name'] = preg_replace('/\s+/u', '_', $this->_file['name']);
        }
        
        // try upload
        if (!move_uploaded_file($this->_file['tmp_name'], $this->_directory . $this->_file['name']))
        {
            Log::write('error', "Upload for file {$this->_file['name']} failed.");
            return false;
        }
        
        // all good
        return true;
    }
    
    /**
     * Sets upload directory.
     *
     * @access public
     * @param  string $dir          Upload directory path in filesystem.
     * @param  boolean $create_dir  Whether to create directory if it is missing.
     * @return File                 Current class object.
     * @uses   Config::get          Gets upload directory configuration item.
     */
    public function set_directory($dir, $create_dir = false)
    {
        // make sure path ends in slash
        if (substr($dir, 0, -1) !== '/')
        {
            $dir .= '/';
        }
        
        if (!is_dir($this->_directory))
        {
            if ($create_dir === true)
            {
                $this->_create_folder = true;
            }
            elseif (Filesystem::dir_check($this->_directory) ===  false)
            {
                throw new Exceptions('Directory error during upload. See log for more details.');
            }
        }
        
        // always upload in preconfigured directory
        $this->_directory = Config::instance()->get('upload_directory') . $dir;
        return $this;
    }
    
    /**
     * Sets maximum upload filesize.
     *
     * @access public
     * @param  int $size  Maximum filesize (in KB)
     * @return File       Current class object.
     */
    public function set_max_size($size)
    {
        $this->_max_size = $size;
        
        return $this;
    }
    
    /**
     * Sets allowed upload filetypes.
     *
     * @access public
     * @param  array $types  Allowed upload filetypes.
     * @return File          Current class object.
     * @uses   Log::write    Writes log in case of duplicate allowed type.
     */
    public function set_types(array $types)
    {
        foreach ($types as $type)
        {
            if (in_array($type, $this->_allowed_mimes))
            {
                Log::write('warning', "Type '{$type}' already passed as allowed type");
            }
            
            if (!isset($this->_mimes[$type]))
            {
                throw new Exceptions("Invalid file type passed ({$type})");
            }
            
            if (is_array($this->_mimes[$type]))
            {
                foreach ($this->_mimes[$type] as $mime_full)
                {
                    $this->_allowed_mimes[] = $mime_full;
                }
            }
            else
            {
                $this->_allowed_mimes[] = $this->_mimes[$type];
            }
            
            $this->_types[] = $type;
        }
        
        return $this;
    }
    
    /**
     * Runs checks for a single file upload field.
     * Validation is split in sub-methods so that inheritance can be used more effectively.
     *
     * @access public
     * @return boolean  Validation result
     * @uses File::_pre_validate Runs pre-validation routines.
     * @uses File::_validate_size Checks for max filesize validity.
     * @uses File::_validate_types Checks for filetype validity.
     */
    public function validate()
    {
        if ($this->_pre_validate() === true || Validation::current()->is_active() === false)
        {
            // field is either empty, or validation was not triggered for the current form, skip...
            return true;
        }
        elseif (count(static::$_errors) > 0)
        {
            // PHP error, $_FILES array not populated
            return false;
        }
        
        $this->_validate_size();
        $this->_validate_types();
        
        return count(static::$_errors) > 0 ? false : true;
    }
    
    /**
     * Runs all necessary routines before proceeding to actual validation.
     *
     * @access protected
     * @return boolean    True if field is empty or not to be validated.
     * @static
     */
    protected function _pre_validate()
    {
        // If file was not submitted, skip validation
        if (!isset($this->_file['name']) || $this->_file['name'] === '')
        {
            return true;
        }
        
        I18n::instance()->load('upload');
        $general_error = sprintf(I18n::instance()->line('invalid_filesize'), $this->_file['name']);
        
        // check for PHP upload errors
        // if there is a PHP upload error, upload will not continue
        switch ($this->_file['error'])
        {
            case 0:
            break;
            case 1:
            static::$_errors[$this->_file['name']] = sprintf(I18n::instance()->line('invalid_filesize'), $this->_file['name']);
            break;
            case 2:
            static::$_errors[$this->_file['name']] = $general_error;
            Log::write('error', "File '{$this->_file['name']}' exceeds the maximum size allowed by the submission form");
            break;
            case 3:
            static::$_errors[$this->_file['name']] = $general_error;
            Log::write('error', "File '{$this->_file['name']}' was only partially uploaded");
            break;
            case 4:
            break;
            case 6:
            static::$_errors[$this->_file['name']] = $general_error;
            Log::write('error', 'The temporary folder is missing');
            break;
            case 7:
            static::$_errors[$this->_file['name']] = $general_error;
            Log::write('error', "File '{$this->_file['name']}' could not be written to disk");
            break;
            case 8:
            static::$_errors[$this->_file['name']] = $general_error;
            Log::write('error', "File '{$this->_file['name']}' was stopped by extension");
            break;
        }
    }
    
    /**
     * Validates an uploaded file by type, comparing it to the maximum allowed filesize.
     *
     * @access protected
     * @static
     */
    protected function _validate_size()
    {
        if (isset($this->_max_size) && $this->_file['size'] > $this->_max_size)
        {
            static::$_errors[$this->_file['name']] = sprintf(I18n::instance()->line('invalid_filesize'), $this->_file['name']);
        }
    }
    
    /**
     * Validates an uploaded file by type, comparing it to the allowed filetypes.
     * Also compares against mime type.
     *
     * @access protected
     * @static
     */
    protected function _validate_types()
    {
        if (count($this->_types) > 0)
        {
            // first make the obvious check: check by extension
            $ext = strtolower(pathinfo($this->_file['name'], PATHINFO_EXTENSION));
            
            // no need to take risks; additionally check by file contents
            $file_info = new finfo(FILEINFO_MIME_TYPE);
            $mime_type = $file_info->file($this->_file['tmp_name']);
            
            if (!in_array($ext, $this->_types) || !in_array($mime_type, $this->_allowed_mimes))
            {
                static::$_errors[$this->_file['name']] = sprintf(I18n::instance()->line('invalid_filetype'), $this->_file['name']);
            }
        }
    }
    
    /**
     * Displays validation errors for a single or all file upload fields in form.
     *
     * @access public
     * @param  string $field  The field to display errors for.
     * @return array           Validation errors.
     * @static
     */
    public static function get_errors($field = null)
    {
        if (Validation::current()->is_active() === false)
        {
            return array();
        }
        
        if (is_null($field))
        {
            return static::$_errors;
        }
        
        return isset(static::$_errors[$field]) ? static::$_errors[$field] : array();
    }
}