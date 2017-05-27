<?php

namespace Drupal\expert_questions\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Expert question entities.
 *
 * @ingroup expert_questions
 */
interface ExpertQuestionInterface extends  ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Expert question name.
   *
   * @return string
   *   Name of the Expert question.
   */
  public function getName();

  /**
   * Sets the Expert question name.
   *
   * @param string $name
   *   The Expert question name.
   *
   * @return \Drupal\expert_questions\Entity\ExpertQuestionInterface
   *   The called Expert question entity.
   */
  public function setName($name);

  /**
   * Gets the Expert question creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Expert question.
   */
  public function getCreatedTime();

  /**
   * Sets the Expert question creation timestamp.
   *
   * @param int $timestamp
   *   The Expert question creation timestamp.
   *
   * @return \Drupal\expert_questions\Entity\ExpertQuestionInterface
   *   The called Expert question entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Expert question published status indicator.
   *
   * Unpublished Expert question are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Expert question is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Expert question.
   *
   * @param bool $published
   *   TRUE to set this Expert question to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\expert_questions\Entity\ExpertQuestionInterface
   *   The called Expert question entity.
   */
  public function setPublished($published);

  /**
   * @return bool
   *   TRUE if the answer exists
   */
  public function isAnswered();

  /**
   * @return mixed
   */
  public function getAnswer();

}
