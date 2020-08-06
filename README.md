edu-sharing activity module
===========================

The edu-sharing activity module adds a new Block to the Gutenberg-Editor. 
Using the edu-sharing resource allows you to either pick content from the repository or upload it to a folder of the repository. 

Installation
------------

Copy the plugin into `wp-content/plugins/edusharing`.

After installation connect the activity module to an edu-sharing repository (settings -> Edusharing).
Then register your wordpress-site in your edu-sharing repository (admin-tools -> applications).

If this fails, you can manually register it in the repository by registering the url `https://example.com/wp-content/plugins/edusharing/metadata.php`

For a full documentation with screenshots of the post installation steps for the edu-sharing plugin visit the [documentation pages](http://docs.edu-sharing.com/confluence/edp/en).
Note that PHP SOAP extension must be installed on your webserver.

UPDATE NOTE
------------

If your edu-sharing repository version is 4.1 you have to configure this in the plugin settings!

Documentation
-------------

More information can be found on the [homepage](http://www.edu-sharing.com).

Where can I get the latest release?
-----------------------------------

You find our latest releases on our [github repository](https://github.com/edu-sharing/plugin-wordpress).

Contributing
------------

If you plan to contribute on a regular basis, please visit our [community site](http://edu-sharing-network.org/?lang=en).

License
-------
Code is under the [GNU GENERAL PUBLIC LICENSE v3](./LICENSE).
