<?php defined('SYSTEM') or exit('No direct script access allowed');
 
class Validation {
	
    /*
     * TODO:
     * 
     * - localeconv for greek decimals
     * - postcode
     * - paypal
     * - credit card
     * - alpha_dash without preg_match
     * - date validation (newer, older, etc)
     */
    
    /**
     * Array storing current validation error messages,
     * with fields that failed a validation rule.
     * 
     * @access private
     * @var    array $_errors
     */
    private $_errors = array();
    
    /**
     * Array storing all field labels 
     * that will be used for error display purposes.
     *
     * @access private
     * @var    array $_labels
     */
    private $_labels = array();
    
    /**
     * Array storing all filters (valid PHP callbacks).
     *
     * @access private
     * @var    array $_filters
     */
    private $_filters = array();
    
    /**
     * Array storing all validation rules.
     *
     * @access private
     * @var    array $_error_messages.
     */
    private $_rules = array();
    
    /**
     * Array storing $_POST field values at submit time.
     * 
     * @access private
     * @var    array $_error_messages
     */
    private $_post = array();
    
    /**
     * Array storing custom error messages set by the user
     * 
     * @access private
     * @var    array $_custom_errors
     */
    private $_custom_errors = array();
    
    /**
     * Stores class instance. 
     * 
     * @access    private
     * @staticvar Validation 
     */
    private static $_instance;
    
    
    /**
     * Factory pattern method. Validation instances must be created 
     * by using this method.
     * 
     * @access public
     * @return Validation
     * @static 
     */
    public static function factory()
    {
        return self::$_instance = new Validation;
    }
    
    /**
     * Returns current (bound to module being executed at the time) 
     * validation instance
     * 
     * @access public
     * @return Validation
     * @static
     */
    public static function current()
    {
        return self::$_instance;
    }
    
    /**
     * Class constructor.
     */
    public function __construct()
    {
        $this->_labels = Form::get_labels();
    }
    
    /**
     * Sets or overwrites a field label. 
     * Labels have already been automatically set; no need to use this method
     * unless you want to set an unset label or overwrite an existing one.  
     * Used for validation error display purposes only.
     * 
     * @access  public  
     * @param   string $field  Field name.
     * @param   string $label  Label to be assigned.
     * @return  Validation     Current validation object state, 
     *                         so that we can use method chaining.
     */ 
    public function label($field, $label)
    {
        // if not all data is present, do nothing
        if (!empty($field) && !empty($label))
        {
            $this->_labels[$field] = $label;
        }
        
        return $this;
    }
    
    /**
     * Sets or appends a filter for a field, preparing $_POST data before validation.
     * Filter must not be empty and field name must be either present in labels 
     * array or set to TRUE.
     * All filters must be valid PHP callbacks.
     * 
     * @example  $validation->filter('username', 'trim');
     * 
     * @access  public  
     * @param   string $field   Field name.
     * @param   string $filter  Filter to be set. If TRUE, filter is set to all
     *                          fields.
     * @param   mixed  $params  Extra parameters that a specific filter may need.
     * @return  Validation      Current validation object state, 
     *                          so that we can use method chaining.
     */ 
    public function filter($field, $filter, $params = NULL)
    {
        // if prerequisites (see docs) are not met, do nothing.
        if (!empty($filter) && (in_array($field, $this->_labels) || ($field === TRUE)))
        {
            if ($field === TRUE)
            {
                // set filter to all fields
                foreach (array_keys($this->_labels) as $field_name)
                {
                    $this->_filters[$field_name][$filter] = $params;
                }
            }
            else
            {
                $this->_filters[$field][$filter] = $params;
            }
        }
        
        return $this;
    }
       
    /**
     * Sets or appends a validation rule for a field.
     * 
     * All rules must be string names of functions method names.
     * 
     * Example  $validation->rule('email', '_valid_email');
     * 
     * @access  public  
     * @param   string $field   Field name.
     * @param   string $rule    Rule to be set.
     * @param   mixed  $params  Extra parameters that a specific rule may need.
     * @return  Validation      Current validation object state, 
     *                          so that we can use method chaining.
     */    
    public function rule($field, $rule, $params = NULL)
    {
        // if not all data is present, do nothing
        if (!empty($field) && !empty($rule))
        {
            $this->_rules[$field][$rule] = $params;
        }
        
        return $this;
    } 
    
