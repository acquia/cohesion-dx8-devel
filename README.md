## Site Studio Developer Module

This library adds a number of useful features for developers working on maintaining the Site Studio codebase. It adds a new branch to the Site Studio admin menu “Developer tools”.
- Stylsheet inspector.
- Devel Settings. A configuration page that allows you to suppress errors and opt to output the saved Site Studio JSON data into a text area on the form for easy visibility.
- Sass variables: A page where you can quickly see what Sass variables are set on the site.
- Rebuild. A quick link to run a Site Studio rebuild.

### Installation with Composer

Using composer is the preferred way of managing your module as composer handles dependencies automatically and there is less margin for error. You can find out more about composer and how to install it here: https://getcomposer.org/. It is not recommended to edit your composer.json file manually.

Open up your terminal and navigate to your project root directory.

Run the following commands to require the module:

```
composer config repositories.cohesion-dx8-devel vcs https://github.com/acquia/cohesion-dx8-devel
composer require acquia/cohesion-dx8-devel
```

### Enable The Module

You can now enable the modules via drush with the following commands:

```
drush pm-enable cohesion_devel
```

### Libraries

The stylesheet inspector requires:

- cssbeautify (https://github.com/senchalabs/cssbeautify)
- code-prettify (https://github.com/google/code-prettify)

They should be installed in `/libraries/cssbeautify` and `/libraries/code-prettify` respectively.

## Site Studio debug sub-module

A debug module is also included to aid with debugging failed API requests, this is an experimental that works with Site Studio v8.2+ only.

The module captures events triggered when an API request fails and logs details of the request in a table.

Note: payloads are captured which can be large and create a sizable database over time so this module should be used with caution.

### Enable The Module

To enable the debug module, run the following command:

```
drush pm-enable sitestudio_debug
```

## License

Copyright (C) 2020 Acquia, Inc.

This program is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License version 2 as published by the Free Software Foundation.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.
