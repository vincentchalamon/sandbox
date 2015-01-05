Feature: Show `Exemple` article

  Scenario: Access to `Exemple` through main menu link
    Given I am on the homepage
    And should see "Exemple" in the "nav ul li:not(.active) a" element
    Then I follow "Exemple"
    And I should be on "/exemple"
    And should see "This blog post shows a few different types of content" in the ".container .row .col-sm-8 article p" element
    And should see "Exemple" in the "nav ul li.active a" element
    But I should not see "Accueil" in the "nav ul li.active a" element
