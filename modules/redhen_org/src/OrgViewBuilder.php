<?php

namespace Drupal\redhen_org;

use Drupal\Core\Entity\Display\EntityViewDisplayInterface;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityViewBuilder;

/**
 * View builder handler for redhen orgs.
 *
 * Necessary to add contextual links until https://www.drupal.org/node/2791571
 * is fixed.
 */
class OrgViewBuilder extends EntityViewBuilder {

  /**
   * {@inheritdoc}
   */
  protected function alterBuild(array &$build, EntityInterface $entity, EntityViewDisplayInterface $display, $view_mode) {
    parent::alterBuild($build, $entity, $display, $view_mode);
    $build['#contextual_links']['redhen_org'] = [
      'route_parameters' => ['redhen_org' => $entity->id()],
      'metadata' => ['changed' => $entity->getChangedTime()],
    ];
  }

}
