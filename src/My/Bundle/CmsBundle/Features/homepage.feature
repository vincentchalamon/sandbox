Feature: Show homepage

  Scenario: Access to homepage and I should not see `Contact` neither `Exemple` articles
    Given I am on the homepage
    Then I should see 1 "article header h1" elements
    And should see "Accueil" in the ".container .row .col-sm-8 article header h1" element
    And should see 2 "nav ul li" elements
    But I should see 1 "nav ul li.active" elements
    And should see "Accueil" in the "nav ul li.active a" element
    But I should not see "Contact" in the ".container .row .col-sm-8 article header h1" element
    And should not see "Exemple" in the ".container .row .col-sm-8 article header h1" element
