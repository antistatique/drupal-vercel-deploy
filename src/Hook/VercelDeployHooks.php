<?php

namespace Drupal\vercel_deploy\Hook;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\DependencyInjection\ClassResolverInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Hook\Attribute\Hook;
use Drupal\Core\Plugin\DefaultPluginManager;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\vercel_deploy\ToolbarHandler;

/**
 * Hook implementations for the Vercel Deploy module.
 */
class VercelDeployHooks {

  /**
   * Constructs a new VercelDeployHooks object.
   *
   * @param \Drupal\Core\DependencyInjection\ClassResolverInterface $classResolver
   *   The class resolver.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $moduleHandler
   *   The module handler.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $configFactory
   *   The config factory.
   * @param \Drupal\Core\Plugin\DefaultPluginManager|null $filterManager
   *   The filter plugin manager, only available when the Filter module is
   *   enabled.
   */
  public function __construct(
    protected ClassResolverInterface $classResolver,
    protected ModuleHandlerInterface $moduleHandler,
    protected ConfigFactoryInterface $configFactory,
    protected ?DefaultPluginManager $filterManager = NULL,
  ) {
  }

  /**
   * Implements hook_toolbar().
   */
  #[Hook('toolbar')]
  public function toolbar(): array {
    return $this->classResolver
      ->getInstanceFromDefinition(ToolbarHandler::class)
      ->toolbar();
  }

  /**
   * Implements hook_help().
   */
  #[Hook('help')]
  public function help(string $route_name, RouteMatchInterface $route_match) {
    if ($route_name !== 'help.page.vercel_deploy') {
      return NULL;
    }

    $text = file_get_contents(dirname(__DIR__, 2) . '/README.md');
    if (!$this->filterManager || !$this->moduleHandler->moduleExists('markdown')) {
      return '<pre>' . $text . '</pre>';
    }

    // Use the Markdown filter to render the README.
    $settings = $this->configFactory
      ->get('markdown.settings')
      ->getRawData();
    $filter = $this->filterManager->createInstance('markdown', ['settings' => $settings]);

    return $filter->process($text, 'en');
  }

}
