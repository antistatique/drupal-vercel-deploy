<?php

namespace Drupal\vercel_deploy\Form;

use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Url;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\StringTranslation\TranslatableMarkup;

/**
 * Provides the Deploy Hooks confirmation form.
 *
 * @internal
 */
class DeployHooksForm extends ConfirmFormBase {

  /**
   * A config object for the Cardis REST reCAPTCHA configuration.
   *
   * @var \Drupal\Core\Config\Config
   */
  protected $config;

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'vercel_deploy_deploy_hooks';
  }

  /**
   * Constructs a new DeployHooksForm object.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config factory.
   */
  public function __construct(ConfigFactoryInterface $config_factory) {
    $this->config = $config_factory->get('vercel_deploy.settings');
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getQuestion() {
    return $this->t('Are you sure you want to initialize a new Vercel Deployment?');
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelUrl() {
    return new Url('vercel_deploy');
  }

  /**
   * {@inheritdoc}
   */
  public function getConfirmText() {
    return $this->t('Initialize a Vercel Deployment');
  }

  /**
   * {@inheritdoc}
   */
  public function getDescription() {
    return implode(' ', [
      $this->t('Trigger deployments and re-run the Build Step.'),
      $this->t('Triggering a Deploy Hook will not rebuild an existing deployment. Instead, it will create a new deployment using the latest source code available on the specified branch.'),
      $this->t('If you send multiple requests to deploy the same version of your project, previous deployments for the same Deploy Hook will be canceled to reduce build times.'),
    ]);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $urls = $this->config->get('deploy_hooks_urls');

    $batch = [
      'title' => $this->t('Bulk initializing Vercel deployment'),
      'operations' => [
        ['Drupal\vercel_deploy\Form\DeployHooksForm::batchStart', []],
      ],
      'finished' => 'Drupal\vercel_deploy\Form\DeployHooksForm::batchFinished',
    ];

    foreach ($urls as $id => $url) {
      if (!empty($url)) {
        $batch['operations'][] = [
          'Drupal\vercel_deploy\Form\DeployHooksForm::batchProcess',
          [$id, $url],
        ];
      }
    }

    batch_set($batch);
  }

  /**
   * Batch callback; initialize the number of deployed Vercel.
   */
  public static function batchStart(&$context) {
    $context['results']['deploy'] = [];
    $context['results']['error'] = [];
  }

  /**
   * Batch 'operation' callback.
   *
   * @param int $id
   *   The id.
   * @param string $url
   *   The batch Vercel URL to POST as deploy-hook.
   * @param array|\DrushBatchContext $context
   *   The sandbox context.
   */
  public static function batchProcess(int $id, string $url, &$context) {
    sleep(1);
    $context['sandbox']['current'] = $id;

    if (empty($context['sandbox'])) {
      $context['sandbox']['count'] = 0;
    }

    $context['sandbox']['count'] += 1;
    $context['message'] = new TranslatableMarkup('Initialize a new Vercel deployment for %url.', ['%url' => $url]);

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);

    // Receive server response.
    $response = curl_exec($curl);
    curl_close($curl);

    $decoded = json_decode($response, TRUE);

    if ($decoded['job']['state'] === 'PENDING') {
      $context['results']['deploy'][] = $url;
    }
    else {
      $context['results']['error'][] = $url;
    }
  }

  /**
   * Finished callback for import batches.
   *
   * @param bool $success
   *   A boolean indicating whether the batch has completed successfully.
   * @param array $results
   *   The value set in $context['results'] by callback_batch_operation().
   * @param array $operations
   *   If $success is FALSE, contains the operations that remained unprocessed.
   */
  public static function batchFinished(bool $success, array $results, array $operations): void {
    /** @var \Drupal\Core\Messenger\MessengerInterface $messenger */
    $messenger = \Drupal::service('messenger');

    if (!$success) {
      $error_operation = reset($operations);
      $messenger->addMessage(new TranslatableMarkup('An error occurred while processing @operation with arguments : @args', [
        '@operation' => $error_operation[0],
        '@args' => print_r($error_operation[0]),
      ]));
      return;
    }

    if (count($results['deploy']) > 0) {
      $messenger->addMessage(\Drupal::translation()
        ->formatPlural(
          count($results['deploy']), 'Initialized 1 Vercel deployment (@urls).',
          'Initialized @count Vercel deployments (@urls).',
          ['@urls' => implode(', ', $results['deploy'])]
        ));
    }

    if (count($results['error']) > 0) {
      $messenger->addError(\Drupal::translation()
        ->formatPlural(
          count($results['error']), 'Error during initialization of 1 Vercel deployment (@urls).',
          'Error during initialization of @count Vercel deployments (@urls).',
          ['@urls' => implode(', ', $results['error'])]
        ));
    }

    if (count($results['deploy']) === 0 && count($results['error']) === 0) {
      $messenger->addMessage(new TranslatableMarkup('No new Vercel deployment initialized.'));
    }
  }

}
