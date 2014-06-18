<?php

/**
 * @file
 * Contains \Drupal\rules\Plugin\Condition\DataIsEmpty.
 */

namespace Drupal\rules\Plugin\Condition;

use Drupal\Core\TypedData\ComplexDataInterface;
use Drupal\Core\TypedData\TypedDataManager;
use Drupal\rules\Context\ContextDefinition;
use Drupal\rules\Engine\RulesConditionBase;

/**
 * Provides a 'Data value is empty' condition.
 *
 *  @Condition(
 *   id = "rules_data_is_empty",
 *   label = @Translation("Data value is empty")
 * )
 *
 * @todo: Add access callback information from Drupal 7.
 * @todo: Add group information from Drupal 7.
 */
class DataIsEmpty extends RulesConditionBase {

  /**
   * {@inheritdoc}
   */
  public static function contextDefinitions(TypedDataManager $typed_data_manager) {
    $contexts['data'] = ContextDefinition::create($typed_data_manager)
      ->setLabel(t('Data to check'))
      ->setDescription(t('The data to be checked to be empty, specified by using a data selector, e.g. "node:author:name".'));

    return $contexts;
  }

  /**
   * {@inheritdoc}
   */
  public function summary() {
    return $this->t('Data value is empty');
  }

  /**
   * {@inheritdoc}
   */
  public function evaluate() {
    $data = $this->getContext('data')->getContextData();
    if ($data instanceof ComplexDataInterface || $data instanceof ListInterface) {
      return $data->isEmpty();
    }

    $value = $data->getValue();
    return empty($value);
  }

}
