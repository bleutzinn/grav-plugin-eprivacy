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
    protected function isEU()
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
            'onTwigSiteVariables' => ['onTwigSiteVariables', 0],
            'onTwigTemplatePaths' => ['onTwigTemplatePaths', 0]
        ]);
    }


    /**
     * Add assets and pass on variables
     */
    public function onTwigSiteVariables()
    {
        $twig = $this->grav['twig'];
        $assets = $this->grav['assets'];

        $assets->addJs('plugin://eprivacy/tarteaucitron/tarteaucitron.js');

        // Determine whether the visitor IP address is from a EU member state
        $is_eu = $this->isEU();

        if ($is_eu) {
            // Get ePrivacy plugin config
            $config = $this->config->get('plugins.eprivacy');

            // Add is_eu flag to config
            $config['is_eu'] = $is_eu;

            // Add inline Javascript rendered by a custom template
            $assets->addInlineJs($twig->twig->render('partials/renderjs.html.twig', array('eprivacy' => $config)));
        }

        // Pass on variable(s) for use in Twig templates
        $this->grav['twig']->twig_vars['eprivacy']['is_eu'] = $is_eu;

    }

    /**
     * Add current directory to twig lookup paths.
     */
    public function onTwigTemplatePaths()
    {
        $this->grav['twig']->twig_paths[] = __DIR__ . '/templates';
    }
}
