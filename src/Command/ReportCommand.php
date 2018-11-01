<?php

namespace Oneshoe\CloverToBitbucket\Command;

use Httpful\Request;
use Oneshoe\CloverToBitbucket\Converter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ReportCommand
 *
 * @package CloverToBitbucket\Command
 */
class ReportCommand extends Command
{
    protected $path;

    protected function configure()
    {
        $this
            ->setName('coverage:report')
            ->setDescription('Report the coverage to bitbucket')
            ->addArgument(
                'coverage.file',
                InputArgument::REQUIRED,
                'Path to the clover coverage report'
            )
            ->addArgument(
                'bitbucket.url',
                InputArgument::REQUIRED,
                'Url of bitbucket server'
            )
            ->addArgument(
                'bitbucket.user',
                InputArgument::REQUIRED,
                'Bitbucket user'
            )
            ->addArgument(
                'bitbucket.password',
                InputArgument::REQUIRED,
                'Bitbucket password or token'
            )
            ->addArgument(
                'bitbucket.commit.id',
                InputArgument::REQUIRED,
                'Bitbucket commit ID'
            )
            ->addArgument(
                'project.directory',
                InputArgument::OPTIONAL,
                "Some tools put absolute file names in coverage reports, so we need to convert them to relative in order
 to be recognized inside the remote repo. The default value is the current directory, so you don't need to set it in
 most cases.",
                getcwd()
            );
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Httpful\Exception\ConnectionErrorException
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
     * @throws \RuntimeException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $coverageFile = $input->getArgument('coverage.file');
        $username = $input->getArgument('bitbucket.user');
        $password = $input->getArgument('bitbucket.password');
        $commitId = $input->getArgument('bitbucket.commit.id');
        $url = rtrim($input->getArgument('bitbucket.url'), '/').'/rest/code-coverage/1.0/commits/'.$commitId;
        $baseDir = $input->getArgument('project.directory');

        if (!is_readable($coverageFile)) {
            throw new \RuntimeException(sprintf('The file %s does not exist', $coverageFile));
        }
        $contents = trim(file_get_contents($coverageFile));
        if (empty($contents)) {
            throw new \RuntimeException(sprintf('The file %s is empty', $coverageFile));
        }

        $cloverXml = new \SimpleXMLElement($contents);
        $result = Converter::cloverToBitbucket($cloverXml, $baseDir);
        $json = json_encode($result);

        // First delete any previous data from bitbucket.
        $deleted = Request::delete($url)
            ->expects('text')
            ->authenticateWith($username, $password)
            ->send();
        $output->writeln($deleted->body);

        // Then post the new results.
        Request::post($url)
            ->sends('json')
            ->authenticateWith($username, $password)
            ->body($json)
            ->send();

        $output->writeln('Posted '.count($result->getFiles()).' entries');
    }
}