    /**
     * Runs the validation, preparing fields array for _run_rule().
     * Typically called after form is submitted.
     * 
     * @access  public
     * @return  boolean     Whether input passed validation or not.	
     * @uses    _run_rule   Executes filters and rules one at a time.
     * @uses    _set_error  Sets a new error in errors array when rule fails.
     */
    public function validate()
    {
        /* 
         * Form has not been submitted, do nothing.
         * Hack that essentially allows developer to skip 
         * form submission check when writing a controller method.  
         */
        if (count($_POST) === 0)
        {
            return false;
        }
        
        // merging filters and rules
        $this->_rules = array_merge_recursive($this->_filters, $this->_rules);
        
        foreach ($this->_rules as $field => $rules)
        {
            // Mmm... works, but need to find a more elegant solution.
            // Useful for HMVC calls.
            if (!isset($_POST[$field])) return false;
            
            // if a field is not required and rule is not "matches" or "required", 
            // check it *only* if it contains some value
            if (!array_key_exists('matches', $rules) && 
                !array_key_exists('required', $rules) && 
                (!isset($_POST[$field]) || $_POST[$field] == ''))
            {
                continue;
            }
            
            $this->_post[$field] = isset($_POST[$field]) ? $_POST[$field] : '';
            
            foreach ($rules as $rule => $params)
            {
                if ($this->_run_rule($field, $rule, $this->_post[$field], $params) === FALSE)
                {
                    // load validation error strings
                    I18n::instance()->load('form_validation');
                    
                    // validation for this rule failed, set an error
                    $this->_set_error($field, $rule, $params);

                    // no need for further checks if rule "required" failed
                    if ($rule === 'required')
                    {
                        break;
                    }
                }
            }
        }

        if (!empty($this->_errors))
        {
            return FALSE;
        }
        
        return TRUE;
    }
    
    /**
     * Displays validation errors as unordered list.
     * 
     * @access  private
     * @return  $string  Error string as unordered list.
     */
    public function errors()
    {
        $errors = '<ul class="errors">';
            
        foreach ($this->_errors as $error)
        {
            $errors .= '<li>' . $error . '</li>';
        }
            
        return $errors . '</ul>';
    }
    
    /**
     * Reduces the new rules array by first applying all filters,
     * then checks all fields against corresponding rules.
     * Run once for every filter or rule.
     * 
     * @access  private
     * @param   string $field    The field to be checked.
     * @param   string $rule     Filter or rule to be applied. 
     * @param   mixed $postdata  The actual $_POST data of the field.
     * @param   mixed $params    Extra parameters for filter of rule, if any.
     */
    private function _run_rule($field, $rule, $postdata, $params)
    {
        // if $_POST data is array, do a recursive call
        if (is_array($postdata))
        {
            foreach ($postdata as $key => $value)
            {
                $this->_run_rule($field, $rule, $value, $params);  
            }
            return;
        }
        
        // first check filters, see if rule is a valid PHP callback
        if (function_exists($rule))
        {
             $postdata = empty($params) ? $rule($postdata, $params) : $rule($postdata);	
        } // now check rules  
        elseif (method_exists($this, '_' . $rule))
        {
            // prepending underscore to match corresponding method name
            $rule = '_' . $rule;
            
            if (!empty($params))
            {
                // need to pass actual $_POST value if rule is matches()   
                if ($rule === '_matches')
                {
                    $params = $this->_post[$params];
                }
                
                return $this::$rule($postdata, $params);
            }
            else
            {
                return $this::$rule($postdata);
            }
        }
        else
        {
            echo 'Invalid parameters.';
        }        
    }
    
    /**
     * Inserts an error message in errors array.
     * Triggered when a field fails validation routine.
     * Error is inserted from local array of stored messages, using as key
     * the rule the field failed to match against.
     * 
     * @access  private
     * @param   string $field  Field that failed validation rule.
     * @param   string $rule   Validation rule the above field failed to pass.
     * @param   mixed $params  Validation rules parameters. NULL by default.
     */
    private function _set_error($field, $rule, $params = NULL)
    {
        // if user has set a custom message, copy it and return
        if (isset($this->_custom_errors[$field][$rule]))
        {
            $this->_errors[] = $this->_custom_errors[$field][$rule];
            
            return;
        }
        
        // so that we can print all of the allowed values as a string
        if ($rule === 'allowed')
        {
            $params = implode(', ', $params);
        }
		
        // "matches" rule needs label name instead of value
        if ($rule === 'matches')
        {
            $swap = $this->_labels[$field];
            $this->_labels[$field] = $this->_labels[$params];
            $params = $swap;
        }
        
        if ($params === NULL)
        {
            $this->_errors[] = sprintf(I18n::instance()->line($rule), 
                                       $this->_labels[$field]);
        }
        elseif (is_array($params))
        {
            $this->_errors[] = sprintf(I18n::instance()->line($rule), 
                                       $this->_labels[$field], 
                                       $params[0], 
                                       $params[1]);
        }
        else
        {
            $this->_errors[] = sprintf(I18n::instance()->line($rule), 
                                       $this->_labels[$field], 
                                       $params);            
        }
    }
    
