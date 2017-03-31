	<?php
/**
 * FXL TEMPLATE v2.0.9
 *
 * Copyright (C) 2003-2005
 * Fever XL
 * Heinrich-Heine-Str. 14, 10179 Berlin, Germany
 *
 * E-Mail: sreinecke@feverxl.de
 * Web: http://www.feverxl.de
 *
 * Copyright (C) 2007-2008
 * phlyLabs
 * c/o M. Sommerfeld, Schmidstr. 7, 10179 Berlin, Germany
 * <mso@phlylabs.de> * http://phlymail.com/
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * http://www.gnu.org/copyleft/lesser.html
 *
 * 1.9.9 2003       version 2 preview (using the first PHP 5 snaps)
 * 2.0.0 2003       initial version 2 (using later PHP 5 snaps)
 *                  bug: php5 OO related changes
 * 2.0.1 2004-03-14 bug: php5s object model changed again in Feb 2004 (__clone fix)
 * 2.0.2 2005-04-09 bug: bug in _match_block() fixed ...
 *                          Attention: more restrictive blocknames!!!
 *                       <!--\sSTART\sblock_name\s-->foo bar<!--\sEND\sblock_name\s-->
 *                       avg. performance boost 35%
 *                  bug: bug in get_content() fixed ...
 *                  add: you can escape placeholders with {\something}
 *                       (usefull for javascripts regular expressions)
 *                       avg. performance loss 15%
 * 2.0.3 2005-05-30 add: passing options directly to the constructor
 *
 * -> Starting to branch off the original class
 *
 * 2.0.4 2007-12-04 mso@phlylabs.de minor patches
 * 2.0.5 2008-01-31 mso@phlylabs.de added shortcut method fill_block
 * 2.0.6 2008-04-15 mso@phlylabs.de better error messages
 * 2.0.7 2008-09-05 j.grueneberg@formblitz.de added preg_tpl()
 * 2.0.8 2008-10-31 j.grueneberg@formblitz.de changed outpout of
 *                  error-msg (block not found) to a nicer format and
 *                  removed path-info ;)
 * 2.0.9 2009-11-02 l.schumachenko@formblitz.de performance issues
 *                  file_get_contents aus dem fxl_template constructor entfernt, da nutzlos
 * 2.1.0 2010-10-08 l.schumachenko@formblitz.de
 *                  file or string is now allowed
 */

/**
 * FB_TEMPLATE for localization
 *
 * make sure you have write permissions for your cache dir / cache file
 *
 * @param string $tpl template file
 * @param array $options several options forfxl
 * @return object fxl_template
 */
class fb_template extends fxl_template {

    public function __construct($content = false, $options = false) {
        if (!file_exists($content) && isset($_SESSION['FBportalCountry']) && $_SESSION['FBportalCountry'] != 'de') {
            $content = str_replace('/templates_' . $_SESSION['FBportalCountry'] . '/', '/templates/', $content);
        }
        parent::__construct($content, $options);
    }

}

/**
 * THE FXL TEMPLATE ENGINE core
 *
 * make sure you have write permissions for your cache dir / cache file
 *
 * @param string $tpl template file
 * @param string $ctpl cached template file
 * @return object fxl_cached_template
 */
class fxl_template {
    protected $cache_plugin_dir = '';
    protected $tpl = array(
            'param' => array('clipleft' => '{', 'clipright' => '}', 'trim_block' => 0),
            'block' => array(),
            'place' => array(),
            'template' => ''
            );
    protected $halt_on_error = true;
    protected $template_file = '';

