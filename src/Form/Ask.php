<?php

namespace Drupal\expert_questions\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\expert_questions\Entity\ExpertQuestion;
use Drupal\expert_questions\Event\QuestionNew;
use Drupal\user\UserInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityTypeManager;
use Drupal\Component\EventDispatcher\ContainerAwareEventDispatcher;
use Drupal\Component\Utility\Xss;

/**
 * Class Ask.
 *
 * @package Drupal\expert_questions\Form
 */
class Ask extends FormBase {

  /**
   * Drupal\Core\Entity\EntityTypeManager definition.
   *
   * @var \Drupal\Core\Entity\EntityTypeManager
   */
  protected $entityTypeManager;

  /**
   * @var \Drupal\Core\Config\ImmutableConfig
   */
  protected $questionsConfig;

  /**
   * Drupal\Component\EventDispatcher\ContainerAwareEventDispatcher definition.
   *
   * @var \Drupal\Component\EventDispatcher\ContainerAwareEventDispatcher
   */
  protected $eventDispatcher;
  /**
   * Constructs a new Ask object.
   */
  public function __construct(
    EntityTypeManager $entity_type_manager,
    ContainerAwareEventDispatcher $event_dispatcher
  ) {
    $this->entityTypeManager = $entity_type_manager;
    $this->questionsConfig = $this->config('expert_questions.config');
    $this->eventDispatcher = $event_dispatcher;
  }

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager'),
      $container->get('event_dispatcher')
    );
  }


  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'ask';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, UserInterface $expert = NULL) {
    if ($expert instanceof UserInterface) {
      $form_state->addBuildInfo('expert', $expert);
    }

    $askRedirect = $this->questionsConfig->get('ask_redirect');
    $form['question_wrap'] = [
      '#type' => 'container',
      '#attributes' => [
        'class' => ['row', 'question-wrap'],
      ],
    ];
    $form['question_wrap']['question'] = [
      '#type' => 'textarea',//'text_format',
      '#title' => $this->t('Question'),
      '#description' => $this->t('You question'),
      '#required' => TRUE,
      '#prefix' => '<div class="col-md-12">',
      '#suffix' => '</div>',
    ];
    $form['persinfo'] = [
      '#type' => 'container',
      '#attributes' => [
        'class' => ['row'],
      ],
    ];
    $form['persinfo']['name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('You name'),
      '#size' => 60,
      '#maxlength' => 250,
      '#required' => TRUE,
      '#prefix' => '<div class="col-md-6">',
      '#suffix' => '</div>',
    ];
    $form['persinfo']['author_email'] = [
      '#type' => 'email',
      '#title' => $this->t('You E-mail'),
      '#description' => $this->t('You E-mail for notify'),
      '#required' => TRUE,
      '#prefix' => '<div class="col-md-6">',
      '#suffix' => '</div>',
    ];
    $form['actions'] = ['#type' => 'actions'];
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    ];

    return $form;
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
    $askRedirect = $this->questionsConfig->get('ask_redirect');
    $askNotify = $this->questionsConfig->get('ask_notify');

    /**
     * @var $expert UserInterface
     */
    $expert = $form_state->getBuildInfo()['expert'];
    $expertQuestion = ExpertQuestion::create([
      'name' => 'Question',
      'question' => Xss::filter($form_state->getValue('question')),
      'expert' => $expert->id(),
      'author_name' => Xss::filter($form_state->getValue('name')),
      'author_email' => $form_state->getValue('author_email'),
    ]);
    $status = $expertQuestion->save();
    $expertQuestion->setName($this->t('Question') . ' #'. $expertQuestion->id());
    $expertQuestion->save();
    if ($status) {

      $event = new QuestionNew($expertQuestion);
      $this->eventDispatcher->dispatch(QuestionNew::EVENT_NAME, $event);

      drupal_set_message($this->t($askNotify['value']));
    }

    $form_state->setRedirect($askRedirect['route_name'], $askRedirect['route_parameters']);
  }

}
