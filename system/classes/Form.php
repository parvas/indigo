<?php defined('SYSTEM') or exit('No direct script access allowed');

class Form {
	
    /**
     * Used as the return string of the class.
     * 
     * @var string $_data
     * @static 
     */
    private static $_data;
	
	/**
     * Label suffix.
     * 
     * @var string $_label_suffix
     * @static 
     */
	private static $_label_suffix = '';
	
	/**
     * Array containing form labels, 
	 * to be later used by form validation.
     * 
     * @var array $_labels
     * @static 
     */
	private static $_labels = array();
    
    /**
     * Used to produce an opening form tag.
     * POST method used by default.
     * Must always be followed by close_form().
     * 
     * @access  public
     * @param   array $atts             Submit redirection page.
     * @return  string                  Extra field attributes array.
     * @uses    HTML::parse_attributes  Injects extra attributes.
     * @see     close
     * @static
     */
    public static function open(array $atts = NULL)
    {
        // if form submit method is not specified, set it to post
        $atts['method'] = isset($atts['method']) ? $atts['method'] : 'post';
        
        return '<form' . HTML::parse_attributes($atts) . '>';
    }
    
    /**
     * Used to produce a closing form tag.
     * Must always be used after open_form().
     * 
     * @access  public
     * @return  string     Closing form tag.
     * @see     open
     * @static
     */
    public static function close()
    {
        return '</form>';
    }

    /**
     * Used to produce a submit input field.
     * 
     * @access  public 
     * @param   string $name            Field name.
     * @param   string $value           Button text.
     * @param   array $atts             Extra field attributes array.
     * @return  string                  Submit field HTML element.
     * @uses    HTML::parse_attributes  Injects extra attributes.
     * @static
     */    
    public static function submit($name, $value, array $atts = NULL)
    {
        self::$_data = '<input type="submit" name="' . $name . '" value="' . $value . '"';
        
        return self::$_data . HTML::parse_attributes($atts) . ' />';
    }
    
    /**
     * Produces a text field.
     * 
     * @param   string $name  Field name.
     * @param   array $atts   Extra field attributes array.
     * @return  string        Text field HTML element.
     * @uses    _input        Renders input field.
     * @static
     */
    public static function text($name, array $atts = NULL)
    {
        return self::_input('text', $name, $atts);
    }
    
    /**
     * Produces a password field.
     * 
     * @param   string $name  Field name.
     * @param   array $atts   Extra field attributes array.
     * @return  string        Password field HTML element.
     * @uses    _input        Renders input field.
     * @static
     */
    public static function password($name, array $atts = NULL)
    {
        return self::_input('password', $name, $atts);
    }
    
    /**
     * Produces a file field.
     * 
     * @param   string $name  Field name.
     * @param   array $atts   Extra field attributes array.
     * @return  string        File field HTML element.
     * @uses    _input        Renders input field.
     * @static
     */
    public static function file($name, array $atts = NULL)
    {
        return self::_input('file', $name, $atts);
    }
    
    /**
     * Produces a hidden field.
     * 
     * @param   string $name  Field name.
     * @param   array $atts   Extra field attributes array.
     * @return  string        Hidden field HTML element.
     * @uses    _input        Renders input field.
     * @static
     */
    public static function hidden($name, array $atts = NULL)
    {
        return self::_input('hidden', $name, $atts);
    }
	
	/**
     * Produces an email field.
     * 
     * @param   string $name  Field name.
     * @param   array $atts   Extra field attributes array.
     * @return  string        Email field HTML element.
     * @uses    _input        Renders input field.
     * @static
     */
	public static function email($name, array $atts = NULL)
	{
		return self::_input('email', $name, $atts);
	}
    
    
    /**
     * Used to produce a textarea field.
     * 
     * @access  public 
     * @param   string $name            Field name.
     * @param   string $value           Field value.
     * @param   array $atts             Extra field attributes array.
     * @return  string                  Textarea HTML element.
     * @uses    _set_value              Sets default value for field.
     * @uses    HTML::parse_attributes  Injects extra attributes.
     * @static
     */ 
    public static function textarea($name, array $atts = NULL, $value = NULL)
    {
        // trying to set id automatically by name
        $atts['id'] = self::_auto_assign($atts, $name, 'id');
        // trying to set value automatically
        $value = self::_auto_assign($value, Form::_set_value($name), 'value');
        
        self::$_data = '<textarea name="' . $name . '"';

        return self::$_data . HTML::parse_attributes($atts) . '>' . $value . '</textarea>';
    }
    
