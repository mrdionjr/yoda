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

class ParseFileCommand extends Command
{
    protected static $defaultName = 'file';
    private $variables = [];

    protected function configure()
    {
        $this->setDescription('Parses a file')->setHelp('This command allows you to parse a file');
        $this->addArgument('file', InputArgument::REQUIRED, 'File path');
        $this->addOption('keys', 'k', InputOption::VALUE_IS_ARRAY | InputOption::VALUE_REQUIRED, 'The variables to look for (separate keys with a space)');
        $this->addOption('output', 'o', InputOption::VALUE_OPTIONAL, 'Output path');
    }

    protected function interact(InputInterface $input, OutputInterface $output)
    {
        /** @var QuestionHelper $helper */
        $helper = $this->getHelper('question');
        $keys = $input->getOption('keys');

        foreach ($keys as $key) {
            $question = new Question("Please enter the value for $key: ");
            $value = $helper->ask($input, $output, $question);
            $this->variables[$key] = $value;
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $file_path = $input->getArgument('file');
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