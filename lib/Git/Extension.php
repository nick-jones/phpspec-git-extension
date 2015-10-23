<?php

namespace PhpSpecExtension\Git;

use GitElephant\Repository;

use PhpSpec\Extension\ExtensionInterface;
use PhpSpec\ServiceContainer;
use PhpSpec\Util\Filesystem;

use PhpSpecExtension\Git\Generator\GitAddingGenerator;

class Extension implements ExtensionInterface
{
    /**
     * @param ServiceContainer $container
     */
    public function load(ServiceContainer $container)
    {
        // N.B. - this is not ideal, but there appears to be no other way to achieve this currently.
        $classGenerator = $container->get('code_generator.generators.class');
        $specGenerator = $container->get('code_generator.generators.specification');

        $container->setShared('phpspec_extension.git.repository', function () {
            return Repository::open(realpath('.'));
        });

        $container->set('code_generator.generators.class', function (ServiceContainer $c) use ($classGenerator) {
            return new GitAddingGenerator(
                $classGenerator,
                $c->get('phpspec_extension.git.repository'),
                new Filesystem()
            );
        });

        $container->set('code_generator.generators.specification', function (ServiceContainer $c) use ($specGenerator) {
            return new GitAddingGenerator(
                $specGenerator,
                $c->get('phpspec_extension.git.repository'),
                new Filesystem()
            );
        });
    }
}