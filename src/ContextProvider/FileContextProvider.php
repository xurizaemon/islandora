<?php

namespace Drupal\islandora\ContextProvider;

use Drupal\file\FileInterface;
use Drupal\Core\Plugin\Context\ContextProviderInterface;
use Drupal\Core\Plugin\Context\EntityContext;
use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * Sets the provided media as a context.
 */
class FileContextProvider implements ContextProviderInterface {

  use StringTranslationTrait;

  /**
   * File to provide in a context.
   *
   * @var \Drupal\file\FileInterface
   */
  protected $file;

  /**
   * Constructs a new FileContextProvider.
   *
   * @var \Drupal\file\FileInterface $file
   *   The file to provide in a context.
   */
  public function __construct(FileInterface $file) {
    $this->file = $file;
  }

  /**
   * {@inheritdoc}
   */
  public function getRuntimeContexts(array $unqualified_context_ids) {
    $context = EntityContext::fromEntity($this->file);
    return ['@islandora.file_route_context_provider:file' => $context];
  }

  /**
   * {@inheritdoc}
   */
  public function getAvailableContexts() {
    $context = EntityContext::fromEntityTypeId('file', $this->t('File from entity hook'));
    return ['@islandora.file_route_context_provider:file' => $context];
  }

}
