<?php

namespace Drupal\Tests\vercel_deploy\Functional;

use Drupal\Tests\BrowserTestBase;

/**
 * Tests vercel_deploy toolbar functionality.
 *
 * @group vercel_deploy
 * @group vercel_deploy_functional
 */
class ToolbarTest extends BrowserTestBase {

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  /**
   * {@inheritdoc}
   */
  protected static $modules = ['toolbar', 'vercel_deploy'];

  /**
   * Tests that the toolbar button is visible.
   */
  public function testButtonVisible(): void {
    // A user with Vercel Deploy permissions should see the button.
    $developer = $this->drupalCreateUser([
      'access toolbar',
      'access vercel deploy-hook',
      'see vercel deploy-hook button toolbar',
    ]);
    $this->drupalLogin($developer);

    $this->drupalGet('');
    $this->assertSession()->elementContains('css', '#toolbar-administration', '/admin/config/development/vercel-deploy');
  }

  /**
   * Tests that the toolbar button works.
   */
  public function testButtonVisit(): void {
    $developer = $this->drupalCreateUser([
      'access toolbar',
      'access vercel deploy-hook',
      'see vercel deploy-hook button toolbar',
    ]);
    $this->drupalLogin($developer);
    $this->drupalGet('');

    // Click the button.
    $this->clickLink('Vercel Deploy');
    $this->assertSession()->pageTextContains('Are you sure you want to initialize a new Vercel Deployment?');
  }

  /**
   * Tests that the toolbar button is not visible without proper permissions.
   */
  public function testButtonNotVisible(): void {
    // First, a user without Vercel Deploy permission should not see the button.
    $editor = $this->drupalCreateUser([
      'access toolbar',
    ]);
    $this->drupalLogin($editor);
    $this->drupalGet('');
    $this->assertSession()->pageTextNotContains('Vercel Deploy');
    $this->assertSession()->elementNotContains('css', '#toolbar-administration', '/admin/config/development/vercel-deploy');

    // Then, a user with only a subset of Vercel Deploy permission
    // should not see the button.
    $editor = $this->drupalCreateUser([
      'access toolbar',
      'see vercel deploy-hook button toolbar',
    ]);
    $this->drupalLogin($editor);
    $this->drupalGet('');
    $this->assertSession()->elementNotContains('css', '#toolbar-administration', '/admin/config/development/vercel-deploy');

    // Then, a user with only a subset of Vercel Deploy permission
    // should not see the button.
    $editor = $this->drupalCreateUser([
      'access toolbar',
      'access vercel deploy-hook',
    ]);
    $this->drupalLogin($editor);
    $this->drupalGet('');
    $this->assertSession()->elementNotContains('css', '#toolbar-administration', '/admin/config/development/vercel-deploy');
  }

}
