<?php

namespace Yoda\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Yoda\Parsers\CsvParser;
use Yoda\Template;
use Yoda\TemplateParser;

/**
 * Class ParseFileCommand
 *
 * @package Yoda\Commands
 * @author Salomon Dion <dev.mrdion@gmail.com>
 */
final class ParseFileCommand extends Command
{
    protected static $defaultName = 'parse';

    protected function configure()
    {
        $this
            ->setDescription('Replace shortcodes with their corresponding values.')
            ->setHelp('You can find the documentation at https://github.com/mrdion/yoda')
            ->addArgument('path', InputArgument::REQUIRED, 'File path')
            ->addArgument('keys', InputArgument::IS_ARRAY, 'The variables to look for (separate keys with a space)')
            ->addOption('output', 'o', InputOption::VALUE_REQUIRED, 'Output path')
            ->addOption('csv', null, InputOption::VALUE_OPTIONAL, 'CSV File path', false)
            ->addOption('dump', null, InputOption::VALUE_OPTIONAL, 'Whether to dump the content on the screen', false);
    }

    protected function interact(InputInterface $input, OutputInterface $output)
    {
        if ($input->getOption('csv') === false) {
            /** @var QuestionHelper $helper */
            $helper = $this->getHelper('question');

            // The first item is the name of the argument
            $keys = array_slice($input->getArgument('keys'), 1);
            $variables = [];

            foreach ($keys as $key) {
                $question = new Question("Please enter the value for $key: ");
                $value = $helper->ask($input, $output, $question);
                $variables[$key] = $value;
            }

            // Reinitialize argument value with key/value array
            $input->setArgument('keys', $variables);
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $csv = $input->getOption('csv');
        $outputPath = $input->getOption('output');
        $file_path = $input->getArgument('path');
        $template = new Template($file_path, $input->getArgument('keys'));

        if ($csv) {
            TemplateParser::use(new CsvParser());
            $contents = TemplateParser::parse($template, compact('csv'));
            return $this->outputToFiles($outputPath, $contents, $output, pathinfo($file_path));
        }

        $content = TemplateParser::parse($template);

        if ($input->getOption('dump')) {
            $output->writeln($content);
        }

        return $this->outputToFile($outputPath, $content, $output);
    }

    private function outputToFiles($dirname, $contents, $output, $pathinfo): bool
    {
        if (!is_dir($dirname)) {
            throw new Exception('Output path must be a folder');
        }

        ['filename' => $filename, 'extension' => $extension] = $pathinfo;

        for ($i = 0, $iMax = count($contents); $i < $iMax; $i++) {
            $path = $dirname . '/' . $filename . '.' . $i . '.' . $extension;
            $this->outputToFile($path, $contents[$i], $output);
        }

        return true;
    }

    private function outputToFile($path, $content, OutputInterface $output)
    {
        set_error_handler(function () use ($output) {
            $output->writeln('<info>You must provide an output path. Refer to the documentation.</info>');
            return false;
        });
        file_put_contents($path, $content);
        restore_error_handler();
        return true;
    }
}