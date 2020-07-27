<?php

namespace Drupal\vercel_deploy;

use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Toolbar integration handler.
 */
class ToolbarHandler implements ContainerInjectionInterface {
  use StringTranslationTrait;

  /**
   * The current user.
   *
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected $account;

  /**
   * ToolbarHandler constructor.
   *
   * @param \Drupal\Core\Session\AccountProxyInterface $account
   *   The current user.
   */
  public function __construct(AccountProxyInterface $account) {
    $this->account = $account;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('current_user')
    );
  }

  /**
   * Hook bridge.
   *
   * @return array
   *   The devel toolbar items render array.
   *
   * @see hook_toolbar()
   */
  public function toolbar() {

    $items['vercel_deploy'] = [
      '#cache' => [
        'contexts' => ['user.permissions'],
      ],
    ];

    if ($this->account->hasPermission('see vercel deploy-hook button toolbar') && $this->account->hasPermission('access vercel deploy-hook')) {
      $items['vercel_deploy'] += [
        '#type' => 'toolbar_item',
        '#weight' => 999,
        'tab' => [
          '#type' => 'link',
          '#title' => $this->t('Vercel Deploy'),
          '#url' => Url::fromRoute('vercel_deploy.deploy_hooks'),
          '#attributes' => [
            'title' => $this->t('Vercel Deploy'),
            'class' => ['toolbar-icon', 'toolbar-icon-vercel-deploy'],
          ],
        ],
        '#attached' => [
          'library' => 'vercel_deploy/vercel-deploy-toolbar',
        ],
      ];
    }

    return $items;
  }

}
