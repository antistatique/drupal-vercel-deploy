<?php

namespace Drupal\Tests\vercel_deploy\Functional;

use Drupal\Tests\BrowserTestBase;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;

/**
 * Tests the Vercel Deploy settings form.
 *
 * @group vercel_deploy
 * @group vercel_deploy_functional
 */
#[Group('vercel_deploy')]
#[Group('vercel_deploy_functional')]
#[RunTestsInSeparateProcesses]
class SettingsFormTest extends BrowserTestBase {

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  /**
   * {@inheritdoc}
   */
  protected static $modules = ['vercel_deploy'];

  /**
   * Tests that valid URLs are saved, trimmed and blank lines are dropped.
   */
  public function testSaveUrls(): void {
    $this->drupalLogin($this->drupalCreateUser(['administer vercel deploy']));
    $this->drupalGet('admin/config/development/vercel-deploy/settings');

    $this->submitForm([
      'vercel_deploy_urls[urls]' => "https://api.vercel.com/v1/integrations/deploy/a\r\n\r\n  https://api.vercel.com/v1/integrations/deploy/b  \r\n",
    ], 'Save configuration');

    $this->assertSession()->pageTextContains('The configuration options have been saved.');
    $this->assertSame([
      'https://api.vercel.com/v1/integrations/deploy/a',
      'https://api.vercel.com/v1/integrations/deploy/b',
    ], $this->config('vercel_deploy.settings')->get('deploy_hooks_urls'));
  }

  /**
   * Tests that non-HTTPS URLs are rejected.
   */
  public function testInvalidUrl(): void {
    $this->drupalLogin($this->drupalCreateUser(['administer vercel deploy']));
    $this->drupalGet('admin/config/development/vercel-deploy/settings');

    $this->submitForm(['vercel_deploy_urls[urls]' => 'http://example.com/hook'], 'Save configuration');

    $this->assertSession()->pageTextContains('is not a valid HTTPS URL.');
    $this->assertSame([], $this->config('vercel_deploy.settings')->get('deploy_hooks_urls'));
  }

}
