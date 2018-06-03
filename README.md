# ePrivacy Plugin

The **ePrivacy** Plugin is for [Grav CMS](https://getgrav.org/). It's purpose is to help webmasters and developers with EU ePrivacy Regulations (GDPR) compliance.

>## [EU] ePrivacy Regulations
The European Union ePrivacy regulation has been published to broaden the scope of the current ePrivacy Directive and align the various online privacy rules that exist across EU member states. The regulation takes on board all definitions of privacy and data that were introduced within the General Data Protection Regulations, and acts to clarify and enhance it. In particular, the areas of unsolicited marketing, Cookies and Confidentiality are covered in a more specific context.   

Source: "Difference between GDPR and ePrivacy regulation", PrivacyTrust, [online](Difference between GDPR and ePrivacy regulation) (2018-06-03)

## Mission and principles

### Mission

To create a Grav plugin which helps webmasters and developers of Grav websites to comply with the EU ePrivacy Regulations.

### Principles

- "Safe mode" principle: In ambiguous situations features are implemented in such a way that the privacy of the visitor is respected to the fullest.

## Status

`testing: true` This plugin is in it's early stages. Help from the Grav community is welcome and needed to improve it.   
For feature enhancement suggestions, questions, discussion, bug reports and Pull Requests (hint) please use the GitHub issues in this repository.

## Installation

Installing the plugin can be done in three ways.

### Admin Plugin

If you use the admin plugin, you can install directly through the admin panel by going to `Plugins` and clicking on the `Add` button.

### GPM Installation

Another way to install is via the [Grav Package Manager (GPM)](http://learn.getgrav.org/advanced/grav-gpm) through your system's terminal (also called the command line).  From the root of your Grav install type:

    bin/gpm install e-privacy

This will install the ePrivacy plugin into your `/user/plugins` directory within Grav. Its files can be found under `user/plugins/e-privacy`.

### Manual Installation

To manually install this plugin, just download the zip version of this repository and unzip it under `user/plugins`. Then, rename the folder to `e-privacy`. You can find these files on [GitHub](https://github.com/bleutzinn/grav-plugin-eprivacy) or via [GetGrav.org](http://getgrav.org/downloads/plugins#extras).

You should now have all the plugin files under

    user/plugins/e-privacy

## Configuration

If you use the admin plugin, a file with your configuration, and named 'e-privacy.yaml' will be saved in the `user/config/plugins/` folder once the configuration is saved in the admin.   
Without the admin plugin, before configuring this plugin, you should copy the `user/plugins/e-privacy/e-privacy.yaml` to `user/config/plugins/e-privacy.yaml` and only edit that copy.

Here is the `e-privacy.yaml` configuration file default content:

```yaml
enabled: true
hashtag: '#eprivacy'
orientation: bottom
ad_blocker: true
show_alert_small: true
cookieslist: true
remove_credit: false
ipstack_api_key: ''
cookie_domain: ''
show_activators: false
```

All options are available in the Admin panel:

`enabled`: **Plugin status** (default **Enabled / `true`**)

`hashtag`: **Popup hashtag**; Automatically open the popup when URL has this hashtag (default **'#eprivacy'**)

`orientation`: **Position**; Position of the banner on the page (Top or Bottom, default **`bottom`**)

`ad_blocker`: **Adblocker alert**; Display a message if an adblocker is detected (default **Enabled / `true`**)

`show_alert_small`: **Small banner**; Always show the small banner on the page (default: **Enabled / `true`**)

`cookieslist`: **List cookies**; Display the list of cookies installed (default **Enabled / `true`**)

`remove_credit`: **Remove credit link**; Remove credit link to tarteaucitron.js (default **Disabled / `false`**)

`cookie_domain`: **Cookie sudomains**; Domain name on which the cookie for the subdomains will be placed

`ipstack_api_key`: **ipstack API Access Key**; ipstack is used to lookup whether a visit is from a EU member state

`show_activators`: **Show activators**; Show activator buttons for disabled services (default **Disabled /  `false`**)


## Usage

The EU ePrivacy Regulations apply to EU citizens only. There is no way to know whether or not an anonymous visitors is a EU citizen except for asking him or her that question. (And maybe that must be a plugin feature?)

The closest is deciding what to do based on the visitor's IP address. Most geo-ip services return the region or continent besides the country (code).

### ipstack IP to geolocation API

A free ipstack account includes the special "is_eu" boolean flag:

>location > is_eu&nbsp;&nbsp;&nbsp;&nbsp;Returns true or false depending on whether or not the county associated with the IP is in the European Union.

Source: ipstack documentation, [online](https://ipstack.com/documentation#objects) (2018-06-03)

Using ipstack is optional but adhering to the "safe mode" principle without it all visitors are treated as if they originate from the EU.


## Credits

The core of the functionality is provided by the Cookie Manager "tarteaucitron.js", see [website](https://opt-out.ferank.eu/en/) and [GitHub](https://github.com/AmauriC/tarteaucitron.js).

## To Do

- [ ] Create a demo
- [ ] Make use of ipstack optional
- [ ] Improve the way the JS library tarteaucitron.js is included in this plugin's source code
- [ ] Actively engage with the Grav community to improve this plugin (remains unchecked)