    /**
     * Used to render an input field.
     * Can produce: text, password, file, checkbox, radio
     * 
     * @access  private 
     * @param   string $type            Field type.
     * @param   string $name            Field name.
     * @param   array $atts             Extra field attributes array.
     * @return  string                  Input HTML element.
     * @uses    _set_value              Sets default value for field.
     * @uses    HTML::parse_attributes  Injects extra attributes.
     * @static
     */ 
    private static function _input($type, $name, array $atts = NULL)
    {
        // trying to set id automatically by name
        $atts['id'] = self::_auto_assign($atts, $name, 'id');
        // automatically setting value
        if ($type !== 'file')
        {
            $atts['value'] = isset($atts['value']) ? 
                             	Form::_set_value($name, $atts['value']) :
                             	Form::_set_value($name);
        }
        
        self::$_data = '<input type="' . $type . '" name="' . $name . '"';

        return self::$_data . HTML::parse_attributes($atts) . ' />';
    }
    
    /**
     * Used to produce a select list field.
     * 
     * @access  public 
     * @param   string $name            Field name.
     * @param   array $options          Options list as associative array.
     * @param   string|array $selected  Selected options.
     * @param   array $atts             Extra field attributes array.
     * @return  string                  Select list HTML element.
     * @uses    HTML::parse_attributes  Injects extra attributes.
     * @uses    _set_select             Sets default value for field
     * @static
     */ 
    public static function select($name, array $options, $selected = NULL, array $atts = NULL)
    {
        // trying to set id automatically by name
        $atts['id'] = self::_auto_assign($atts, $name, 'id');
        self::$_data = '<select name="' . $name . '"' . HTML::parse_attributes($atts) . '>';
        
        // convert string to array in order to avoid is_array() check inside foreach() loop
        if (!is_null($selected) && !is_array($selected))
        {
            $selected = array($selected);
        }
        
        foreach ($options as $value => $text)
        {
            // if option text is an array, we have an optgroup
            if (is_array($text))
            {
                self::$_data .= '<optgroup label="' . $value . '">';

                foreach ($text as $opt_value => $opt_text)
                {
                    $sel = Form::_set_select($name, $opt_value, $selected) ? ' selected="selected"' : '';
                    self::$_data .= '<option value="' . $opt_value . '"' . $sel . '>' . $opt_text . '</option>';
                }

                self::$_data .= '</optgroup>';
            }
            else
            {
                $sel = Form::_set_select($name, $value, $selected) ? ' selected="selected"' : '';
                self::$_data .= '<option value="' . $value . '"' . $sel . '>' . $text . '</option>';
            }
        }
        
        return self::$_data . '</select>';
    }
    
    /**
     * Used to produce a multiple select list field.
     * 
     * @access  public 
     * @param   string $name                 Field name.
     * @param   array $options               Options list as associative array.
     * @param   string|array $selected       Selected options.
     * @param   array $atts                  Extra field attributes array.
     * @return  string                       Select list HTML element.
     * @uses    HTML::parse_attributes       Injects extra attributes.
     * @uses    set_select                   Sets default value for field
     * @static
     */ 
    public static function multiselect($name, array $options, $selected = NULL, array $atts = NULL)
    {
        $atts['multiple'] = 'multiple';
        // extracting id from original field name (e.g. name: select[] -> id: select) 
        $atts['id'] = self::_auto_assign($atts, substr($name, 0, -2), 'id');
        
        return Form::select($name, $options, $selected, $atts);
    }
    
    /**
     * Used to produce a checkbox field.
     * 
     * @access  public 
     * @param   string $name            Field name.
     * @param   string $label           Field label to be automatically set.
     * @param   boolean $checked        Whether checkbox is checked or not.
     * @param   array $atts             Extra field attributes array.
     * @return  string                  Checkbox HTML element.
     * @uses    _set_check_radio        Sets default field value.
     * @uses    HTML::parse_attributes  Injects extra attributes.
     * @uses    label                   Produces field label.
     * @static
     */ 
    public static function checkbox($name, $label, $checked = FALSE, array $atts = NULL)
    {
        // so that we can treat groups the same way when re-populating
        $value = self::_auto_assign($atts, $name, 'value');
        // trying to set id automatically by name
        $atts['id'] = self::_auto_assign($atts, $name, 'id');
        
        self::$_data = '<input type="checkbox" name="' . $name . '"';
        self::$_data .= Form::_set_checkbox($name, $checked, $value) ? ' checked="checked"' : '';
        
        return self::$_data . HTML::parse_attributes($atts) . ' />' . 
                              Form::label($label, $atts['id'], FALSE, array('class' => 'check'));
    }
    
