Feature: Generated interfaces & classes can become automatically managed by git
  As a developer
  I want my generated interfaces & classes to become managed by git
  So that I do not have to manually stage them

  Scenario: Generating a class from a specification with the git extension enabled
    Given the config file contains:
    """
    extensions:
      - PhpSpecExtension\Git\Extension
    """
    And I have initialised a git repository
    And I have started describing the "PhpSpecExtensionTest/Git/Example1" class
    When I run phpspec and answer "y" when asked if I want to generate the code
    Then file "src/PhpSpecExtensionTest/Git/Example1.php" should be staged for commit

  Scenario: Generating a specification with the git extension enabled
    Given the config file contains:
    """
    extensions:
      - PhpSpecExtension\Git\Extension
    """
    And I have initialised a git repository
    When I have started describing the "PhpSpecExtensionTest/Git/Example2" class
    Then file "spec/PhpSpecExtensionTest/Git/Example2Spec.php" should be staged for commit

  Scenario: Generating a collaborator with the git extension enabled
    Given the config file contains:
    """
    extensions:
      - PhpSpecExtension\Git\Extension
    """
    And I have initialised a git repository
    And the class file "src/PhpSpecExtensionTest/Git/Example3.php" contains:
    """
    <?php

    namespace PhpSpecExtensionTest\Git;

    class Example3
    {
    }
    """
    And the spec file "spec/PhpSpecExtensionTest/Git/Example3Spec.php" contains:
    """
    <?php

    namespace spec\PhpSpecExtensionTest\Git;

    use PhpSpec\ObjectBehavior;
    use Prophecy\Argument;
    use PhpSpecExtensionTest\Git\Collaborator;

    class Example3Spec extends ObjectBehavior
    {
        function it_interacts_with_a_collaborator(Collaborator $collaborator)
        {
            $this->foo($collaborator);
        }
    }

    """
    When I run phpspec and answer "y" when asked if I want to generate the code
    Then file "src/PhpSpecExtensionTest/Git/Collaborator.php" should be staged for commit