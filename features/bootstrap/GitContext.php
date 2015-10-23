<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Context\Environment\InitializedContextEnvironment;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;

use GitElephant\Repository;
use GitElephant\Status\StatusFile;

class GitContext implements Context
{
    /**
     * @var FilesystemContext
     */
    private $fsContext;

    /**
     * @var Repository
     */
    private $repository;

    /**
     * @param BeforeScenarioScope $scope
     * @BeforeScenario
     */
    public function gatherContexts(BeforeScenarioScope $scope)
    {
        /** @var InitializedContextEnvironment $environment */
        $environment = $scope->getEnvironment();

        $this->fsContext = $environment->getContext('FilesystemContext');
    }

    /**
     * @Given I have initialised a git repository
     */
    public function iHaveInitialisedAGitRepository()
    {
        $path = $this->getFileSystemPath();

        $this->repository = Repository::open($path);
        $this->repository->init();
    }

    /**
     * @Then file :file should be staged for commit
     */
    public function fileShouldBeStagedForCommit($file)
    {
        $status = $this->repository->getStatus();
        $added = $status->added();

        $matched = $added->filter(function (StatusFile $addedFile) use ($file) {
            return $addedFile->getName() === $file;
        });

        if (count($matched) !== 1) {
            throw new \UnexpectedValueException('Failed to located added file');
        }
    }

    /**
     * Super dodgy, but avoids a lot of hassle.
     *
     * @return string
     */
    private function getFileSystemPath()
    {
        $property = new ReflectionProperty('FilesystemContext', 'workingDirectory');
        $property->setAccessible(true);

        return $property->getValue($this->fsContext);
    }
}