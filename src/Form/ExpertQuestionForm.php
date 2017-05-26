<?php

namespace Drupal\expert_questions\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for Expert question edit forms.
 *
 * @ingroup expert_questions
 */
class ExpertQuestionForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    /* @var $entity \Drupal\expert_questions\Entity\ExpertQuestion */
    $form = parent::buildForm($form, $form_state);

    $entity = $this->entity;

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $entity = &$this->entity;

    $status = parent::save($form, $form_state);

    switch ($status) {
      case SAVED_NEW:
        drupal_set_message($this->t('Created the %label Expert question.', [
          '%label' => $entity->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label Expert question.', [
          '%label' => $entity->label(),
        ]));
    }
    $form_state->setRedirect('entity.expert_question.canonical', ['expert_question' => $entity->id()]);
  }

}
