<?php  if (!defined('SYSTEM')) exit('No direct script access allowed');

class HTML {
	
    private static $_valid_atts = array(
        'action',
        'method',
        'type',
        'id',
        'name',
        'value',
        'href',
        'src',
        'width',
        'height',
        'cols',
        'rows',
        'size',
        'maxlength',
        'rel',
        'media',
        'accept-charset',
        'accept',
        'tabindex',
        'accesskey',
        'alt',
        'title',
        'class',
        'style',
        'selected',
        'checked',
        'readonly',
        'disabled'
    );
    
    /**
     * Static variable used as the 
     * return string of parse_attributes
     * 
     * @var string $data
     * @static 
     */
    private static $_data;
    
    /**
     * Produces a link tag (<a href=""></a>).
     * 
     * @access  public 
     * @param   string $link      Link anchor.
     * @param   string $text      Link text.
     * @param   array $atts       Extra link attributes array.
     * @return  string            <a> HTML element.
     * @uses    parse_attributes  Injects extra attributes.
     * @static
     */
    public static function a($link, $text, array $atts = NULL)
    {
        return '<a' . self::parse_attributes($atts) . ' href="' . $link . '">' . $text . '</a>';
    }
    
    /**
     * Produces an image tag (<img />).
     * 
     * @access  public 
     * @param   string $src       Image location.
     * @param   string $alt       Alternate text.
     * @param   array $atts       Extra image attributes array.
     * @return  string            <img> HTML element.
     * @uses    parse_attributes  Injects extra attributes.
     * @static
     */
    public static function img($src, $alt, array $atts = NULL)
    {
        return '<img src="' . $src . '" alt="' . $alt . '"' . self::parse_attributes($atts) . ' />';
    }
    
    /**
     * Produces a span tag (<span></span>).
     * 
     * @access  public 
     * @param   string $text      Span conntent.
     * @param   array $atts       Extra span attributes array.
     * @return  string            <span> HTML element.
     * @uses    parse_attributes  Injects extra attributes.
     * @static
     */
    public static function span($text, array $atts = NULL)
    {
        return '<span' . self::parse_attributes($atts) . '>' . $text . '</span>';
    }
    
    /**
     * Produces a divider tag (<div></div>).
     * 
     * @access  public 
     * @param   string $contents  Div contents.
     * @param   array $atts       Extra div attributes array.
     * @return  string            <div> HTML element.
     * @uses    parse_attributes  Injects extra attributes.
     * @static
     */
    public static function div($contents, array $atts = NULL)
    {
        return '<div' . self::parse_attributes($atts) . '>' . $contents . '</div>';
    }
    
    /**
     * Produces a paragraph tag (<p></p>).
     * 
     * @access  public 
     * @param   string $contents  Paragraph contents.
     * @param   array $atts       Extra paragraph attributes array.
     * @return  string            <p> HTML element.
     * @uses    parse_attributes  Injects extra attributes.
     * @static
     */
    public static function p($contents, array $atts = NULL)
    {
        return '<p' . self::parse_attributes($atts) . '>' . $contents . '</p>';
    }
    
    /**
     * Produces a button tag (<button></button>).
     * 
     * @access  public 
     * @param   string $name      Button name.
     * @param   string $value     Button text.
     * @param   array $atts       Extra button attributes array.
     * @return  string            <button> HTML element.
     * @uses    parse_attributes  Injects extra attributes.
     * @static
     */   
    public static function button($name, $value, array $atts)
    {
        return '<button' . self::parse_attributes($atts) . '>' . $value . '</button>';
    }
    
    /**
     * Used to produce an opening fieldset tag.
     * Fieldset legend is also produced.
     * Must always be followed by close_fieldset().
     * 
     * @access  public
     * @param   string $legend        Legend text.
     * @param   array $fieldset_atts  Extra fieldset attributes array.
     * @param   array $legend_atts    Extra legend attributes array.
     * @return  string                Opening fieldset tag.
     * @uses    parse_attributes      Injects extra attributes.
     * @see     close_fieldset
     * @static
     */
    public static function open_fieldset($legend, array $fieldset_atts = NULL, array $legend_atts = NULL)
    {
        return '<fieldset' . self::parse_attributes($fieldset_atts) . 
               '><legend' . self::parse_attributes($legend_atts) . '>' . $legend . '</legend>';
    }
    
    /**
     * Used to produce a closing fieldset tag. 
     * Must be always used after open_fieldset().
     * 
     * @access  public
     * @return  string         Closing fieldset tag.
     * @see     open_fieldset
     * @static
     */
    public static function close_fieldset()
    {
        return '</fieldset>';
    }
    
    /**
     * Parses and returns attributes associated with each element.
     * The string returned is injected in the field element that called this 
     * method.
     * 
     * @access  public 
     * @param   array $atts  Extra field arguments array.
     * @return  string       Extra attributes to be injected in field element.
     * @static
     */
    public static function parse_attributes(array $atts = NULL)
    {
        // need to empty var before check (cause it's static!)
        self::$_data = '';

        if (!empty($atts))
        {
            foreach ($atts as $att => $value)
            {
                if (!in_array($att, static::$_valid_atts, true))
                {
                    throw new Exceptions("Invalid attribute passed ('{$att}')");
                }
                
                // if attribute is empty, don't print anything
                if ($value === NULL || $value === '')
                {
                    continue;    
                }
                
                self::$_data .= ' ' . $att . '="' . $value . '"';
            }
        }
        
        return self::$_data;
    }
}