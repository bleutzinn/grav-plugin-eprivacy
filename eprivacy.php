<?php
namespace Grav\Plugin;

use Grav\Common\Plugin;
use RocketTheme\Toolbox\Event\Event;

/**
 * Class EPrivacyPlugin
 * @package Grav\Plugin
 */
class EPrivacyPlugin extends Plugin
{

    public $cookie_name = 'ePrivacyConfig';
    public static $services = [];
    

    /**
     * Get configured services configuration from plugin config 
     * 
     * 
     */
    public function getServices()
    {
        // Get ePrivacy plugin config
        $config = $this->config->get('plugins.eprivacy');
        
        self::$services = $config['services'];
        
    }


    /**
     * @return array
     *
     * The getSubscribedEvents() gives the core a list of events
     *     that the plugin wants to listen to. The key of each
     *     array section is the event that the plugin listens to
     *     and the value (in the form of an array) contains the
     *     callable (or function) as well as the priority. The
     *     higher the number the higher the priority.
     */
    public static function getSubscribedEvents()
    {
        return [
            'onPluginsInitialized' => ['onPluginsInitialized', 0]
        ];
    }

    /**
     * Get flag $is_eu from ipstack
     * 
     * From ipstack documentation:
     * location > is_eu	Returns true or false depending on whether or not the county
     * associated with the IP is in the European Union.
     * 
     * Tests:
     * This Swiss IP address: 178.255.153.180 should return 'false' since Switserland
     * is not a EU member.
     * 
     * @return boolean $is_eu
     */
    public static function isEU()
    {
        // Get IP address of visitor
        $ip = $_SERVER['REMOTE_ADDR'];
        
        if ($ip == '127.0.0.1' || $ip == '::1') {
            // Return true for testing purposes on localhost
            return true;
        }
        else {

            // Get the ipstack API Access Key from the plugin configuration
            $ipstack_api_key = $this->grav['config']->get('plugins.eprivacy.ipstack_api_key');

            // Set ipstack API parameters
            $fields = 'location.is_eu';
            $output = 'json';

            // Initialize CURL
            $ch = curl_init('http://api.ipstack.com/'.$ip.'?access_key='.$ipstack_api_key.'&fields='.$fields.'&output='.$output);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            // Do the request and store the response
            $json = curl_exec($ch);
            curl_close($ch);

            // Decode JSON response
            $api_result = json_decode($json, true);

            // Check "is_eu" object inside "location"
            if (is_null($api_result['location']['is_eu'])) {
                // Determination failed so according to the safe mode
                // principle assume EU origin
                return true;
            }
            else {
                // Return the "is_eu" value
                return $api_result['location']['is_eu'];
            }
        }
    }


    /**
     * Initialize the plugin
     */
    public function onPluginsInitialized()
    {
        // Don't proceed if we are in the admin plugin
        if ($this->isAdmin()) {
            return;
        }

        // Enable the main event we are interested in
        $this->enable([
            'onPagesInitialized' => ['onPagesInitialized', 0],
            'onTwigSiteVariables' => ['onTwigSiteVariables', 0],
            'onTwigTemplatePaths' => ['onTwigTemplatePaths', 0],
        ]);
    }

    
    /**
     * Do some work for this event, full details of events can be found
     * on the learn site: http://learn.getgrav.org/plugins/event-hooks
     *
     * @param Event $e
     */
    public function onPagesInitialized(Event $e)
    {
        // Get ePrivacy plugin config
        $config = $this->config->get('plugins.eprivacy');
        $services = $config['services'];

        $page = $this->grav['page'];
        
        // Get page content
        $content = $page->rawMarkdown();

        /*
            Search and replace shortcodes in two steps.
            First search and replace the long notation (BTW blabla is ignored):
            [eprivacy-pinterest]blabla[/eprivacy-pinterest]
            preg_match_all('/\[eprivacy-([a-zA-Z0-9_\-]+?)\](?:.*\n*)\[\/eprivacy-\1\]/is', $md, $matches);
            Secondly do the short notation:
            [eprivacy-twit-9T_-ter/]
            preg_match_all('/\[eprivacy-([a-zA-Z0-9_\-]+?)\s*\/\]/is', $md, $matches);
            
        */
        preg_match_all('/\[eprivacy-([a-zA-Z0-9_\-]+?)\](?:.*\n*)\[\/eprivacy-\1\]/is', $content, $matches);

        foreach ($matches[1] as $key => $match) {

            $tag = $this->grav['twig']->twig->render($match.'.twig', array('eprivacy' => array($match => $services[$match])));

            $content = str_replace($matches[0][$key], $tag, $content);
        }

        preg_match_all('/\[eprivacy-([a-zA-Z0-9_\-]+?)\s*\/\]/is', $content, $matches);

        foreach ($matches[1] as $key => $match) {

            $tag = $this->grav['twig']->twig->render($match.'.twig', array('eprivacy' => array($match => $services[$match])));
            $content = str_replace($matches[0][$key], $tag, $content);
        }
        // Save content
        $this->grav['page']->rawMarkdown($content);
    
    }


