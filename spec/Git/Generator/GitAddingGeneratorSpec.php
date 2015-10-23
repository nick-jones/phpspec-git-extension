<?php

namespace spec\PhpSpecExtension\Git\Generator;

use GitElephant\Repository;

use PhpSpec\CodeGenerator\Generator\GeneratorInterface;
use PhpSpec\Locator\ResourceInterface;
use PhpSpec\ObjectBehavior;
use PhpSpec\Util\Filesystem;
use PhpSpecExtension\Git\Generator\GitAddingGenerator;
use Prophecy\Argument;

/**
 * @mixin GitAddingGenerator
 */
class GitAddingGeneratorSpec extends ObjectBehavior
{
    public function let(GeneratorInterface $delegate, Repository $repository, Filesystem $filesystem)
    {
        $this->beConstructedWith($delegate, $repository, $filesystem);
    }

    public function it_is_a_generator()
    {
        $this->shouldBeAnInstanceOf('PhpSpec\CodeGenerator\Generator\GeneratorInterface');
    }

    public function its_priority_is_that_of_its_delegate($delegate)
    {
        $delegatePriority = 10;

        $delegate->getPriority()
            ->willReturn($delegatePriority);

        $this->getPriority()
            ->shouldReturn($delegatePriority);
    }

    public function its_supports_what_its_delegate_supports($delegate, ResourceInterface $resource)
    {
        $generation = 'generation';
        $data = ['data'];

        $delegate->supports($resource, $generation, $data)
            ->willReturn(true);

        $this->supports($resource, $generation, $data)
            ->shouldReturn(true);
    }

    public function it_does_not_support_what_its_delegate_does_not_support($delegate, ResourceInterface $resource)
    {
        $generation = 'generation';
        $data = ['data'];

        $delegate->supports($resource, $generation, $data)
            ->willReturn(false);

        $this->supports($resource, $generation, $data)
            ->shouldReturn(false);
    }

    public function it_generates_content_via_its_delegate($delegate, ResourceInterface $resource)
    {
        $data = ['data'];
        $this->generate($resource, $data);

        $delegate->generate($resource, $data)
            ->shouldHaveBeenCalled();
    }

    public function it_stages_files_created_as_a_result_of_delegation_of_generation(
        $delegate,
        ResourceInterface $resource,
        $filesystem,
        $repository
    ) {
        $srcFilePath = '/foo/bar.php';
        $specFilePath = '/foo/barspec.php';

        $resource->getSrcFilename()
            ->willReturn($srcFilePath);

        $resource->getSpecFilename()
            ->willReturn($specFilePath);

        $filesystem->pathExists($srcFilePath)
            ->willReturn(false);

        $filesystem->pathExists($specFilePath)
            ->willReturn(true);

        $delegate->generate(Argument::cetera())->will(function () use ($filesystem, $srcFilePath) {
            $filesystem->pathExists($srcFilePath)
                ->willReturn(true);
        });

        $this->generate($resource, []);

        $repository->stage($srcFilePath)
            ->shouldHaveBeenCalled();

        $repository->stage($specFilePath)
            ->shouldNotHaveBeenCalled();
    }
}