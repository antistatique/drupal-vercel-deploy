<?php

namespace Drupal\vercel_deploy\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Component\Utility\Xss;

/**
 * Configure Vercel Deploy settings form.
 *
 * @internal
 */
class SettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'vercel_deploy_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['vercel_deploy.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('vercel_deploy.settings');
    $form['#tree'] = TRUE;

    $form['vercel_deploy_urls'] = [
      '#title' => $this->t('Vercel Deploy Hooks'),
      '#type' => 'fieldset',
      '#markup' => '<div class="description">' . $this->t('Add Vercel URLs that accept HTTP POST requests in order to trigger deployments and re-run the Build Step.') . '</div>',
    ];

    $urls = implode(PHP_EOL, $config->get('deploy_hooks_urls'));
    $form['vercel_deploy_urls']['urls'] = [
      '#type' => 'textarea',
      '#title' => $this->t('URLs'),
      '#default_value' => $urls,
      '#description' => $this->t('Please specify one Vercel URL per line.'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $values = $form_state->getValues();

    $urls = explode(PHP_EOL, $values['vercel_deploy_urls']['urls']);
    $deploy_hooks_urls = array_map(static function ($item) {
      return trim(Xss::filter($item, []));
    }, $urls);

    $config = $this->config('vercel_deploy.settings');
    $config->set('deploy_hooks_urls', $deploy_hooks_urls)->save();

    parent::submitForm($form, $form_state);
  }

}
