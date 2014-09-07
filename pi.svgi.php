<?php
/**
 * Plugin_svgi
 * Displays external SVG files inline
 *
 * @author Ray Gesualdo <ray@rjgesualdo.com>
 * @link   https://github.com/raygesualdo/statamic-svgi
 */
 
class Plugin_svgi extends Plugin 
{    
    // Metadata
    var $meta = array(
        'name'       => 'SVG Inline',
        'version'    => '0.2',
        'author'     => 'Ray Gesualdo',
        'author_url' => 'http://rjgesualdo.com'
    );    

    /**
     * index function
     * 
     * The function process the parameters and calls insertSvg().
     * The "as" parameter can accept "obj/object" or "img/image" to
     * insert the SVG file using the <object> or <img> tags respectively.
     *    
     * @return string
     */
    public function index() 
    {
        // Fetch paramaters
        $src = $this->fetchParam('src', false, null, false, false);
        $as  = $this->fetchParam('as', false, null, false, true);
        $alt = $this->fetchParam('alt', false, null, false, false);
        
        // Exit if no $src
        if (!$src)
        {
            return NULL;
        }

        return $this->insertSvg($src, $as, $alt);
        
    }

    /**
     * theme function
     * 
     * The function is equivalent to the index fuction, except it assumes
	 * a base path of the current theme directory.
     *    
     * @return string
     */
    public function theme() 
    {
        // Fetch parameters
        $src = $this->fetchParam('src', false, null, false, false);
        $as  = $this->fetchParam('as', false, null, false, true);
        $alt = $this->fetchParam('alt', false, null, false, false);
        $fallback = $this->fetchParam('fallback', null, null, false, false);

        // Exit if no $src
        if (!$src)
        {
            return NULL;
        }
		
        // Get theme path from global config
        $theme_path = Config::getCurrentThemePath();

        // Build new $src URL
        $src = $theme_path . $src;

        // Same for fallback
        if ($fallback)
        {
            $fallback = $theme_path . $fallback;
        }

        return $this->insertSvg($src, $as, $alt, $fallback);

    }

    /**
     * insertSvg function
     * 
     * The function inserts the given SVG file using parameters
     * fetched previously.
     *
     * @param string $src URL for file
     * @param string $as optionally insert as <object> or <img>
     * @param string $alt alt text for <img> tags only
     * @param string $fallback fallback url for <image> tag
     * @return string
     */
    private function insertSvg($src, $as = NULL, $alt = NULL, $fallback = NULL)
    {
        // Exit if no $src
        if (!$src)
        {
            return NULL;
        }                                        
        
        // Assemble system path to SVG file
        $full_src = Path::assemble(BASE_PATH, $src);
        
        // Assemble URL to SVG file
        $full_url = URL::prependSiteRoot($src);

        // Do the same to the fallback
        if ($fallback)
        {
            $full_fallback_url = URL::prependSiteRoot($fallback);
        }

        // Declare return variable
        $html;
        
        // Check $as
        if ( $as == "img" || $as == "image" )
        {            
            // Build alt tag
            if ($alt)
            {
                $alt = ' alt="' . $alt . '" ';
            } 
            // Build <img> tag                   
            $html = '<img src ="' . $full_url . '" ' . $alt . '>';        
        } 
        elseif ( $as == "obj" || $as == "object" ) 
        {            
            // Build <object> tag                   
            $html = '<object type="image/svg+xml" data="' . $full_url . '"></object>';   
        }
        elseif ( $as == "svg_image" )
        {
            // Build <svg> tag
            // see: http://lynn.ru/examples/svg/en.html for intended usage
            $html = '<svg><image xlink:href="' . $full_url . '" src="' . $full_fallback_url . '" width="100%" height="100%" /></svg>';
        }
        else
        {
            // Check if file exists
            if (file_exists($full_src))
            {
                // Get contents of SVG and remove unnecessary headers
                $html = file_get_contents($full_src);
                $html = preg_replace(array('/<!--(.*)-->/Uis', '/<\?(.*)\?>/Uis'), '', $html);
            }
            else
            {
                // Nothing to return
                $html = "";
            }
        }
        
        return $html;
        
    }

    /**
     * symbol function
     *
     * This outputs a SVG symbol reference.
     * @see http://css-tricks.com/svg-sprites-use-better-icon-fonts/
     *
     * @return string
     */
    public function symbol()
    {
        $symbol = $this->fetchParam('name', null, null, false, false);
        $class = $this->fetchParam('class', false, null, false, false);

        return "<svg class=\"$class $symbol\"><use xlink:href=\"#$symbol\"></use></svg>";
    }
}