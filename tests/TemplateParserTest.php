<?php

namespace Tests;

use Yoda\Parsers\CsvParser;
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
        $template = new Template(__DIR__ . '/template.txt', ['name' => 'Yoda']);
        $this->assertRegExp('/Yoda/', TemplateParser::parse($template));
    }

    public function test_should_handle_custom_delimiter()
    {
        $template = new Template('<% name %>', ['name' => 'Yoda']);
        $template->setDelimiters('<%', '%>');
        $this->assertEquals('Yoda', TemplateParser::parse($template));
    }

    public function test_should_handle_csv_file()
    {
        $template = new Template(__DIR__ . '/template.txt');
        TemplateParser::use(new CsvParser());
        $result = TemplateParser::parse($template, ['csv' => __DIR__ . '/template.csv']);
        $this->assertInternalType('array', $result);
        $this->assertRegExp('/Yoda/', $result[0]);
    }

    protected function tearDown()
    {
        TemplateParser::use(null);
    }
}
