<?php

/**
 * @file
 * Contains \Drupal\redhen_org\Entity\Org.
 */

namespace Drupal\redhen_org\Entity;

use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\redhen_org\OrgInterface;

/**
 * Defines the Org entity.
 *
 * @ingroup redhen_org
 *
 * @ContentEntityType(
 *   id = "redhen_org",
 *   label = @Translation("Org"),
 *   label_singular = @Translation("org"),
 *   label_plural = @Translation("orgs"),
 *   label_count = @PluralTranslation(
 *     singular = "@count org",
 *     plural = "@count org",
 *   ),
 *   bundle_label = @Translation("Org type"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\redhen_org\OrgListBuilder",
 *     "views_data" = "Drupal\redhen_org\Entity\OrgViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\redhen_org\Form\OrgForm",
 *       "add" = "Drupal\redhen_org\Form\OrgForm",
 *       "edit" = "Drupal\redhen_org\Form\OrgForm",
 *       "delete" = "Drupal\redhen_org\Form\OrgDeleteForm",
 *     },
 *     "access" = "Drupal\redhen_org\OrgAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\redhen_org\OrgHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "redhen_org",
 *   revision_table = "redhen_org_revision",
 *   admin_permission = "administer org entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "revision" = "revision_id",
 *     "bundle" = "type",
 *     "uuid" = "uuid",
 *     "uid" = "user_id",
 *     "langcode" = "langcode",
 *     "status" = "status",
 *   },
 *   links = {
 *     "canonical" = "/redhen/org/{redhen_org}",
 *     "add-form" = "/redhen/org/add/{redhen_org_type}",
 *     "edit-form" = "/redhen/org/{redhen_org}/edit",
 *     "delete-form" = "/redhen/org/{redhen_org}/delete",
 *     "collection" = "/redhen/org",
 *   },
 *   bundle_entity_type = "redhen_org_type",
 *   field_ui_base_route = "entity.redhen_org_type.edit_form"
 * )
 */
class Org extends ContentEntityBase implements OrgInterface {
  use EntityChangedTrait;

  /**
   * {@inheritdoc}
   */
  public function label() {
    return $this->getName();
  }

  /**
   * {@inheritdoc}
   */
  public function getName() {
    $name = $this->get('name')->value;
    // Allow other modules to alter the name of the org.
    \Drupal::moduleHandler()->alter('redhen_org_name', $name, $this);
    return $name;
  }

  /**
   * {@inheritdoc}
   */
  public function setName($name) {
    $this->set('name', $name);
    return $this;
  }
  
  /**
   * {@inheritdoc}
   */
  public function getType() {
    return $this->bundle();
  }

  /**
   * {@inheritdoc}
   */
  public function getCreatedTime() {
    return $this->get('created')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setCreatedTime($timestamp) {
    $this->set('created', $timestamp);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getRevisionCreationTime() {
    return $this->get('revision_timestamp')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setRevisionCreationTime($timestamp) {
    $this->set('revision_timestamp', $timestamp);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getRevisionAuthor() {
    return $this->get('revision_uid')->entity;
  }

  /**
   * {@inheritdoc}
   */
  public function setRevisionAuthorId($uid) {
    $this->set('revision_uid', $uid);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function isActive() {
    return (bool) $this->getEntityKey('status');
  }

  /**
   * {@inheritdoc}
   */
  public function setActive($active) {
    $this->set('status', $active ? REDHEN_ORG_INACTIVE : REDHEN_ORG_ACTIVE);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Name'))
      ->setDescription(t('The name of the org.'))
      ->setSettings(array(
        'max_length' => 50,
        'text_processing' => 0,
      ))
      ->setDefaultValue('')
      ->setDisplayOptions('form', array(
        'type' => 'string_textfield',
        'weight' => -10,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setRevisionable(TRUE);

    $fields['status'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Active'))
      ->setDescription(t('A boolean indicating whether the Org is active.'))
      ->setDefaultValue(TRUE)
      ->setDisplayOptions('form', array(
        'type' => 'boolean_checkbox',
        'settings' => array(
          'display_label' => TRUE,
        ),
        'weight' => 16,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setRevisionable(TRUE);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the org was created.'))
      ->setRevisionable(TRUE);

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the org was last edited.'))
      ->setRevisionable(TRUE);

    return $fields;
  }

}