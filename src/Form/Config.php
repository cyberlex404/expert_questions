<?php

namespace Drupal\expert_questions\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityTypeManager;

/**
 * Class Config.
 *
 * @package Drupal\expert_questions\Form
 */
class Config extends ConfigFormBase {

  /**
   * Drupal\Core\Entity\EntityTypeManager definition.
   *
   * @var \Drupal\Core\Entity\EntityTypeManager
   */
  protected $entityTypeManager;
  /**
   * Constructs a new Config object.
   */
  public function __construct(
    ConfigFactoryInterface $config_factory,
      EntityTypeManager $entity_type_manager
    ) {
    parent::__construct($config_factory);
        $this->entityTypeManager = $entity_type_manager;
  }

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory'),
            $container->get('entity_type.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'expert_questions.config',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'config';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('expert_questions.config');

    //dpm($config->getRawData());

    $form['ask'] = [
      '#type' => 'details',
      '#title' =>$this->t('Ask form config'),
    ];
    $askRedirect = $config->get('ask_redirect');
    $url = Url::fromRoute($askRedirect['route_name'], $askRedirect['route_parameters'], []);
    $form['ask']['redirect'] = [
      '#type' => 'path',
      '#title' => $this->t('Ask redirect'),
      '#size' => 30,
      '#default_value' => ($url instanceof Url) ? $url->toString() : '',
    ];

    $notify = $config->get('ask_notify');
    $form['ask']['notify'] = [
      '#type' => 'text_format',
      '#title' => $this->t('Notify text'),
      '#default_value' => $notify['value'],
      '#format' => $notify['format'],
    ];
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);


    $this->config('expert_questions.config')
      ->set('ask_redirect', $form_state->getValue('redirect'))
      ->set('ask_notify', $form_state->getValue('notify'))
      ->save();
  }

}
