<?php

namespace Drupal\Tests\vercel_deploy\Functional;

use Drupal\Tests\BrowserTestBase;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;

/**
 * Tests the access to the Vercel Deploy administration pages.
 *
 * @group vercel_deploy
 * @group vercel_deploy_functional
 */
#[Group('vercel_deploy')]
#[Group('vercel_deploy_functional')]
#[RunTestsInSeparateProcesses]
class AdminAccessTest extends BrowserTestBase {

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  /**
   * {@inheritdoc}
   */
  protected static $modules = ['vercel_deploy'];

  /**
   * Tests that the overview page is reachable with a single permission.
   */
  public function testOverviewAccess(): void {
    $this->drupalLogin($this->drupalCreateUser(['access vercel deploy-hook', 'access administration pages']));
    $this->drupalGet('admin/config/development/vercel-deploy');
    $this->assertSession()->statusCodeEquals(200);

    $this->drupalLogin($this->drupalCreateUser(['administer vercel deploy', 'access administration pages']));
    $this->drupalGet('admin/config/development/vercel-deploy');
    $this->assertSession()->statusCodeEquals(200);
  }

  /**
   * Tests that the overview page is denied without any Vercel permission.
   */
  public function testOverviewDenied(): void {
    $this->drupalLogin($this->drupalCreateUser(['access administration pages']));
    $this->drupalGet('admin/config/development/vercel-deploy');
    $this->assertSession()->statusCodeEquals(403);
  }

}