    /**
     * Allows the user to set a custom error message for a specific field and rule.
     * 
     * @access  public
     * @param   string $field    Relative field for message to be set.
     * @param   string $rule     Relative rule for message to be set.
     * @param   string $message  Message to be set.
     */
    public function set_message($field, $rule, $message)
    {
        $this->_custom_errors[$field][$rule] = $message;
    }

    /**
     * Checks if field is empty.
     * 
     * @access  private  
     * @param   string $field  Field to be checked.
     * @return  boolean        Validation result.
     * @static
     */
    private static function _required($field)
    {
        return ($field != '');
    }
    
    /**
     * Checks if field value is exactly the same as another field value.
     * 
     * @access  private  
     * @param   string $field  Value to be checked.
     * @param   string $match  Value to match.
     * @return  boolean        Validation result.
     * @static
     */
    private static function _matches($field, $match = '')
    {
        return ($field === $match);
    }
    
    /**
     * Checks if field value is among predefined array of allowed values.
     * 
     * @access  private  
     * @param   string $field  Value to be checked.
     * @param   array $values  Array of allowed values.
     * @return  boolean        Validation result.
     * @static
     */
    private static function _allowed($field, array $values)
    {
        return (in_array($field, $values, TRUE));
    }
    
    /**
     * Checks if field value is long enough.
     * 
     * @access  private  
     * @param   string $field  Value to be checked.
     * @param   int $length    Maximum length.
     * @return  boolean        Validation result.
     * @static
     */
    private static function _min_length($field, $length)
    {
        return (strlen($field) >= $length);
    }
    
    /**
     * Checks if field value is short enough.
     * 
     * @access  private  
     * @param   string $field  Value to be checked.
     * @param   int $length    Maximum length.
     * @return  boolean        Validation result.
     * @static
     */
    private static function _max_length($field, $length)
    {
        return (strlen($field) <= $length);
    }
    
    /**
     * Checks if field value is exactly the right length.
     * 
     * @access  private  
     * @param   string $field  Value to be checked.
     * @param   int $length    Exact length.
     * @return  boolean        Validation result.
     * @static
     */
    private static function _exact_length($field, $length)
    {
        return (strlen($field) == $length);
    }
    
    /**
     * Checks if a number is within specified length range.
     * 
     * @access  private  
     * @param   string $field  Value to be checked.
     * @param   array $range   Length range ($range[0]=min, $range[1]=max).
     * @return  boolean        Validation result.
     * @static
     */
    private static function _length_range($field, array $range)
    {
        if (count($range) !== 2 || $range[0] >= $range[1])
        {
            throw new Exceptions('Invalid range supplied');
        }
        
        return (strlen($field) >= $range[0]) && (strlen($field) <= $range[1]);
    }
   
    /**
     * Checks if a number is large enough.
     * 
     * @access  private  
     * @param   string $field  Value to be checked.
     * @param   int $value     Minimum value.
     * @return  boolean        Validation result.
     * @static
     */
    private static function _min_value($field, $value)
    {
        return ($field >= $value);
    }

    /**
     * Checks if a number is small enough.
     * 
     * @access  private  
     * @param   string $field  Value to be checked.
     * @param   int $value     Maximum value.
     * @return  boolean        Validation result.
     * @static
     */
    private static function _max_value($field, $value)
    {
        return ($field <= $value);
    }
    
    /**
     * Checks if a number is within specified value range.
     * 
     * @access  private  
     * @param   string $field  Value to be checked.
     * @param   array $range   Value range ($range[0]=min, $range[1]=max).
     * @return  boolean        Validation result.
     * @static
     */
    private static function _value_range($field, array $range)
    {
        if (count($range) !== 2 || $range[0] >= $range[1])
        {
            throw new Exceptions('Invalid range supplied');
        }
        
        return ($field >= $range[0]) && ($field <= $range[1]);
    }
    
    /**
     * Checks if field value is numeric.
     * 
     * @access  private  
     * @param   string $field  Value to be checked.
     * @return  boolean        Validation result.
     * @static
     */
    private static function _numeric($field)
    {
        return is_numeric(str_replace(',', '.', $field));
    }
    
    /**
     * Checks if a number is an integer.
     * 
     * @access  private  
     * @param   string $field  Value to be checked.
     * @return  boolean        Validation result.
     * @static
     */
    private static function _integer($field)
    {
        return (bool)filter_var($field, FILTER_VALIDATE_INT);   
    }
    
