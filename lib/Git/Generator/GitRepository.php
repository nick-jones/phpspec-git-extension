<?php

namespace PhpSpecExtension\Git\Generator;

use GitElephant\Repository;

class GitRepository
{
    /**
     * @var Repository
     */
    private $repository;

    /**
     * @param Repository $git
     */
    public function __construct(Repository $git)
    {
        $this->repository = $git;
    }

    /**
     * @return GitRepository
     */
    public static function fromCurrentWorkingDirectory()
    {
        return new self(Repository::open(realpath('.')));
    }

    /**
     * @param string $filePath
     */
    public function stageFile($filePath)
    {
        $this->repository->stage($filePath);
    }
}