   /**
    * fxl_template constructor
    *
    * @param string $content file name
    * @param array $options options array
    * @return object fxl_template
    */
    public function __construct($content = false, $options = false)
    {
        if ($options && is_array($options) && isset($options['cache_file']) && isset($options['cache_mode'])) {
            $this->set_template($content);
            $this->set_cache_file($options['cache_file']);
            $this->set_mode($options['cache_mode']);
            $this->init();
        } elseif (is_array($options) && isset($options['type']) && $options['type'] == 'string') {
            $this->set_template($content, 'string');
            if ($this->tpl['template']) {
                $this->init();
            }
        } elseif ($content) {
            if (!file_exists($content) || !is_readable($content)) {
                @internal_mail(ADMIN_MAIL,
                        'ALERT: '.HOST_URL.' Template nicht gefunden!',
                        'TEMPLATE: ' . $content .
                        "\nHOST: " . $_SERVER['HTTP_HOST'] .
                        "\nREQUEST: " . $_SERVER['REQUEST_URI'] . "\n" . print_r($_REQUEST, 1)
                    );
                die('<h1>Fehler</h1><p>Bitte klicken Sie <a href="/">hier</a> um zur Startseite zu gelangen oder kontaktieren'
                . ' Sie uns per E-mail: <a href="mailto:support@formblitz.de?subject=Fehler:+tplnotfound+'
                . basename($content) . '+' . htmlspecialchars(HOST_URL.$_SERVER['REQUEST_URI'], ENT_QUOTES) . '">support@formblitz.de</a><hr>'
                . '<p><em>DEBUG-INFO:</em> TPL: '.basename($content).' not found.');
            }
            $this->template_file = $content;
            $this->set_template($content, 'file');
            if ($this->tpl['template']) $this->init();
        }
    }

   /**
    * interface for cache plugins like fxl_ser_template
    *
    * @since 2.0.0
    * @param mixed first arg is the plugin name, additional args will be passed to the plugin
    * @return object fxl_(plugin name)_template
    */
    public function cache()
    {
        $params = func_get_args();
        $type = (count($params)) ? array_shift($params) : 'ser';
        $class = 'fxl_'.$type.'_template';
        if (!class_exists($class)) return false;
        return new $class($params);
    }

   /**
    * sets the template
    *
    * @since 2.0.0
    * @param string $data enum: file name, content string
    * @param string $type enum: file, string
    * @return bool
    */
    public function set_template($data, $type = 'file')
    {
        if ($type == 'file') {
            if (($this->tpl['template'] = file_get_contents($data))) return true;
        } elseif ($type == 'string') {
            $this->tpl['template'] = $string;
            return true;
        }
        return false;
    }

    public function set_template_cache($cache_file = false, $checksum_file = false)
    {
    	$this->cache = true;
        $this->cache_file = $cache_file;
        $this->checksum_file = $checksum_file;
        return true;
    }

   /**
    * template initialization
    * @since 2.0.0
    */
    public function init()
    {
    	return $this->parse($this->tpl['template']);
    }

   /**
    * return the parsed template as a string
    * @since 1.0.0
    */
    public function get_content()
    {
        $tmp = preg_replace('/'.$this->tpl['param']['clipleft'].'([a-z0-9\-\_]+)'.$this->tpl['param']['clipright'].'/i', '', $this->get());
        return preg_replace('/'.$this->tpl['param']['clipleft'].'\\\/i', $this->tpl['param']['clipleft'], $tmp);
    }

   /**
    * displays the template (echo to STDOUT)
    * @since 1.0.0
    */
    public function display()
    {
        echo $this->get_content();
    }

   /**
    * assign placeholders with values
    * @param string|array $var When a string is passed, this holds the placeholder name.
    *   Optionally you can pass an array, where the keys are the placeholder names and
    *   the values are the contents of the respective placeholders.
    *[@param mixed $val The actual value of the placeholder. Can be either a scalar value or an instance of (another) fxl_template]
    * @since 1.0.0
    */
    public function assign($var, $val = '')
    {
        if (is_array($var)) {
            foreach ($var as $k => $v) $this->tpl['place'][$k][] = $v;
        } elseif (is_object($val)) {
            $this->tpl['place'][$var][] = clone $val;
        } elseif ($var) {
            $this->tpl['place'][$var][] = $val;
        }
    }

   /**
    * Effectively a combination of $foo = $this->get_block('bar'); $this->assign('bar', $foo);
    *
    * @param string $str  name of the block to get assigned to the template
    */
    public function assign_block($str)
    {
        $this->assign($str, $this->get_block($str));
    }

    /**
     * Wrapper for getting a block, assigining it one or more placeholders and then
     * assigning the now filled block to its parent template again.
     *
     * @param string $blk Name of the block
     * @param mixed $var See $this->assign()
     * @param mixed $val See $this->assign()
     * @since 2.0.5
     */
    public function fill_block($blk, $var, $val = '')
    {
        $b = $this->get_block($blk);
        $b->assign($var, $val);
        $this->assign($blk, $b);
    }

