# ePrivacy Plugin

The **ePrivacy** Plugin is for [Grav CMS](https://getgrav.org/). It's purpose is to help webmasters and developers with EU ePrivacy Regulations (GDPR) compliance.

>## [EU] ePrivacy Regulations
>The European Union ePrivacy regulation has been published to broaden the scope of the current ePrivacy Directive and align the various online privacy rules that exist across EU member states. The regulation takes on board all definitions of privacy and data that were introduced within the General Data Protection Regulations, and acts to clarify and enhance it. In particular, the areas of unsolicited marketing, Cookies and Confidentiality are covered in a more specific context.   

Source: "Difference between GDPR and ePrivacy regulation", PrivacyTrust, [online](https://www.privacytrust.com/guidance/gdpr-vs-eprivacy-regulation.html) (2018-06-03)

## Mission and principles

### Mission

To create a Grav plugin which helps webmasters and developers of Grav websites to comply with the EU ePrivacy Regulations.

### Principles

- "Safe mode": In ambiguous situations features are implemented in such a way that the privacy of the visitor is respected to the fullest.

## Status

`testing: true` This plugin is in it's early stages. Help from the Grav community is welcome and needed to improve it.   
For feature enhancement suggestions, questions, discussion, bug reports and Pull Requests (hint) please use the GitHub issues in this repository.


## Demo

A first demo is available at [https://festeto.net/grav-plugin-eprivacy-demo/](https://festeto.net/grav-plugin-eprivacy-demo/).

## Usage

### Adding services

Developer and webmasters must replace the usual code of services such as Google Analytics and Twitter by the appropriate code as documented by tarteaucitron.js in [Step 3: Add your services](https://opt-out.ferank.eu/en/install/).

In this 0.4.0 version of this plugin the best way to experiment is to include these code blocks in Twig templates.

To ease this it is planned that a next version uses the Grav Shortcode Core Plugin to do that for you.

### Testing

For testing it is possible to force a page being treated as if it was visited from the EU by using this in page frontmatter:

```
eprivacy:
    override: true
    is_eu: true
```

Similarly, this will simulate a request from a non-EU member country:

```
eprivacy:
    override: true
    is_eu: false
```

To disable the override use `override: false` or delete this frontmatter if done with testing.


## Configuration

If you use the admin plugin, a file with your configuration, and named 'e-privacy.yaml' will be saved in the `user/config/plugins/` folder once the configuration is saved in the admin.   
Without the admin plugin, before configuring this plugin, you should copy the `user/plugins/e-privacy/e-privacy.yaml` to `user/config/plugins/e-privacy.yaml` and only edit that copy.

Here is the `e-privacy.yaml` configuration file default content:

```yaml
enabled: true
anchortag: '#eprivacy'
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

`anchortag`: **Popup hashtag**; Automatically open the popup when URL has this anchor tag (default **'#eprivacy'**)

`orientation`: **Position**; Position of the banner on the page (Top or Bottom, default **`bottom`**)

`ad_blocker`: **Adblocker alert**; Display a message if an adblocker is detected (default **Enabled / `true`**)

`show_alert_small`: **Small banner**; Always show the small banner on the page (default: **Enabled / `true`**)

`cookieslist`: **List cookies**; Display the list of cookies installed (default **Enabled / `true`**)

`remove_credit`: **Remove credit link**; Remove credit link to tarteaucitron.js (default **Disabled / `false`**)

`cookie_domain`: **Cookie sudomains**; Domain name on which the cookie for the subdomains will be placed

`ipstack_api_key`: **ipstack API Access Key**; ipstack is used to lookup whether a visit is from a EU member state

`show_activators`: **Show activators**; Show activator buttons for disabled services (default **Disabled /  `false`**)

---

For a better understanding of the tarteaucitron.js options please read the [tarteaucitron.js documentation](https://github.com/AmauriC/tarteaucitron.js).


## Requirements

### ipstack IP to geolocation API

The EU ePrivacy Regulations apply to EU citizens only. There is no way to know whether or not an anonymous visitors is a EU citizen except for asking him or her that question. (And maybe that must be a plugin feature?)

The closest is deciding what to do based on the visitor's IP address. Most geo-ip services return the region or continent besides the country (code).

A free ipstack account includes the special "is_eu" boolean flag:

>location > is_eu&nbsp;&nbsp;&nbsp;&nbsp;Returns true or false depending on whether or not the county associated with the IP is in the European Union.

Source: ipstack documentation, [online](https://ipstack.com/documentation#objects) (2018-06-03)

Using ipstack is optional but adhering to the "safe mode" principle, without it all visitors are treated as if they originate from the EU.

>Note: when developing and testing on localhost the value of "is_eu" is always `true`.

### Shortcode Core Plugin

The next version will require the Grav Shortcode Core Plugin as the mechanism to include the tarteaucitron.js third party services code blocks in the page content.

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

## Credits

The core of the functionality is provided by the Cookie Manager "tarteaucitron.js", see [website](https://opt-out.ferank.eu/en/) and [GitHub](https://github.com/AmauriC/tarteaucitron.js).

## To Do

- [ ] Create a demo (ETA 2018-06-20)
- [ ] Use the Grav Shortcode Core Plugin to include the tarteaucitron.js third party services code blocks in the page content
- [x] Make use of ipstack optional
- [ ] Improve the way the JS library tarteaucitron.js is included in this plugin's source code
- [ ] Actively engage with the Grav community to improve this plugin (remains unchecked)

