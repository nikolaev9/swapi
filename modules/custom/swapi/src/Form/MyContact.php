<?php

/**
 * @file
 * Contains \Drupal\swapi\Form\MyContact.
 */

namespace Drupal\swapi\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\user\Entity\User;

class MyContact extends FormBase {

  /**
   * {@inheritdoc}.
   */
  public function getFormId(): string {
    return 'contact';
  }

  /**
   * {@inheritdoc}.
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {
    $form['name'] = array(
      '#type' => 'textfield',
      '#title' => 'Ваше имя'
    );

    $form['lastname'] = array(
      '#type' => 'textfield',
      '#title' => 'Ваша фамилия'
    );

    // Автоматически заполяем имя и фамилию
    $currentUser = \Drupal::currentUser();
    if($currentUser->isAuthenticated()) {
      $user = User::load($currentUser->id());

      if(!empty($user->field_name->value)) $form['name']['#default_value'] = $user->field_name->value;
      if(!empty($user->field_lastname->value)) $form['lastname']['#default_value'] = $user->field_lastname->value;
    }

    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = array(
      '#type' => 'submit',
      '#value' => 'Отправить',
      '#button_type' => 'primary',
    );

    return $form;
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Не успел сделать отправку письма

    drupal_set_message($this->t('You name is @name, your lastname is @lastname', array(
      '@name' => $form_state->getValue('name'),
      '@lastname' => $form_state->getValue('lastname')
    )));
  }


}
