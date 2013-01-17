<?php
/**
 * @file
 * Administrative settings for the Marketo Webform Integration
 */


/**
 * Page callback for admin/config/marketo-webform
 */
function marketo_webform_settings_form() {
  $form = array();
  if (module_exists('marketo_munchkin')) {
    $form['marketo_webform_soap'] = array(
      '#type' => 'radios',
      '#title' => t('Marketo API type'),
      '#description' => t('Specify the way you wish webform submissions to be sent to Marketo.'),
      '#options' => array(
        0 => t('Munchkin API'),
        1 => t('SOAP API'),
      ),
      '#default_value' => variable_get('marketo_webform_soap', 1),
    );
  }
  else {
    $form['munchkin'] = array(
      '#markup' => '<em>' . t('Enable the @m module to use the Marketo Munchkin API', array('@m' => 'marketo_munchkin')) . '</em>',
    );
  }
  $form['marketo_webform_soap_endpoint'] = array(
    '#type' => 'textfield',
    '#title' => t('Marketo SOAP API Endpoint'),
    '#description' => t('The SOAP API Endpoint for your Marketo Instance'),
    '#default_value' => variable_get('marketo_webform_soap_endpoint', 'https://na-k.marketo.com/soap/mktows/1_6'),
  );
  $form['marketo_webform_soap_access_key'] = array(
    '#type' => 'textfield',
    '#title' => t('Marketo SOAP API Access Key'),
    '#description' => t('The SOAP API Access Key for your Marketo Instance'),
    '#default_value' => variable_get('marketo_webform_soap_access_key', ''),
  );
  $form['marketo_webform_soap_secret_key'] = array(
    '#type' => 'textfield',
    '#title' => t('Marketo SOAP API Secret Key'),
    '#description' => t('The SOAP API Secret Key for your Marketo Instance'),
    '#default_value' => variable_get('marketo_webform_soap_secret_key', ''),
  );
  return system_settings_form($form);
}