    /**
     * Used to produce a checkbox group member.
     * 
     * @access  public 
     * @param   string $name            Field name.
     * @param   string $id              Field id.
     * @param   string $value           Field value.
     * @param   string $label           Field label to be automatically set.
     * @param   boolean $checked        Whether checkbox is checked or not.
     * @param   array $atts             Extra field attributes array.
     * @return  string                  Checkbox HTML element.
     * @uses    HTML::parse_attributes  Injects extra attributes.
     * @uses    checkbox                Renders the checkbox group member. 
     * @static
     */ 
    public static function checkbox_group($name, $id, $value, $label, $checked = FALSE, array $atts = NULL)
    {
        $atts['id']    = $id;
        $atts['value'] = $value;     
         
        return Form::checkbox($name, $label, $checked, $atts); 
    }
    
    /**
     * Used to produce a radiobutton field.
     * 
     * @access  public 
     * @param   string $name            Field name.
     * @param   boolean $checked        Whether radiobutton is checked or not.
     * @param   array $atts             Extra field attributes array.
     * @return  string                  Radiobutton HTML element.
     * @uses    _set_check_radio        Sets default field value.
     * @uses    HTML::parse_attributes  Injects extra attributes.
     * @uses    label                   Produces field label.
     * @static
     */ 
    public static function radio($name, $label, $checked = FALSE, array $atts = NULL)
    {
        // so that we can treat groups the same way when re-populating
        $value = self::_auto_assign($atts, $name, 'value');
        // trying to set id automatically by name
        $atts['id'] = self::_auto_assign($atts, $name, 'id');
        
        self::$_data = '<input type="radio" name="' . $name . '"';
        self::$_data .= Form::_set_radio($name, $checked, $value) ? ' checked="checked"' : '';
        
        return self::$_data . HTML::parse_attributes($atts) . ' />' . 
                              Form::label($label, $atts['id'], FALSE, array('class' => 'check'));
    }
    
    /**
     * Used to produce a radiobutton group member.
     * 
     * @access  public 
     * @param   string $name       Field name.
     * @param   string $id         Field id.
     * @param   string $value      Field value.
     * @param   string $label      Field label text.
     * @param   boolean $checked   Whether radiobutton is checked or not.
     * @param   array $atts        Extra field attributes array.
     * @return  string             Radiobutton HTML element.
     * @uses    radio              Renders the radiobutton group member. 
     * @static
     */ 
    public static function radio_group($name, $id, $value, $label, $checked = FALSE, array $atts = NULL)
    {
        $atts['id']    = $id;
        $atts['value'] = $value;     
         
        return Form::radio($name, $label, $checked, $atts); 
    }
    
    /**
     * Used to produce a field label.
     * 
     * @access  public  
     * @param   string $label      Label text.
     * @param   string $field      Form field to bind label.
     * @param   boolean $required  If true, an asterisk is printed indicating 
	 * 							   the field as required.
     * @param   array $atts        Extra label attributes array.
     * @return  string             Label HTML element.
     * @static
     */ 
    public static function label($label, $field, $required = FALSE, array $atts = NULL)
    {
    	self::$_labels[$field] = $label;
        
        if ($required === TRUE)
        {
            $label = '<span class="required">*</span>' . $label;  
            I18n::load();
            $atts['title'] = _REQUIRED_;  
        }
        
        self::$_data = '<label for="' . $field . '"';
	self::$_data .= HTML::parse_attributes($atts) . '>';
        return self::$_data . $label . self::$_label_suffix . '</label>';
    }
    
    /**
     * Re-populates an input, textarea or select list field with its $_POST 
     * value in case of validation failure.
     * 
     * @access  private
     * @param   string $field    Name of field to be re-populated.
     * @param   string $default  Default field value to be set if no $_POST 
	 * 							 value is found.
     * @return  string           Field value to print.
     * @static 
     */
    private static function _set_value($field, $default = NULL)
    {
        return (!isset($_POST[$field]) || count($_POST) === 0) ? $default : $_POST[$field]; 
    }
    
