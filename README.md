# ReadMe First

**As of July 8th 2018 I've stopped working on this plugin.**

# ePrivacy Plugin

The **ePrivacy** Plugin is for [Grav CMS](https://getgrav.org/). It's purpose is to help webmasters and developers with EU ePrivacy Regulations (GDPR) compliance.

>## [EU] ePrivacy Regulations
>The European Union ePrivacy regulation has been published to broaden the scope of the current ePrivacy Directive and align the various online privacy rules that exist across EU member states. The regulation takes on board all definitions of privacy and data that were introduced within the General Data Protection Regulations, and acts to clarify and enhance it. In particular, the areas of unsolicited marketing, Cookies and Confidentiality are covered in a more specific context.   

Source: "Difference between GDPR and ePrivacy regulation", PrivacyTrust, [online](https://www.privacytrust.com/guidance/gdpr-vs-eprivacy-regulation.html) (2018-06-03)

## Introduction

### The engine and the body work

The unique Javascript library [tarteaucitron.js](https://github.com/AmauriC/tarteaucitron.js) is what does all the hard and difficult work of asking for consent and only adds the corresponding code when consent for it has been given (partly depending upon configuration). In this respect tarteaucitron.js is the engine.

This plugin is merely a piece of body work which wraps the tarteaucitron.js library and makes it easy to use in the Grav CMS.

### Mission and principles

#### Mission

To create a Grav plugin which helps webmasters and developers of Grav CMS websites to comply with the EU ePrivacy Regulations.

#### Principles

- Adhere as closely as possible to the EU ePrivacy Regulations;
- In ambiguous situations features are implemented in such a way that the privacy of the visitor is respected to the fullest ("Safe mode").

## Status

`testing: true` This plugin is maturing but still **not ready for production**. Help from the Grav community is welcome and needed to improve it.   
For feature enhancement suggestions, questions, discussion, bug reports and Pull Requests (hint) please use the [issues](https://github.com/bleutzinn/grav-plugin-eprivacy/issues) feature in this repository.


## Demo

A first demo is available at [https://festeto.net/grav-plugin-eprivacy-demo/](https://festeto.net/grav-plugin-eprivacy-demo/).


## Configuration

If you use the Admin plugin, a file with your configuration named `e-privacy.yaml` will be saved in the `user/config/plugins/` folder once the configuration is saved in Admin.

Without the Admin plugin, before configuring this plugin, you should copy the `user/plugins/e-privacy/e-privacy.yaml` to `user/config/plugins/e-privacy.yaml` and only edit that copy.

Here is the `e-privacy.yaml` configuration file default content:

```yaml
enabled: true
anchortag: '#eprivacy'
orientation: bottom
ad_blocker: true
show_alert_small: true
remove_credit: false
handle_dnt_request: true
cookieslist: true
cookies_expiry: 365
cookie_domain: ''
show_activators: false
ipstack_api_key: ''
```

All options are available in the Admin panel:

`enabled`: **Plugin status** (default **Enabled / `true`**)

`anchortag`: **Popup hashtag**; Automatically open the popup when URL has this anchor tag (default **'#eprivacy'**)

`orientation`: **Position**; Position of the banner on the page (Top or Bottom, default **`bottom`**)

`ad_blocker`: **Adblocker alert**; Display a message if an adblocker is detected (default **Enabled / `true`**)

`show_alert_small`: **Handle browser DoNotTrack**; Handle browser DoNotTrack request setting (default: **Enabled / `true`**)

`remove_credit`: **Remove credit link**; Remove credit link to tarteaucitron.js (default **Disabled / `false`**)

`handle_dnt_request`: **Small banner**; Always show the small banner on the page (default: **Enabled / `true`**)

`cookieslist`: **List cookies**; Display the list of cookies installed (default **Enabled / `true`**)

`cookies_expire`: **Cookies Expire**; How long in days the tarteaucitron.js cookie lasts (max. 365) (default **365**)

`cookie_domain`: **Cookie sudomains**; Domain name on which the cookie for the subdomains will be placed

`show_activators`: **Show activators**; Show activator buttons for disabled services (default **Disabled /  `false`**)

`ipstack_api_key`: **ipstack API Access Key**; ipstack is used to lookup whether a visit is from a EU member state

---

For a better understanding of the tarteaucitron.js options please read the [tarteaucitron.js documentation](https://github.com/AmauriC/tarteaucitron.js).


## Terminology

### "services" and "service tags"

tarteaucitron.js uses the term "services" for the functionality provided by third-party service providers or partners like Facebook and the required code blocks.    
To indicate such code blocks this plugin uses the term "service tags". 

## Usage

### Adding third-party services through service tags

The ePrivacy Plugin comes with all tarteaucitron.js service code blocks as documented by tarteaucitron.js in [Step 3: Add your services](https://opt-out.ferank.eu/en/install/) as service tags.

Service tags can be used as Twig variables in Twig (theme) templates and as shortcodes. 

### Service tags as Twig variables

Including a Third-party Tag in one of your theme templates is as simple as using any other Twig variable. This is one example: `{{ eprivacy.twitter }}`.

### Service tags as shortcodes

To insert a service tag inside your page markdown use the normal shortcode syntax like `[eprivacy-twitter][/eprivacy-twitter]` or the even shorter version `[eprivacy-twitter /]`.


## Testing

Mainly for testing it is possible to override the global configuration as set in the file `user/config/plugins/e-privacy.yaml` or via the Admin panel through page frontmatter.

For example to force a page being treated as if it was visited from the EU and have the banner show at the top of the page use this in the page frontmatter:

```
eprivacy:
    override: true
    is_eu: true
    orientation: top
```

Similarly, this will simulate a request from a non-EU member country:

```
eprivacy:
    override: true
    is_eu: false
    orientation: top
```

To temporarily disable the override use `override: false` or delete this frontmatter if done with testing.


## Requirements

### ipstack IP to geolocation API

The EU ePrivacy Regulations apply to EU citizens only. There is no way to know whether or not an anonymous visitors is a EU citizen except for asking him or her that question. (And maybe that must be a plugin feature?)

The closest is deciding what to do based on the visitor's IP address. Most geo-ip services return the region or continent besides the country (code).

A free ipstack account includes the special "is_eu" boolean flag:

>location > is_eu&nbsp;&nbsp;&nbsp;&nbsp;Returns true or false depending on whether or not the county associated with the IP is in the European Union.

Source: ipstack documentation, [online](https://ipstack.com/documentation#objects) (2018-06-03)

Using ipstack is optional but adhering to the "safe mode" principle, without it all visitors are treated as if they originate from the EU.

>Note: when developing and testing on localhost the value of "is_eu" is always `true`.


## Installation

Installing the plugin can be done in three ways.

### Admin Plugin (not available for this version)

If you use the admin plugin, you can install directly through the admin panel by going to `Plugins` and clicking on the `Add` button.

### GPM Installation (not available for this version)

Another way to install is via the [Grav Package Manager (GPM)](http://learn.getgrav.org/advanced/grav-gpm) through your system's terminal (also called the command line).  From the root of your Grav install type:

    bin/gpm install e-privacy

This will install the ePrivacy plugin into your `/user/plugins` directory within Grav. Its files can be found under `user/plugins/e-privacy`.

### Manual Installation

To manually install this plugin, just download the zip version of this repository and unzip it under `user/plugins`. Then, rename the folder to `e-privacy`. You can find these files on [GitHub](https://github.com/bleutzinn/grav-plugin-eprivacy) or via [GetGrav.org](http://getgrav.org/downloads/plugins#extras).

You should now have all the plugin files under

    user/plugins/e-privacy

## Credits

- The core of the functionality is provided by the Cookie Manager "tarteaucitron.js", see [website](https://opt-out.ferank.eu/en/) and [GitHub](https://github.com/AmauriC/tarteaucitron.js);
- Thanks goes to **perlkonig** for valuable advise during development.

## To Do

- [x] Create a demo (ETA 2018-06-20). Try the [demo](https://festeto.net/grav-plugin-eprivacy-demo/).
- [x] Use the Grav Shortcode Core Plugin to include the tarteaucitron.js third party services code blocks in the page content. Shortcode support is implemented without the use of the Grav Shortcode Core Plugin.
- [x] Make use of ipstack optional.
- [ ] Store consent.
- [ ] Improve the way the JS library tarteaucitron.js is included in this plugin's source code.
- [ ] Actively engage with the Grav community to improve this plugin (remains unchecked).