    /**
     * Add assets and pass on variables
     */
    public function onTwigSiteVariables()
    {
        $page = $this->grav['page'];
        $header = $page->header();
            
        // Get ePrivacy plugin config
        $config = $this->config->get('plugins.eprivacy');
        $services = $config['services'];

        // Initialize $is_eu flag
        $is_eu = null;

        // Check frontmatter for page level overrides
        if ($header->eprivacy['override'] === true) {
            if (isset($header->eprivacy['is_eu']) && gettype($header->eprivacy['is_eu']) == boolean) {
                $is_eu = $header->eprivacy['is_eu'];
            }
            else {
                $is_eu = null;
            }
            foreach ($config as $k => $v) {
                if (isset($header->eprivacy[$k])) {
                    $config[$k] = $header->eprivacy[$k];
                }
            }
        }

        // Set $is_eu if override was not done or not valid
        if (is_null($is_eu)) {
            // Use previously stored cookie if present to limit ipstack requests
            $cookie_value = [];
            if (isset ($_COOKIE[$this->cookie_name])) {
                // Get value from cookie
                $cookie_value = json_decode($_COOKIE[$this->cookie_name], true);
                $is_eu = $cookie_value['is_eu'];
            }
            else {
                // No cookie yet so determine whether the visitor IP address is
                // from a EU member state by a request to ipstack
                $is_eu = $this->isEU();

                // Set cookie with expiry of one year
                $cookie_value['is_eu'] = $is_eu;
                setcookie($this->cookie_name, json_encode($cookie_value), time()+60*60*24*365);
            }
        }

        // Add is_eu flag to config
        $config['is_eu'] = (bool)$is_eu;

        if ($is_eu) {
            // Visit from EU member state so activate tarteaucitron.js
            $assets = $this->grav['assets'];
            $assets->addJs('plugin://eprivacy/tarteaucitron/tarteaucitron.js');
           
            // Add inline Javascript to initialize TAC (rendered by a custom template)
            $assets->addInlineJs($this->grav['twig']->twig->render('init_tarteaucitron.twig', array('eprivacy' => $config)));

            // Add inline Javascript to add all services to TAC
            $assets->addInlineJs($this->grav['twig']->twig->render('add_service_jobs.twig', array('eprivacy' => array_keys($services))));

        }

        // Pass on variable(s) for use in Twig templates
        $this->grav['twig']->twig_vars['eprivacy']['is_eu'] = $is_eu;

        foreach ($services as $this->name => $this->params) {
            // Create Twig variable for service ready to be used in a template
            $service_js = $this->grav['twig']->twig->render($this->name.'.twig', array('eprivacy' => array($this->name => $this->params)));
            $this->grav['twig']->twig_vars['eprivacy'][$this->name] = $service_js;
        }
        
    }

    /**
     * Add current directory to twig lookup paths.
     */
    public function onTwigTemplatePaths()
    {
        // Add plugin templates path
        $this->grav['twig']->twig_paths[] = __DIR__ . '/templates';
        $this->grav['twig']->twig_paths[] = __DIR__ . '/templates/partials';

    }

    /**
     * Return string in quotes.
     */
    public function quoteStr($str)
    {
        return "'" . $str . "'";
    }
}
