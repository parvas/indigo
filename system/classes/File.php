<?php if (!defined('SYSTEM')) exit('No direct script access allowed');

class File {
	
    protected static $_instance;
    protected $_file = array();
    protected $_directory;
    protected $_max_size;
    protected $_types = array();
    protected $_allowed_mimes = array();
    protected $_errors = array();
    protected $_create_folder = false;
    
    protected $_mimes = array(	
            'hqx'	=>	'application/mac-binhex40',
            'cpt'	=>	'application/mac-compactpro',
            'csv'	=>	array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel'),
            'bin'	=>	'application/macbinary',
            'dms'	=>	'application/octet-stream',
            'lha'	=>	'application/octet-stream',
            'lzh'	=>	'application/octet-stream',
            'exe'	=>	array('application/octet-stream', 'application/x-msdownload'),
            'class'	=>	'application/octet-stream',
            'psd'	=>	'application/x-photoshop',
            'so'	=>	'application/octet-stream',
            'sea'	=>	'application/octet-stream',
            'dll'	=>	'application/octet-stream',
            'oda'	=>	'application/oda',
            'pdf'	=>	array('application/pdf', 'application/x-download'),
            'ai'	=>	'application/postscript',
            'eps'	=>	'application/postscript',
            'ps'	=>	'application/postscript',
            'smi'	=>	'application/smil',
            'smil'	=>	'application/smil',
            'mif'	=>	'application/vnd.mif',
            'xls'	=>	array('application/excel', 'application/vnd.ms-excel', 'application/msexcel'),
            'ppt'	=>	array('application/powerpoint', 'application/vnd.ms-powerpoint'),
            'wbxml'	=>	'application/wbxml',
            'wmlc'	=>	'application/wmlc',
            'dcr'	=>	'application/x-director',
            'dir'	=>	'application/x-director',
            'dxr'	=>	'application/x-director',
            'dvi'	=>	'application/x-dvi',
            'gtar'	=>	'application/x-gtar',
            'gz'	=>	'application/x-gzip',
            'php'	=>	'application/x-httpd-php',
            'php4'	=>	'application/x-httpd-php',
            'php3'	=>	'application/x-httpd-php',
            'phtml'	=>	'application/x-httpd-php',
            'phps'	=>	'application/x-httpd-php-source',
            'js'	=>	'application/x-javascript',
            'swf'	=>	'application/x-shockwave-flash',
            'sit'	=>	'application/x-stuffit',
            'tar'	=>	'application/x-tar',
            'tgz'	=>	array('application/x-tar', 'application/x-gzip-compressed'),
            'xhtml'	=>	'application/xhtml+xml',
            'xht'	=>	'application/xhtml+xml',
            'zip'	=>  array('application/x-zip', 'application/zip', 'application/x-zip-compressed'),
            'mid'	=>	'audio/midi',
            'midi'	=>	'audio/midi',
            'mpga'	=>	'audio/mpeg',
            'mp2'	=>	'audio/mpeg',
            'mp3'	=>	array('audio/mpeg', 'audio/mpg', 'audio/mpeg3'),
            'aif'	=>	'audio/x-aiff',
            'aiff'	=>	'audio/x-aiff',
            'aifc'	=>	'audio/x-aiff',
            'ram'	=>	'audio/x-pn-realaudio',
            'rm'	=>	'audio/x-pn-realaudio',
            'rpm'	=>	'audio/x-pn-realaudio-plugin',
            'ra'	=>	'audio/x-realaudio',
            'rv'	=>	'video/vnd.rn-realvideo',
            'wav'	=>	'audio/x-wav',
            'bmp'	=>	'image/bmp',
            'gif'	=>	'image/gif',
            'jpeg'	=>	array('image/jpeg', 'image/pjpeg'),
            'jpg'	=>	array('image/jpeg', 'image/pjpeg'),
            'jpe'	=>	array('image/jpeg', 'image/pjpeg'),
            'png'	=>	array('image/png',  'image/x-png'),
            'tiff'	=>	'image/tiff',
            'tif'	=>	'image/tiff',
            'css'	=>	'text/css',
            'html'	=>	'text/html',
            'htm'	=>	'text/html',
            'shtml'	=>	'text/html',
            'txt'	=>	'text/plain',
            'text'	=>	'text/plain',
            'log'	=>	array('text/plain', 'text/x-log'),
            'rtx'	=>	'text/richtext',
            'rtf'	=>	'text/rtf',
            'xml'	=>	'text/xml',
            'xsl'	=>	'text/xml',
            'mpeg'	=>	'video/mpeg',
            'mpg'	=>	'video/mpeg',
            'mpe'	=>	'video/mpeg',
            'qt'	=>	'video/quicktime',
            'mov'	=>	'video/quicktime',
            'avi'	=>	'video/x-msvideo',
            'movie'	=>	'video/x-sgi-movie',
            'doc'	=>	'application/msword',
            'docx'	=>	'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'xlsx'	=>	'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'word'	=>	array('application/msword', 'application/octet-stream'),
            'xl'	=>	'application/excel',
            'eml'	=>	'message/rfc822'
	);
	