   /**
    * no description available
    *
    * @since 1.0.0
    */
    public function get_block($blockname)
    {
        if (isset($this->tpl['block'][$blockname]) && is_object($this->tpl['block'][$blockname])) {
            return clone $this->tpl['block'][$blockname];
        }
        if ($this->halt_on_error) {
            /*@internal_mail(ADMIN_MAIL,
                           'ALERT: '.HOST_URL.' TPL-Block nicht gefunden!',
                           'Block: ' . $blockname . ' not found in ' . basename($this->template_file).
                           "\nHOST: " . $_SERVER['HTTP_HOST'] .
                           "\nREQUEST: " . $_SERVER['REQUEST_URI'] . "\n\$_REQUEST: " . print_r($_REQUEST, 1) );*/
            die('<h1>Fehler</h1><p>Bitte klicken Sie <a href="/">hier</a> um zur Startseite zu gelangen oder kontaktieren'
                . ' Sie uns per E-mail: <a href="mailto:g.hughes@formblitz.de?subject=Fehler:+'.$blockname.'+'
                . basename($this->template_file) . '+' . htmlspecialchars($_SERVER['REQUEST_URI'], ENT_QUOTES) . '">g.hughes@formblitz.de</a><hr>'
                . '<p><em>DEBUG-INFO:</em> Block: ' . $blockname . ' not found in ' . basename($this->template_file));
        }
        return false;
    }

   /**
    * checks a block exists or not
    * @since 2.0.0
    */
    public function block_exists($blockname)
    {
        if (isset($this->tpl['block'][$blockname]) && is_object($this->tpl['block'][$blockname])) return true;
        return false;
    }

   /**
    * no description available
    * @since 1.0.0
    */
    public function clear()
    {
        $this->tpl['place'] = array();
    }

    /**
     * INTERNAL METHODS
     */
    protected function parse($tplstring = '')
    {
        $this->tpl['template'] = $tplstring;
        $m = $this->match_block();
        for ($x = 0; $x < count($m[0]); $x++) {
        	$this->tpl['template'] = $this->parse_block($m[1][$x], $this->tpl['template']);
        	$this->tpl['block'][$m[1][$x]] = clone $this;
            $this->tpl['block'][$m[1][$x]]->tpl['place'] = array();
            $this->tpl['block'][$m[1][$x]]->tpl['block'] = array();
            $this->tpl['block'][$m[1][$x]]->parse(($this->tpl['param']['trim_block']) ? trim($m[2][$x]) : $m[2][$x]);
        }
    }

    protected function parse_block($blockname = '', $template = '')
    {
        $blockname = preg_quote($blockname);
        return preg_replace
                (($this->tpl['param']['trim_block'])
                        ? '/[\s\r\n]+<!--\sSTART\s('.$blockname.')\s-->.*<!--\sEND\s('.$blockname.')\s-->[\s\r\n]+/ms'
                        : '/<!--\sSTART\s('.$blockname.')\s-->.*<!--\sEND\s('.$blockname.')\s-->/ms'
                ,$this->tpl['param']['clipleft'].$blockname.$this->tpl['param']['clipright']
                ,$template
                );
    }

    protected function match_block()
    {
        preg_match_all('/<!--\sSTART\s([a-z0-9_]+)\s-->(.*)<!--\sEND\s(\\1)\s-->/mis', $this->tpl['template'], $m);
        return $m;
    }

    public function get()
    {
        if (count($this->tpl['place'])) {
            foreach ($this->tpl['place'] as $k => $v) {
                $replace = '';
                $limit = count($this->tpl['place'][$k]);
                for ($i = 0; $i < $limit; $i++) {
                    $replace .= (is_object($this->tpl['place'][$k][$i])) ? $this->tpl['place'][$k][$i]->get() : $this->tpl['place'][$k][$i];
                }
                $this->tpl['template'] = ($this->tpl['param']['trim_block'] == 2)
                        ? preg_replace('/[\n\r\s]*'.$this->tpl['param']['clipleft'].$k.$this->tpl['param']['clipright'].'[\n\r\s]/', trim($replace), $this->tpl['template'])
                        : str_replace($this->tpl['param']['clipleft'].$k.$this->tpl['param']['clipright'], $replace, $this->tpl['template']);
            }
        }
        return $this->tpl['template'];
    }


