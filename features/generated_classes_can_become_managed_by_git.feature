Feature: Generated classes can become automatically managed by git
  As a developer
  I want my generated classes to become managed by git
  So that I do not have to manually add them

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