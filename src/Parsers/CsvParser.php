<?php

namespace Yoda\Parsers;

use League\Csv\Reader;
use League\Csv\Statement;
use Yoda\MissingVariableException;
use Yoda\Template;

class CsvParser implements ParserInterface
{
    /**
     * Replace shortcodes by their corresponding value from the template variables.
     *
     * @param Template $template
     * @param array $options
     * @return array
     * @throws \League\Csv\Exception
     * @throws \Exception
     */
    public static function parse(Template $template, array $options = [])
    {
        if (!isset($options['csv'])) {
            throw new \Exception('Option "csv" must be set for CsvParser');
        }

        $csv = Reader::createFromPath($options['csv'], 'r');
        $csv->setHeaderOffset(0);
        $stmt = new Statement();
        $records = $stmt->process($csv);
        $result = [];

        foreach ($records as $record) {
            $result[] = self::replaceShortcodes($options['pattern'], $template, $record);
        }

        return $result;
    }

    /**
     * @param string $pattern
     * @param Template $template
     * @param array $variables
     * @return string|string[]|null
     */
    public static function replaceShortcodes(string $pattern, Template $template, array  $variables = [])
    {
        return preg_replace_callback($pattern, function ($matches) use ($variables) {
            [$shortcode, $key] = $matches;

            if (isset($variables[$key]) ) {
                return $variables[$key];
            }

            throw new MissingVariableException("Missing variable '{$key}' for shortcode '{$shortcode}'.", 1);

        }, $template->getContent());
    }
}