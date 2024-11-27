<?php
use Drupal\Core\Template\Attribute;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\FieldDefinition;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\block\Entity\Block;
use Drupal\Core\Form\FormStateInterface;
use \Drupal\Core\File\FileSystemInterface;

/**
 * Implements hook_form_FORM_ID_alter().
 */
function dafr_themer_form_node_form_alter(&$form, \Drupal\Core\Form\FormStateInterface &$form_state, $form_id) {
   unset($form['dafr_blockbuilder_layout']);
   unset($form['dafr_shortcode']);

   $form['dafr_node_settings'] = array(
      '#type'   => 'details',
      '#title'  => t('Node Settings'),
      '#group'  => 'advanced',
      '#open'   => TRUE,
      '#access' => TRUE,
      '#attributes' => array('class' => array('node-class-form')),
   );

   $path = \Drupal::service('path.current')->getPath();
   $path_args = explode('/', $path);
   $node_id = 0;
   if (isset($path_args[1]) && isset($path_args[2]) && ($path_args[1] == 'node') && (is_numeric($path_args[2]))) {
      $node = \Drupal\node\Entity\Node::load($path_args[2]);
      if($node->id()){
         $node_id = $node->id();
      }
   }
 
   $form['dafr_node_layout']['#group']          = 'dafr_node_settings';
   $form['dafr_header']['#group']               = 'dafr_node_settings';   
   $form['dafr_breadcrumb']['#group']           = 'dafr_node_settings';
   $form['dafr_footer']['#group']               = 'dafr_node_settings';      
   $form['dafr_node_class']['#group']           = 'dafr_node_settings';
}

/**
 * Implements hook_entity_base_field_info().
 */
function dafr_themer_entity_base_field_info(EntityTypeInterface $entity_type) {
   if ($entity_type->id() === 'node') {

      $fields['dafr_node_layout'] = BaseFieldDefinition::create('list_string')
         ->setSetting('allowed_values', [
            'fw' => 'Fullwith',            
            'container-xxl' => 'Container xxl',            
            'container' => 'Container'
         ])
         ->setLabel(t('Layout settings'))
         ->setDisplayOptions('form', array(
            'type'    => 'options_select',
            'weight'  => 1,
         ))
         ->setDisplayConfigurable('form', TRUE);     

      $fields['dafr_header'] = BaseFieldDefinition::create('list_string')
         ->setSetting('allowed_values', [
            'header' => t('Standard'),
            'header-2' => t('Sticky'),
            'header-3' => t('Background'),
            'no-header' => t('No Header')
         ])
         ->setLabel(t('Header'))
         ->setDisplayOptions('form', array(
         'type'    => 'options_select',
         'weight'  => 2,
         ))
         ->setDisplayConfigurable('form', TRUE);
      
      $fields['dafr_breadcrumb'] = BaseFieldDefinition::create('list_string')
         ->setSetting('allowed_values', [
            'enable'    => 'Enable',
            'disable'   => 'Disable'
         ])
         ->setLabel(t('Breadcrumb settings'))
         ->setDisplayOptions('form', array(
            'type'    => 'options_select',
            'weight'  => 3,
         ))
         ->setDisplayConfigurable('form', TRUE);   

      $fields['dafr_footer'] = BaseFieldDefinition::create('list_string')
         ->setSetting('allowed_values', [            
            'disable'   => 'Disable'
         ])
         ->setLabel(t('Footer'))
         ->setDisplayOptions('form', array(
            'type'    => 'options_select',
            'weight'  => 4,
         ))
         ->setDisplayConfigurable('form', TRUE);  

      $fields['dafr_node_class'] = BaseFieldDefinition::create('string')
         ->setLabel(t('CSS class(es)'))
         ->setDisplayOptions('form', array(
            'type'    => 'string_textfield',
            'weight'  =>5,
         ))
         ->setDisplayConfigurable('form', TRUE);
      
      return $fields;
   }
}


/**
 * Implements hook_form_FORM_ID_alter().
 */
 

function dafr_themer_validate_path($path) {
   // Absolute local file paths are invalid.
   if (\Drupal::service('file_system')->realpath($path) == $path) {
      return FALSE;
   }
   // A path relative to the Drupal root or a fully qualified URI is valid.
   if (is_file($path)) {
      return $path;
   }
   // Prepend 'public://' for relative file paths within public filesystem.
   if (file_uri_scheme($path) === FALSE) {
      $path = 'public://' . $path;
   }
   if (is_file($path)) {
      return $path;
   }
   return FALSE;
}