# Yoda

A simple and lightweight command-line tool for template parsing. It allows to replace shortcodes inside a text with their corresponding values. You might want
to use Yoda to bulk send the same email to different users.

## Requirements

- PHP 7.0 and later

## Installation

### As a Phar (Recommended)

Visit the [releases](https://github.com/mrdionjr/yoda/releases) page to download the latest binaries.
A script will be set up soon to automate this process. You will now have the PHAR file yoda.phar 
which you can execute either by calling it with php like any PHP file:

```shell
$ php yoda.phar
```

Or make it executable and execute it directly:

```shell
$ chmod u+x yoda.phar # Make the PHAR executable
$ yoda.phar # Execute it
```

From there, you may place it anywhere that will make it easier for you to access (such as /usr/local/bin). 
You can even rename it to just `yoda` to avoid having to type the .phar extension every time.

```shell
$ yoda --version
```

### As a Global Composer install

This is probably the best way when you have other tools like phpunit and 
other tools installed in this way:

```shell
$ composer global require 'mrdion/yoda' --prefer-source
```

### As a Composer dependency:

``` shell
$ composer require mrdion/yoda
```

or

```json
{
  "require": {
    "mrdion/yoda": "~0.1.0"
  }
}
```

Once you have installed the application, you can run the help command to get detailed information about all of the available commands. 
This should be your go-to place for information about how to use Yoda. 
You may also find additional useful information on the wiki. 
If you happen to come across any information that could prove to be useful to others, the wiki is open for you to contribute.

```shell
$ yoda help
```

## Usage

Yoda can be used by passing the shortcodes name, it will ask you to enter the value for
each shortcodes (keys).

```shell
$ yoda parse ./template.txt keys name --output ./template-output.txt
```

The recommended approach is to store all you data in a CSV file and pass it to Yoda.

```shell
$ yoda parse ./template.txt --csv ./data.csv --output ./output-dir
```

If you have installed Yoda as a composer dependency, you can also use it programmatically.

``` php
use Yoda\Template;
use Yoda\TemplateParser;

$template = new Template('Thanks for using Yoda, {{ name }}!', ['name' => 'SuperDev'], 'test');
$result = TemplateParser::parse($template); // Thanks for using Yoda, SuperDev! 
```

There are currently two available parsers:
- SimpleParser (Default)
- CsvParser

To use the CsvParser, you have to specify the parser to the template parser.

```php
use Yoda\Template;
use Yoda\TemplateParser;
use Yoda\Parsers\CsvParser;

$template = new Template('Your {{ first_name }} and {{ last_name}}');
TemplateParser::use(new CsvParser());
$contents = TemplateParser::parse($template, ['csv' => './data.csv']);
```

You can write your custom own that implement the ParserInterface.