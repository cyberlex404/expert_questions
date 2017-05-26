<?php

/**
 * @file
 * Contains expert_question.page.inc.
 *
 * Page callback for Expert question entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Expert question templates.
 *
 * Default template: expert_question.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_expert_question(array &$variables) {
  // Fetch ExpertQuestion Entity Object.
  $expert_question = $variables['elements']['#expert_question'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
