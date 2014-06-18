<?php

/**
 * @file
 * Contains \Drupal\rules\Tests\Plugin\Condition.
 */

namespace Drupal\rules\Tests\Condition;

use Drupal\system\Tests\Entity\EntityUnitTestBase;

/**
 * Tests the 'Node is promoted' condition.
 */
class DataIsEmptyTest extends EntityUnitTestBase {

  /**
   * {@inheritdoc}
   */
  public static $modules = ['node', 'rules'];

  /**
   * The condition manager.
   *
   * @var \Drupal\Core\Condition\ConditionManager
   */
  protected $conditionManager;

  /**
   * The node storage.
   *
   * @var \Drupal\node\NodeStorage
   */
  protected $nodeStorage;

  /**
   * {@inheritdoc}
   */
  public static function getInfo() {
    return [
      'name' => 'Data value is empty condition tests',
      'description' => 'Tests the data value is empty condition.',
      'group' => 'Rules conditions',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    parent::setUp();
    $this->conditionManager = $this->container->get('plugin.manager.condition', $this->container->get('container.namespaces'));
    $this->nodeStorage = $this->entityManager->getStorage('node');

    $this->entityManager->getStorage('node_type')
      ->create(['type' => 'page'])
      ->save();
  }

  /**
   * Tests evaluating the condition.
   */
  public function testConditionEvaluation() {
    // Test with an empty node.
    $node = $this->nodeStorage->create([
      'type' => 'page',
      'title' => '',
    ]);

    // Test an empty node.
    // @todo Test fails because node has populated proerties such as nid.
    $condition = $this->conditionManager->createInstance('rules_data_is_empty')
      ->setContextValue('data', $node);
    $this->assertTRUE($condition->execute());

    // Test an empty node field.
    // @todo Test fails because $node->title is a FieldItemList object with a
    // single item, although that single item is empty.
    $condition = $this->conditionManager->createInstance('rules_data_is_empty')
      ->setContextValue('data', $node->title);
    $this->assertTRUE($condition->execute());

    // Test an empty node field value.
    $condition = $this->conditionManager->createInstance('rules_data_is_empty')
      ->setContextValue('data', $node->title->value);
    $this->assertTrue($condition->execute());

    // Test with a populated node.
    $node = $this->nodeStorage->create([
      'type' => 'page',
      'title' => 'bar',
    ]);

    // Test a populated node.
    $condition = $this->conditionManager->createInstance('rules_data_is_empty')
      ->setContextValue('data', $node);
    $this->assertFalse($condition->execute());

    // Test a populated node field.
    $condition = $this->conditionManager->createInstance('rules_data_is_empty')
      ->setContextValue('data', $node->title);
    $this->assertFalse($condition->execute());

    // Test a populated node field value.
    $condition = $this->conditionManager->createInstance('rules_data_is_empty')
      ->setContextValue('data', $node->title->value);
    $this->assertFalse($condition->execute());
  }

}
