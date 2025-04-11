<?php

declare(strict_types=1);

namespace Drupal\hello\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a Hello form.
 */
final class ExampleForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'hello_example';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {


    $users = \Drupal::entityTypeManager()->getStorage('user')->loadMultiple();
    $options = [];
    foreach ($users as $user) {
      $options[$user->id()] = $user->getDisplayName();
    }

    $valeur= \Drupal::state()->get('hello.last_sent from the form was  : ');
    $valeur1 = \Drupal::service('date.formatter')->format($valeur, 'short');
    $form['last_sent'] = [
      '#markup' => $this->t('Last sent : %date', ['%date' => $valeur1]),
    ];
    $form['author'] = [
      '#type' => 'select',
      '#options' => $options,
      '#title' => $this->t('Author'),
      '#required' => TRUE,
    ];
    $form['title'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Title'),
      '#required' => TRUE,
      '#maxlength' => 25,
    ];

    $form['actions'] = [
      '#type' => 'actions',
      'submit' => [
        '#type' => 'submit',
        '#value' => $this->t('Send'),
      ],
    ];

    return $form;
  }


  public function submitForm(array &$form, FormStateInterface $form_state): void {
    $node= \Drupal::entityTypeManager()->getStorage('node')->create([
      'type' => 'article',
      'status' => 1,
      'title' => $form_state->getValue('title'),
      'uid' => $form_state->getValue('author'),
    ]);
    $node->setPublished(True);
    $node->save();


    $now = \Drupal::time()->getCurrentTime();
    \Drupal::state()->set('hello.last_sent from the form was  : ', $now);

    $form_state->setRedirect('entity.node.canonical', ['node' => $node->id()]);
  }

}
