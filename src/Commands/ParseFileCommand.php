<?php

namespace Yoda\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
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
    private $variables = [];

    protected function configure()
    {
        $this
            ->setDescription('Replace shortcodes with their corresponding values.')
            ->setHelp('You can find the documentation at https://github.com/mrdion/yoda')
            ->addArgument('path', InputArgument::REQUIRED, 'File path')
            ->addArgument('keys', InputArgument::IS_ARRAY, 'The variables to look for (separate keys with a space)')
            ->addOption('output', 'o', InputOption::VALUE_OPTIONAL, 'Output path');
    }

    protected function interact(InputInterface $input, OutputInterface $output)
    {
        /** @var QuestionHelper $helper */
        $helper = $this->getHelper('question');
        // The first item is the name of the argument
        $keys = array_slice($input->getArgument('keys'), 1);

        foreach ($keys as $key) {
            $question = new Question("Please enter the value for $key: ");
            $value = $helper->ask($input, $output, $question);
            $this->variables[$key] = $value;
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $file_path = $input->getArgument('path');
        ['dirname' => $dirname, 'extension' => $extension, 'filename' => $filename] = pathinfo($file_path);
        $template = new Template($file_path, $this->variables);
        $content = TemplateParser::parse($template);
        $outputPath = $input->getOption('output');

        if ($outputPath === null) {
            $outputPath = $dirname . '/' . $filename . '.output.' . $extension;
            if (!file_exists($outputPath)) {
                touch($outputPath);
            }
        }

        file_put_contents($outputPath, $content);
    }
}