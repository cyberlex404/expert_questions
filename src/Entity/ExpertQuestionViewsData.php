<?php

namespace Drupal\expert_questions\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for Expert question entities.
 */
class ExpertQuestionViewsData extends EntityViewsData {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    // Additional information for Views integration, such as table joins, can be
    // put here.

    return $data;
  }

}
