<?php

namespace PhpSpecExtension\Git;

use PhpSpec\CodeGenerator\Generator\GeneratorInterface;
use PhpSpec\Extension\ExtensionInterface;
use PhpSpec\ServiceContainer;
use PhpSpec\Util\Filesystem;

use PhpSpecExtension\Git\Generator\GitAddingGenerator;
use PhpSpecExtension\Git\Generator\GitRepository;

class Extension implements ExtensionInterface
{
    /**
     * @param ServiceContainer $container
     */
    public function load(ServiceContainer $container)
    {
        $container->setShared('phpspec_extension.git.repository', function () {
            return GitRepository::fromCurrentWorkingDirectory();
        });

        // N.B. - this is not ideal, but there appears to be no other way to achieve this currently.
        $classGenerator = $container->get('code_generator.generators.class');
        $specGenerator = $container->get('code_generator.generators.specification');
        $interfaceGenerator = $container->get('code_generator.generators.interface');

        $this->decorateGenerator($container, 'code_generator.generators.class', $classGenerator);
        $this->decorateGenerator($container, 'code_generator.generators.specification', $specGenerator);
        $this->decorateGenerator($container, 'code_generator.generators.interface', $interfaceGenerator);
    }

    /**
     * @param ServiceContainer $container
     * @param GeneratorInterface $generator
     */
    private function decorateGenerator(ServiceContainer $container, $key, GeneratorInterface $generator)
    {
        $container->set($key, function (ServiceContainer $c) use ($generator) {
            return new GitAddingGenerator(
                $generator,
                $c->get('phpspec_extension.git.repository'),
                new Filesystem()
            );
        });
    }
}