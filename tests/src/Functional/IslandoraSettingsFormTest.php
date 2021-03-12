<?php

namespace Drupal\Tests\islandora\Functional;

/**
 * Tests Islandora Settings Form.
 *
 * @package Drupal\Tests\islandora\Functional
 * @group islandora
 * @coversDefaultClass \Drupal\islandora\Form\IslandoraSettingsForm
 */
class IslandoraSettingsFormTest extends IslandoraFunctionalTestBase {

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    parent::setUp();

    // Create a test user.
    $account = $this->drupalCreateUser([
      'bypass node access',
      'administer site configuration',
      'view media',
      'create media',
      'update media',
    ]);
    $this->drupalLogin($account);
  }

  /**
   * Test form validation for JWT expiry.
   */
  public function testJwtExpiry() {
    $this->drupalGet('/admin/config/islandora/core');
    $this->assertSession()->statusCodeEquals(200);
    $this->assertSession()->pageTextContains("JWT Expiry");
    $this->assertSession()->fieldValueEquals('edit-jwt-expiry', '+2 hour');
    // Blank is not allowed.
    $this->drupalPostForm('/admin/config/islandora/core', ['edit-jwt-expiry' => ""], $this->t('Save configuration'));
    $this->assertSession()->pageTextContainsOnce('"" is not a valid time or interval expression.');
    // Negative is not allowed.
    $this->drupalPostForm('/admin/config/islandora/core', ['edit-jwt-expiry' => "-2 hours"], $this->t('Save configuration'));
    $this->assertSession()->pageTextContainsOnce('Time or interval expression cannot be negative');
    // Must include an integer value.
    $this->drupalPostForm('/admin/config/islandora/core', ['edit-jwt-expiry' => "last hour"], $this->t('Save configuration'));
    $this->assertSession()->pageTextContainsOnce('No numeric interval specified, for example "1 day"');
    // Must have an accepted interval.
    $this->drupalPostForm('/admin/config/islandora/core', ['edit-jwt-expiry' => "1 fortnight"], $this->t('Save configuration'));
    $this->assertSession()->pageTextContainsOnce('No time interval found, please include one of');
    // Test a valid setting.
    $this->drupalPostForm('/admin/config/islandora/core', ['edit-jwt-expiry' => "2 weeks"], $this->t('Save configuration'));
    $this->assertSession()->pageTextContainsOnce('The configuration options have been saved.');

  }

}
