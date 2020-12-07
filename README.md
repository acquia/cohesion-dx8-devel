## Installation with Composer

Using composer is the preferred way of managing your module as composer handles dependencies automatically and there is less margin for error. You can find out more about composer and how to install it here: https://getcomposer.org/. It is not recommended to edit your composer.json file manually.

Open up your terminal and navigate to your project root directory.

Run the following commands to require the module:

```
composer require acquia/cohesion-dx8-devel
```

## Enable The Module

You can now enable the modules via drush with the following commands:

```
drush pm-enable cohesion_devel
```

## Libraries

The stylesheet inspector requires:

- cssbeautify (https://github.com/senchalabs/cssbeautify)
- code-prettify (https://github.com/google/code-prettify)

They should be installed in `/libraries/cssbeautify` and `/libraries/code-prettify` respectively.

## License

Copyright (C) 2020 Acquia, Inc.

This program is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License version 2 as published by the Free Software Foundation.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.