    /**
     * function preg_tpl
     * replaces strings in protected tpl object -- :>
     * @param $search (string) regex searchpattern
     * @param $replace (string) replacement
     * @return (bool) status
     * @since 2.0.7
     **/
    public function preg_tpl($search, $replace) {
        $this->tpl['template'] = preg_replace($search, $replace, $this->tpl['template']);
        return $this->tpl['template'] ? true : false;
    }
}

/**
 *  FXL CACHED TEMPLATE WRAPPER
 *
 *  working cache plugin example for fxl_template based on md5 and php serialize
 *  Feel free to customize it for your needs ;-)  Just use:
 *
 *  $tpl = new fxl_cached_template($tpl_file, $cache_file);
 *
 *  instead of:
 *
 *  $tpl = new fxl_template($tpl_file);
 *
 *  btw, you could also use:
 *  $tpl = new fxl_template($tpl_file, array('cache_file' => 'tpl.cache', 'cache_mode' => 'ser'));
 */
class fxl_cached_template extends fxl_ser_template {
    /**
     * fxl_cached_template constructor
     *
     * make sure you have write permissions for your cache dir / cache file
     *
     * @param string $tpl template file
     * @param string $ctpl cached template file
     * @return object fxl_cached_template
     */
    function __construct($tpl, $ctpl = false)
    {
        if (!$ctpl) $ctpl = $tpl.'.cache';
        $this->set_template($tpl);
        $this->set_cache_file($ctpl);
        $this->set_mode('ser');
        $this->init();
    }
}

/**
 * FXL TEMPLATE CACHE PLUGIN: ser v1.0.0
 * base class for the caching implementation
 */
class fxl_ser_template extends fxl_template {
    protected $mode = '';
    protected $check = true;
    protected $force = false;
    protected $cache_suffix = '.cache';
    protected $cache_prefix = '';
    protected $halt_on_error = false;
    protected $cache_file = '';
    protected $template_file = '';
    protected $version = 1.0;
    protected $sub = false;

    public function __construct($template_file = '')
    {
        if ($template_file && !$this->set_template_file($template_file)) return false;
    }

    public function set_check($val)
    {
        $this->check = (bool) $val;
        return true;
    }

    public function set_mode($val)
    {
        if (in_array($val, array('md5'))) {
            $this->mode = $val;
            return true;
        }
        return false;
    }

    public function set_template($data, $type = 'file')
    {
        if ($type == 'file') {
            if (!file_exists($data) || !is_readable($data)) die('Cannot open template file '.$content);
            $this->tpl['template'] = file_get_contents($data);
            $this->template_file = $data;
            return true;
        }
        if ($type == 'string') return (bool) $this->tpl['template'] = $data;
        return false;
    }

    public function get_cache_file_name($template_file = '') {
        if ($template_file && ($this->cache_prefix || $this->cache_suffix)) {
            return $this->cache_prefix.$this->template_file.$this->cache_suffix;
        } elseif ($this->cache_file) {
            return $this->cache_file;
        } elseif ($this->template_file && ($this->cache_prefix || $this->cache_suffix)) {
            return $this->cache_prefix.$this->template_file.$this->cache_suffix;
        }
        return false;
    }

    public function set_cache_file($filename)
    {
        return (bool) $this->cache_file = $filename;
    }

    public function init()
    {
        if (!$this->tpl['template']) return false;
        if (!$cfile = $this->get_cache_file_name()) return false;

        if ($this->check && file_exists($cfile) && is_readable($cfile)) {
            $fp = fopen($cfile, 'r');
            $header_line = fgets($fp, 256);
            $header = explode(':', $header_line, 2);
            if (!isset($header[1]) || (chop($header[1]) != md5($this->tpl['template']))) $this->force = true;
            if (!$this->force) $ser = fread($fp, filesize($cfile) - strlen($header_line));
            fclose($fp);
        } elseif ($this->check && (!file_exists($cfile))) $this->force = true;

        if ($this->force) {
        	$head = 'md5:'.md5($this->tpl['template'])."\n";
            $this->parse($this->tpl['template']);
            $cached = serialize($this);
            file_put_contents($cfile, $head.$cached);
        } else {
            $cached = unserialize($ser);
            $this->tpl = $cached->tpl;
        }
    }

    protected function md5_check($val1, $val2) { return ($val1 == md5($val2)); }

    protected function __clone()
    {
        $this->cache_file = ''; // cannot be the same
        $this->template_file = ''; // cannot be the same
        $this->sub = true;
    }

    public function version() { return $this->version; }
}

?>