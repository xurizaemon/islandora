<?php

namespace Drupal\islandora\ContextProvider;

use Drupal\media\MediaInterface;
use Drupal\Core\Plugin\Context\ContextProviderInterface;
use Drupal\Core\Plugin\Context\EntityContext;
use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * Sets the provided media as a context.
 */
class MediaContextProvider implements ContextProviderInterface {

  use StringTranslationTrait;

  /**
   * Media to provide in a context.
   *
   * @var \Drupal\media\MediaInterface
   */
  protected $media;

  /**
   * Constructs a new MediaRouteContext.
   *
   * @var \Drupal\media\MediaInterface $media
   *   The media to provide in a context.
   */
  public function __construct(MediaInterface $media) {
    $this->media = $media;
  }

  /**
   * {@inheritdoc}
   */
  public function getRuntimeContexts(array $unqualified_context_ids) {
    $context = EntityContext::fromEntity($this->media);
    return ['@islandora.media_route_context_provider:media' => $context];
  }

  /**
   * {@inheritdoc}
   */
  public function getAvailableContexts() {
    $context = EntityContext::fromEntityType(\Drupal::entityTypeManager()->getDefinition('media'), $this->t('Media from URL'));
    return ['@islandora.media_route_context_provider:media' => $context];
  }

}