    /**
     *
     * @param type $file
     * @return File
     */
    public static function instance($file = null)
    {
        if (static::$_instance)
        {
            return static::$_instance;
        }

        return static::$_instance = new File($file);
    } 
	
    protected function __construct($file)
    {
        $this->_file = Module::instance()->files($file);
    }
	
    public function upload()
    {
        if ($this->_create_folder === true)
        {
            mkdir($this->_directory);
            $this->_create_folder = false;
        }

        move_uploaded_file($this->_file['tmp_name'], $this->_directory . $this->_file['name']);
    }
    
    public function set_directory($directory, $create_dir = false)
    {
        if (!is_dir($this->_directory))
        {
            if ($create_dir === true)
            {
                $this->_create_folder = true;
            }
            elseif (!is_writable($this->_directory))
            {
                Exceptions::exception("Directory '{$this->_directory}' is not writeable");
            }
            else
            {
                Exceptions::exception("Directory '{$this->_directory}' does not exist");
            }
        }

        // always upload in preconfigured directory
        $this->_directory = APP . 'assets/uploads/' . $directory . '/';
        return $this;
    }
	
    /**
     *
     * @param type $size
     * @return File 
     */
	public function max_size($size)
	{
		$this->_max_size = $size;

		return $this;
	}
	
    /**
     *
     * @param array $types
     * @return File 
     */
	public function set_types(array $types)
	{
		//$this->_types = $types;

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
	
	public function validate()
	{
		if ($this->_pre_validate() === true)
		{
			return true;
		}
		elseif (count($this->_errors) > 0)
		{
			// PHP error, $_FILES array not populated
			return false;
		}

		$this->_validate_size();
		$this->_validate_types();

		return count($this->_errors) > 0 ? false : true;
	}
    
    protected function _pre_validate()
    {
        // If file was not submitted, skip validation
        if (!isset($this->_file['name']) || $this->_file['name'] === '')
        {
            return true;
        }
        
        I18n::instance()->load('upload');
        $general_error = sprintf(I18n::instance()->line('invalid_filesize'), $this->_file['name']);
        
        // if there is a PHP upload error, upload will not continue
        switch ($this->_file['error'])
        {
            case 0:
                break;
            case 1: 
                $this->_errors[] = sprintf(I18n::instance()->line('invalid_filesize'), $this->_file['name']);
                break;
            case 2:
                $this->_errors[] = $general_error;
                Log::write('error', "File '{$this->_file['name']}' exceeds the maximum size allowed by the submission form");
                break;
            case 3:
                $this->_errors[] = $general_error;
                Log::write('error', "File '{$this->_file['name']}' was only partially uploaded");
                break;
            case 4: 
                break;
            case 6: 
                $this->_errors[] = $general_error;
                Log::write('error', 'The temporary folder is missing');
                break;
            case 7:
                $this->_errors[] = $general_error;
                Log::write('error', "File '{$this->_file['name']}' could not be written to disk");
                break;
            case 8: 
                $this->_errors[] = $general_error; 
                Log::write('error', "File '{$this->_file['name']}' was stopped by extension");
                break;
        }
    }
    
    protected function _validate_size()
    {
        if (isset($this->_max_size) && $this->_file['size'] > $this->_max_size)
        {
            $this->_errors[] = sprintf(I18n::instance()->line('invalid_filesize'), $this->_file['name']);
        }
    }
    
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
				$this->_errors[] = sprintf(I18n::instance()->line('invalid_filetype'), $this->_file['name']);
			}
		}
	}
    
	public function get_errors()
	{
		$errors = '<ul class="errors">';

		foreach ($this->_errors as $error)
		{
			$errors .= '<li>' . $error . '</li>';
		}

		return $errors . '</ul>';
	}
}