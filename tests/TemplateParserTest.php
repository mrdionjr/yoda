<?php

namespace Tests;

use Yoda\MissingVariableException;
use Yoda\Template;
use Yoda\TemplateParser;
use PHPUnit\Framework\TestCase;

class TemplateParserTest extends TestCase
{
    public function test_should_replace_shortcode_with_corresponding_value()
    {
        $template = new Template('{{ name }}', ['name' => 'Yoda'], 'test');
        $this->assertEquals('Yoda', TemplateParser::parse($template));
    }

    public function test_handle_content_from_file()
    {
        $template = new Template(__DIR__ . '/template-test.txt', ['name' => 'Yoda']);
        $this->assertRegExp('/Yoda/', TemplateParser::parse($template));
    }

    public function test_should_handle_custom_delimiter()
    {
        $template = new Template('<% name %>', ['name' => 'Yoda']);
        $this->assertEquals('Yoda', TemplateParser::parse($template, '<%', '%>'));
    }

    public function test_should_throw_exception_if_missing_variable()
    {
        $template = new Template('{{ name }}', []);
        $this->expectException(MissingVariableException::class);
        TemplateParser::parse($template);
    }
}
