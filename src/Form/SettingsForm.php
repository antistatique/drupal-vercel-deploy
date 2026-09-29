<?php

namespace Drupal\vercel_deploy\Form;

use Drupal\Component\Utility\UrlHelper;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure Vercel Deploy settings form.
 *
 * @internal
 */
final class SettingsForm extends ConfigFormBase {

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
   * Splits the textarea value into a list of trimmed non-empty URLs.
   *
   * @param string $value
   *   The raw textarea value.
   *
   * @return string[]
   *   The URLs.
   */
  protected function parseUrls(string $value): array {
    $urls = preg_split('/\R/', $value) ?: [];
    return array_values(array_filter(array_map('trim', $urls), 'strlen'));
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    foreach ($this->parseUrls($form_state->getValue(['vercel_deploy_urls', 'urls'])) as $url) {
      if (!UrlHelper::isValid($url, TRUE) || !str_starts_with($url, 'https://')) {
        $form_state->setErrorByName('vercel_deploy_urls][urls', $this->t('%url is not a valid HTTPS URL.', ['%url' => $url]));
      }
    }

    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('vercel_deploy.settings')
      ->set('deploy_hooks_urls', $this->parseUrls($form_state->getValue(['vercel_deploy_urls', 'urls'])))
      ->save();

    parent::submitForm($form, $form_state);
  }

}
