<?php

namespace Drupal\expert_questions;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Expert question entity.
 *
 * @see \Drupal\expert_questions\Entity\ExpertQuestion.
 */
class ExpertQuestionAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\expert_questions\Entity\ExpertQuestionInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished expert question entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published expert question entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit expert question entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete expert question entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add expert question entities');
  }

}
