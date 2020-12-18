<?php

namespace Drupal\islandora\ContextProvider;

use Drupal\Core\Cache\CacheableMetadata;
use Drupal\Core\Plugin\Context\Context;
use Drupal\Core\Plugin\Context\ContextProviderInterface;
use Drupal\Core\Plugin\Context\EntityContext;
use Drupal\Core\Plugin\Context\EntityContextDefinition;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * Sets the current file as a context on file routes.
 */
class TermRouteContextProvider implements ContextProviderInterface {

  use StringTranslationTrait;

  /**
   * The route match object.
   *
   * @var \Drupal\Core\Routing\RouteMatchInterface
   */
  protected $routeMatch;

  /**
   * Constructs a new TermRouteContextProvider.
   *
   * @param \Drupal\Core\Routing\RouteMatchInterface $route_match
   *   The route match object.
   */
  public function __construct(RouteMatchInterface $route_match) {
    $this->routeMatch = $route_match;
  }

  /**
   * {@inheritdoc}
   */
  public function getRuntimeContexts(array $unqualified_context_ids) {
    $context_definition = EntityContextDefinition::fromEntityTypeId('taxonomy_term')->setLabel(NULL)->setRequired(FALSE);
    $value = NULL;

    // Hack the taxonomy term out of the route.
    $route_object = $this->routeMatch->getRouteObject();
    if ($route_object) {
      $route_contexts = $route_object->getOption('parameters');
      if ($route_contexts && isset($route_contexts['taxonomy_term'])) {
        $term = $this->routeMatch->getParameter('taxonomy_term');
        if ($term) {
          $value = $term;
        }
      }
    }

    $cacheability = new CacheableMetadata();
    $cacheability->setCacheContexts(['route']);

    $context = new Context($context_definition, $value);
    $context->addCacheableDependency($cacheability);
    return ['taxonomy_term' => $context];
  }

  /**
   * {@inheritdoc}
   */
  public function getAvailableContexts() {
    $context = EntityContext::fromEntityTypeId('taxonomy_term', $this->t('Term from URL'));
    return ['taxonomy_term' => $context];
  }

}
