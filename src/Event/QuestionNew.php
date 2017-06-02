<?php

namespace Drupal\expert_questions\Event;

use Drupal\expert_questions\Entity\ExpertQuestionInterface;
use Symfony\Component\EventDispatcher\Event;

/**
 * Event that is fired when a added new ExpertQuestion.
 *
 */
class QuestionNew extends Event {

  const EVENT_NAME = 'expert_questions_new_question';

  /**
   * @var ExpertQuestionInterface
   */
  public $question;

  function __construct(ExpertQuestionInterface $question) {
    $this->question = $question;
  }
}