    /**
     * Checks if a number is an integer greater than or equal to zero.
     * 
     * @access  private  
     * @param   string $field  Value to be checked.
     * @return  boolean        Validation result.
     * @static
     */
    private static function _natural($field)
    {
        return ((bool)filter_var($field, FILTER_VALIDATE_INT) && $field >= 0);
    } 
    
    /**
     * Checks if a number is an integer greater than zero.
     * 
     * @access  private  
     * @param   string $field  Value to be checked.
     * @return  boolean        Validation result.
     * @static
     */
    private static function _natural_no_zero($field)
    {
        return ((bool)filter_var($field, FILTER_VALIDATE_INT) && $field > 0);
    }
    
    /**
     * Checks if a field value is a valid date or time value.
     * 
     * @access  private  
     * @param   string $field  Value to be checked.
     * @return  boolean        Validation result.
     * @static
     */
    private static function _date_time($field)
    {
        return ((bool)strtotime($field) !== FALSE);
    }    
    
    /**
     * Checks whether a string consists of alphabetical characters only.
     * 
     * @access  private  
     * @param   string $field  Value to be checked.
     * @return  boolean        Validation result.
     * @static
     */
    private static function _alpha($field)
    {
        return ctype_alpha($field);
    }
    
    /**
     * Checks whether a string consists of alphabetical characters 
     * and numbers only.
     * 
     * @access  private 
     * @param   string $field  Value to be checked.
     * @return  boolean        Validation result.
     * @static
     */
    private static function _alpha_numeric($field)
    {
        return ctype_alnum($field);
    }
    
    /**
     * Checks whether a string consists of alphabetical characters, 
     * numbers, underscores and dashes only.
     * 
     * @access  private  
     * @param   string $field  Value to be checked.
     * @return  boolean        Validation result.
     * @static
     */
    private static function _alpha_dash($field)
    {
        
    }

    /**
     * Checks if field value is a valid postcode.
     * 
     * @access  private  
     * @param   string $field  Value to be checked.
     * @return  boolean        Validation result.
     * @static
     */    
    private static function _postcode($field)
    {
        return ctype_alnum($field);
    }
    
    /**
     * Checks if field value is a valid greek postcode.
     * 
     * @access  private  
     * @param   string $field  Value to be checked.
     * @return  boolean        Validation result.
     * @static
     */
    private static function _greek_postcode($field)
    {
        return (strlen($field) === 5 && ctype_digit($field));
    }
    
    /**
     * Checks if field value is a valid phone number.
     * 
     * @access  private  
     * @param   string $field  Value to be checked.
     * @return  boolean        Validation result.
     * @static
     */  
    private static function _phone($field)
    {
        
    }
	
    /**
     * Checks if field value is a valid credit card number.
     * 
     * @access  private  
     * @param   string $field  Value to be checked.
     * @return  boolean        Validation result.
     * @static
     */  
    private static function _credit_card($field)
    {
        
    }
    
    /**
     * Checks if field value is a valid paypal account number.
     * 
     * @access  private  
     * @param   string $field  Value to be checked.
     * @return  boolean        Validation result.
     * @static
     */ 
    private static function _paypal($field)
    {
        
    }
    
    /**
     * Checks if field value is a valid email.
     * 
     * @access  private  
     * @param   string $field  Value to be checked.
     * @return  boolean        Validation result.
     * @static
     */ 
    private static function _valid_email($email)
    {
        return (bool)filter_var($email, FILTER_VALIDATE_EMAIL);
    }
    
    /**
     * Checks if field value is a valid url.
     * 
     * @access  private  
     * @param   string $field  Value to be checked.
     * @return  boolean        Validation result.
     * @static
     */ 
    private static function _valid_url($url)
    {
        if ((substr($url, 0, 7) !== 'http://') && (substr($url, 0, 8) !== 'https://'))
        {
            $url = 'http://' . $url;
        }
        
        return (bool)filter_var($url, FILTER_VALIDATE_URL, FILTER_FLAG_HOST_REQUIRED);
    }
    
    /**
     * Checks if field value is a valid ip address.
     * 
     * @access  private  
     * @param   string $field  Value to be checked.
     * @return  boolean        Validation result.
     * @static
     */ 
    private static function _valid_ip($ip)
    {
        return (bool)filter_var($ip, FILTER_VALIDATE_IP);
    }
    
    /**
     * Matches field value against a regular expression.
     * 
     * @access  private  
     * @param   string $field  Value to be checked.
     * @param   string $regex  Regular expression to match against.
     * @return  boolean        Validation result.
     * @static
     */ 
    private static function _regex($field, $regex)
    {
        return (bool)preg_match($regex, $field);
    }
}