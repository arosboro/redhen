<?php

namespace Drupal\redhen_connection;

use Drupal\Core\Entity\Display\EntityViewDisplayInterface;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityViewBuilder;

/**
 * View builder handler for redhen connections.
 *
 * Necessary to add contextual links until https://www.drupal.org/node/2791571
 * is fixed.
 */
class ConnectionViewBuilder extends EntityViewBuilder {

  /**
   * {@inheritdoc}
   */
  protected function alterBuild(array &$build, EntityInterface $entity, EntityViewDisplayInterface $display, $view_mode) {
    parent::alterBuild($build, $entity, $display, $view_mode);
    $build['#contextual_links']['redhen_connection'] = [
      'route_parameters' => ['redhen_connection' => $entity->id()],
      'metadata' => ['changed' => $entity->getChangedTime()],
    ];
  }

}
