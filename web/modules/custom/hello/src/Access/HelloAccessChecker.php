<?php

declare(strict_types=1);

namespace Drupal\hello\Access;

use Drupal\Component\Datetime\Time;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Entity\EntityTypeManagerInterface;

use Drupal\Core\Routing\Access\AccessInterface;
use Drupal\Core\Session\AccountInterface;

use Symfony\Component\Routing\Route;

/**
 * Checks if passed parameter matches the route configuration.
 *
 * Usage example:
 * @code
 * foo.example:
 *   path: '/example/{parameter}'
 *   defaults:
 *     _title: 'Example'
 *     _controller: '\Drupal\hello\Controller\HelloController'
 *   requirements:
 *     _hello: 'some value'
 * @endcode
 */
final class HelloAccessChecker implements AccessInterface {








  /**
   * Access callback.
   *
   * @DCG
   * Drupal does some magic when resolving arguments for this callback. Make
   * sure the parameter name matches the name of the placeholder defined in the
   * route, and it is of the same type.
   * The following additional parameters are resolved automatically.
   *   - \Drupal\Core\Routing\RouteMatchInterface
   *   - \Drupal\Core\Session\AccountInterface
   *   - \Symfony\Component\HttpFoundation\Request
   *   - \Symfony\Component\Routing\Route
   */
  public function access(Route $route, AccountInterface $account): AccessResult
  {
    if ($account->isAnonymous()) {
      return AccessResult::forbidden();
    }
    $nb_heures = $route->getRequirement('_hello');
//    Ksm($account);
//    dump($account);
//    kint($account);
    $user=\Drupal::entityTypeManager()->getStorage('user')->load($account->id());
//    Ksm($user);
    $created=$user->getCreatedTime();
    $now=\Drupal::time()->getRequestTime();



    return AccessResult::allowedIf($now-$created > $nb_heures*3600);
  }

}
