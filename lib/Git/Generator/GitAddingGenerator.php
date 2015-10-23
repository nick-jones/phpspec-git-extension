<?php

namespace PhpSpecExtension\Git\Generator;

use GitElephant\Repository;

use PhpSpec\CodeGenerator\Generator\GeneratorInterface;
use PhpSpec\Locator\ResourceInterface;
use PhpSpec\Util\Filesystem;

class GitAddingGenerator implements GeneratorInterface
{
    /**
     * @var GeneratorInterface
     */
    private $delegate;

    /**
     * @var Repository
     */
    private $repository;

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @param GeneratorInterface $delegate
     * @param Repository $repository
     * @param Filesystem $filesystem
     */
    public function __construct(GeneratorInterface $delegate, Repository $repository, Filesystem $filesystem)
    {
        $this->delegate = $delegate;
        $this->repository = $repository;
        $this->filesystem = $filesystem;
    }

    /**
     * {@inheritDoc}
     */
    public function supports(ResourceInterface $resource, $generation, array $data)
    {
        return $this->delegate->supports($resource, $generation, $data);
    }

    /**
     * {@inheritDoc}
     */
    public function generate(ResourceInterface $resource, array $data)
    {
        $fileExistsMap = $this->mapFilePathsToExistence(
            $this->getFilePathsFromResource($resource)
        );

        $this->delegate->generate($resource, $data);

        $this->stageRelevantFiles($fileExistsMap);
    }

    /**
     * @param ResourceInterface $resource
     * @return string[]
     */
    private function getFilePathsFromResource(ResourceInterface $resource)
    {
        return [
            $resource->getSrcFilename(),
            $resource->getSpecFilename()
        ];
    }

    /**
     * @param string[] $filePaths
     * @return bool[]
     */
    private function mapFilePathsToExistence(array $filePaths)
    {
        $map = [];

        foreach ($filePaths as $filePath) {
            $map[$filePath] = $this->fileExists($filePath);
        }

        return $map;
    }

    /**
     * @param bool[] $filePaths
     */
    private function stageRelevantFiles(array $filePaths)
    {
        foreach ($filePaths as $filePath => $existedPriorToGeneration) {
            if (!$existedPriorToGeneration && $this->fileExists($filePath)) {
                $this->stageFile($filePath);
            }
        }
    }

    /**
     * @param string $filePath
     */
    private function stageFile($filePath)
    {
        $this->repository->stage($filePath);
    }

    /**
     * @param string $filePath
     * @return bool
     */
    private function fileExists($filePath)
    {
        return $this->filesystem->pathExists($filePath);
    }

    /**
     * {@inheritDoc}
     */
    public function getPriority()
    {
        return $this->delegate->getPriority();
    }
}