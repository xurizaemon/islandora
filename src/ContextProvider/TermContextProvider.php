<?php

namespace Drupal\islandora\ContextProvider;

use Drupal\taxonomy\TermInterface;
use Drupal\Core\Plugin\Context\ContextProviderInterface;
use Drupal\Core\Plugin\Context\EntityContext;
use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * Sets the provided media as a context.
 */
class TermContextProvider implements ContextProviderInterface {

  use StringTranslationTrait;

  /**
   * Term to provide in a context.
   *
   * @var \Drupal\term\TermInterface
   */
  protected $term;

  /**
   * Constructs a new TermContextProvider.
   *
   * @var \Drupal\term\TermInterface $term
   *   The term to provide in a context.
   */
  public function __construct(TermInterface $term) {
    $this->term = $term;
  }

  /**
   * {@inheritdoc}
   */
  public function getRuntimeContexts(array $unqualified_context_ids) {
    $context = EntityContext::fromEntity($this->term);
    return ['@islandora.taxonomy_term_route_context_provider:taxonomy_term' => $context];
  }

  /**
   * {@inheritdoc}
   */
  public function getAvailableContexts() {
    $context = EntityContext::fromEntityTypeId('taxonomy_term', $this->t('Term from entity hook'));
    return ['@islandora.taxonomy_term_route_context_provider:taxonomy_term' => $context];
  }

}
