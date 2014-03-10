<?php
/**
 * Plugin_svgi
 * Displays external SVG files inline
 *
 * @author Ray Gesualdo <ray@rjgesualdo.com>
 * @link    https://github.com/raygesualdo/statamic-svgi
 */
 
class Plugin_svgi extends Plugin 
{    
    // Metadata
    var $meta = array(
        'name'       => 'SVG Inline',
        'version'    => '0.1',
        'author'     => 'Ray Gesualdo',
        'author_url' => 'http://rjgesualdo.com'
    );    

    /**
     * Index function
     * 
     * The function process the parameters and calls insertSVG().
     * The "as" parameter can accept "obj/object" or "img/image" to
     * insert the SVG file using the <object> or <img> tags respectively.
     *    
     * @return string
     */
    public function index() 
    {
        $src = $this->fetchParam('src', false, null, false, false);
        $as  = $this->fetchParam('as', false, null, false, true);
        $alt = $this->fetchParam('alt', false, null, false, false);
        
        if (!$src)
        {
            return NULL;
        }

        return $this->insertSVG($src, $as, $alt);
        
    }

    /**
     * Theme function
     * 
     * The function is equivalent to the index fuction, except it assumes
	 * a base path of the current theme directory.
     *    
     * @return string
     */
    public function theme() 
    {
        $src = $this->fetchParam('src', false, null, false, false);
        $as  = $this->fetchParam('as', false, null, false, true);
        $alt = $this->fetchParam('alt', false, null, false, false);
		
		$this->theme_assets_path = Config::getThemeAssetsPath();
        $this->theme_path        = Config::getCurrentthemePath();
        $this->theme_root        = Config::getTemplatesPath();
        $this->site_root         = Config::getSiteRoot();
        
		var_dump($this->theme_assets_path);
		var_dump($this->theme_path);
		var_dump($this->theme_root);
		var_dump($this->site_root);
		die;
		
        if (!$src)
        {
            return NULL;
        }

        return $this->insertSVG($src, $as, $alt);
        
    }

    /**
     * insertSVG function
     * 
     * The function inserts the given SVG file using parameters
     * fetched previously.
     *
     * @param string $src URL for file
     * @param string $as optionally insert as <object> or <img>
     * @param string $alt alt text for <img> tags only
     * @return string
     */
    private function insertSVG($src, $as = NULL, $alt = NULL)
    {        
        if (!$src)
        {
            return NULL;
        }                                        

        $full_src = Path::assemble(BASE_PATH, $src);
        $full_url = Config::getSiteRoot() . $src;
        $html;
        
        if ( $as == "img" || $as == "image" )
        {            
            if ($alt)
            {
                $alt = ' alt="' . $alt . '" ';
            }                    
            $html = '<img src ="' . $full_url . '" ' . $alt . '>';        
        } 
        elseif ( $as == "obj" || $as == "object" ) 
        {            
            $html = '<object type="image/svg+xml" data="' . $full_url . '"></object>';            
        }
        else
        {
            $html = file_get_contents($full_src);
            $html = preg_replace(array('/<!--(.*)-->/Uis', '/<\?(.*)\?>/Uis'), '', $html);
        }
        
        return $html;
        
    }
}