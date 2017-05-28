<?php

namespace Drupal\expert_questions\Form;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\expert_questions\Entity\ExpertQuestion;
use Drupal\expert_questions\Entity\ExpertQuestionInterface;
use Drupal\user\UserInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityTypeManager;

/**
 * Class Answer.
 *
 * @package Drupal\expert_questions\Form
 */
class Answer extends FormBase {

  /**
   * Drupal\Core\Entity\EntityTypeManager definition.
   *
   * @var \Drupal\Core\Entity\EntityTypeManager
   */
  protected $entityTypeManager;

  /**
   * @var AccountInterface $account
   */
  protected $currentUser;
  /**
   * Constructs a new Ask object.
   */
  public function __construct(
    EntityTypeManager $entity_type_manager,
    AccountInterface $currentUser
  ) {
    $this->entityTypeManager = $entity_type_manager;
    $this->currentUser = $currentUser;
  }

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager'),
      $container->get('current_user')
    );
  }


  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'answer';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, ExpertQuestionInterface $expert_question = NULL) {
    if ($expert_question instanceof ExpertQuestionInterface) {
      $form_state->addBuildInfo('question', $expert_question);
    }
    $form['answer_wrap'] = [
      '#type' => 'container',
      '#attributes' => [
        'class' => ['row', 'answer-wrap'],
      ],
    ];
    $form['answer_wrap']['answer'] = [
      '#type' => 'text_format',//'text_format',
      '#title' => $this->t('Answer'),
      '#description' => $this->t('You answer'),
      '#format' => 'basic_html',
      '#required' => TRUE,
      '#prefix' => '<div class="col-md-12">',
      '#suffix' => '</div>',
    ];
    if ($expert_question->isAnswered()) {
      $answer = reset($expert_question->getAnswer());
      $form['answer_wrap']['answer']['#default_value'] = $answer['value'];
    }

    $form['actions'] = ['#type' => 'actions'];
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save Answer'),
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
    // Display result.
    /**
     * @var $expertQuestion ExpertQuestionInterface
     */
    $expertQuestion = $form_state->getBuildInfo()['question'];
    $expertQuestion->set('answer', $form_state->getValue('answer'));
    $status = $expertQuestion->save();
    dpm($status,'status'); //todo: delete all dpm() function

    //$expertQuestion->save();
   /* if ($status) {
      drupal_set_message($this->t('Question send'));
    }*/
    $form_state->setRedirect('entity.expert_question.canonical', [
      'expert_question' => $expertQuestion->id(),
    ]);
  }

  /**
   * Checks access for a specific request.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   Run access checks for this account.
   */
  public function access(ExpertQuestionInterface $expert_question = NULL) {

    if ($expert_question instanceof ExpertQuestionInterface) {

      if ($this->currentUser->hasPermission('edit expert question entities')) {
        return AccessResult::allowed();
      }
      return AccessResult::allowedIf($this->currentUser->id() == $expert_question->getExpert()->id()
        && $this->currentUser->hasPermission('answer the questions addressed'));

    }else {
      return AccessResult::forbidden();
    }
  }


}
