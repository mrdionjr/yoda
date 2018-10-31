# Yoda

Yoda is a template parser. It allows to replace shortcodes inside a text with their corresponding values. You might want
to use Yoda to bulk send the same email to different users.

## Requirements

- PHP 7.1 and later
- Composer

## Installation

First install the package using composer:

``` shell
composer require mrdion/yoda
```

## Usage

``` php
$template = new Template('Thanks for using Yoda, {{ name }}!', ['name' => 'SuperDev'], 'test');
$result = TemplateParser::parse($template); // Thanks for using Yoda, SuperDev! 
```