    /**
     * Re-populates a checkbox field with its $_POST value in case of 
	 * validation failure.
     * 
     * @access  private
     * @param   string $field     Name of field to be re-populated.
     * @param   string $value     Field value, contains value only if field is 
	 * 							  group member.
     * @param   boolean $default  Whether field is checked by default.
     * @return  boolean           If field is to be checked, an array is 
	 * 							  returned so that it can be parsed. 
     * @static 
     */
    private static function _set_checkbox($field, $default, $value)
    {
        if (count($_POST) === 0)
        {
            return $default;
        }
        
        // do we have a group?
        $field = self::_extract_array_name($field);

        if (isset($_POST[$field]))
        {
            // if field is a group, search if value is in it
            return is_array($_POST[$field]) ? 
                   	  in_array($value, $_POST[$field]) : 
                   	  isset($_POST[$field]);
        }
        
        return FALSE;
    }
	
	
	/**
     * Re-populates a checkbox field with its $_POST value in case of 
	 * validation failure.
     * 
     * @access  private
     * @param   string $field     Name of field to be re-populated.
     * @param   string $value     Field value, contains value only if field is 
	 * 							  group member.
     * @param   boolean $default  Whether field is checked by default.
     * @return  boolean           If field is to be checked, an array is 
	 * 							  returned so that it can be parsed. 
     * @static 
     */
	private static function _set_radio($field, $default, $value)
	{
		if (count($_POST) === 0)
        {
            return $default;
        }
		
		if (isset($_POST[$field]))
		{
			return ($_POST[$field] === $value);
		}
		
		return FALSE;
	}
    
    /**
     * Re-populates a select field with its $_POST value in case of 
	 * validation failure.
     * 
     * @access  private
     * @param   string $field    Name of field to be re-populated.
     * @param   string $value    Field value.
     * @param   array $default   Pre-selected options array.
     * @return  boolean          TRUE if to be selected, FALSE otherwise. 
     * @static 
     */
    private static function _set_select($field, $value, array $default = NULL)
    {
        if (count($_POST) === 0)
        {
            return (!is_null($default) && in_array($value, $default));
        }
        
		// do we have a multiselect?
        $field = self::_extract_array_name($field);
        
		if (isset($_POST[$field]))
        {
            // if list is multiselect, search if value is in it
            return is_array($_POST[$field]) ? 
                   	  in_array($value, $_POST[$field]) : 
                   	  ($_POST[$field] === $value);
        }

        return FALSE;
    }
	
	/**
	 * Autoassigns an attribute (typically id or value).
	 * 
	 * Why not just pass array value as argument (e.g. $atts['id'])? 
	 * So that PHP does not throw annoying notices if this value is not set.
	 * 
	 * @access  private
	 * @param	array|string $value  Array containing all extra field attributes, 
	 * 								 or string with attribute value.
	 * @param	string $default		 Default value to be assigned as attribute.  
	 * @param	string $att	  		 Specific attribute to be set.	
	 * @return	string		  		 Requested attribute.
	 * @static
	 */
	private static function _auto_assign($value, $default, $att)
	{
		if (is_array($value))
		{
			// search in attribute array; if not present return field name
			return isset($value[$att]) ? $value[$att] : $default;	
		}
		else
		{
			// return value if exists; default otherwise
			return !is_null($value) ? $value : $default; 	
		}
	}
	
	/**
	 * Lets user set a custom suffix for labels (default is ':').
	 * 
	 * @access  public
	 * @param   string $suffix  Suffix string.  
	 * @static
	 */
	public static function set_label_suffix($suffix)
	{
		self::$_label_suffix = $suffix;
	}
	
	/**
	 * Extracts array name from string if supplied argument is in array format,
	 * for example: string: select[] -> name: select.
	 * 
	 * @access  private
	 * @param   string $name  String containing variable name.
	 * @return  string   	  Array name if argument is array, 
	 * 					 	  the argument itself otherwise.  
	 * @static
	 */
	private static function _extract_array_name($name)
	{
		return (($pos = strpos($name, '[')) !== FALSE) ? substr($name, $pos) : $name;	
	}
	
	/**
	 * Returns all labels set in this form (so there is no need to 
	 * re-set them for validation).
	 * 
	 * @access  public
	 * @return  array $_labels  Array containing all form labels.  
	 * @static
	 */
	public static function get_labels()
	{
		return self::$_labels;
	}
        
        /**
         *
         * @return type 
         */
        public static function errors()
        {
            return Validation::factory()->errors();
        }
}