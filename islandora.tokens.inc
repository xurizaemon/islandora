<?php

/**
 * @file
 * Contains islandora.tokens.inc.
 *
 * This file provides islandora tokens.
 */

use Drupal\Core\Render\BubbleableMetadata;

/**
 * Implements hook_token_info().
 */
function islandora_token_info() {
  $type = [
    'name' => t('Islandora Tokens'),
    'description' => t('Tokens for Islandora objects.'),
  ];
  $node['media-thumbnail-image:url'] = [
    'name' => t('Media: Thumbnail Image URL.'),
    'description' => t('URL of Thumbnail Image associated with Islandora Object via Media.'),
  ];

  $node['media-thumbnail-image:alt'] = [
    'name' => t('Alternative text for Media: Thumbnail Image.'),
    'description' => t('Alternative text for Thumbnail Image associated with Islandora Object via Media.'),
  ];

  // Deprecated in favour if hyphenated version.
  $node['media_thumbnail_image:url'] = [
    'name' => t('Media: Thumbnail Image URL.'),
    'description' => t('Deprecated: URL of Thumbnail Image associated with Islandora Object via Media.'),
  ];

  // Deprecated in favour if hyphenated version.
  $node['media_thumbnail_image:alt'] = [
    'name' => t('Alternative text for Media: Thumbnail Image.'),
    'description' => t('Deprecated: Alternative text for Thumbnail Image associated with Islandora Object via Media.'),
  ];

  return [
    'types' => ['islandoratokens' => $type],
    'tokens' => ['islandoratokens' => $node],
  ];
}

/**
 * Implements hook_tokens().
 */
function islandora_tokens($type, $tokens, array $data, array $options, BubbleableMetadata $bubbleable_metadata) {
  $replacements = [];
  if ($type == 'islandoratokens' && !empty($data['node'])) {
    if (!is_array($tokens) || empty($tokens)) {
      \Drupal::logger('islandora')
        ->alert(
            'Tokens not correct format: @tokens', [
              '@tokens' => print_r($tokens, 1),
            ]
        );
      return;
    }
    $islandoraUtils = \Drupal::service('islandora.utils');
    foreach ($tokens as $name => $original) {
      switch ($name) {
        case 'media-thumbnail-image:url':
        case 'media_thumbnail_image:url':
          $term = $islandoraUtils->getTermForUri('http://pcdm.org/use#ThumbnailImage');
          $media = $islandoraUtils->getMediaWithTerm($data['node'], $term);
          // Is there media?
          // @todo is this single or multiple?
          if ($media) {
            $file = \Drupal::service('islandora.media_source_service')->getSourceFile($media);
            if (!empty($file)) {
              $url = $file->url();
              $replacements[$original] = $url;
            }
          }
          break;

        case 'media-thumbnail-image:alt':
        case 'media_thumbnail_image:alt':
          $alt = '';
          $term = $islandoraUtils->getTermForUri('http://pcdm.org/use#ThumbnailImage');
          $media = $islandoraUtils->getMediaWithTerm($data['node'], $term);
          // Is there media?
          // @todo is this single or multiple?
          if ($media) {
            // Is the media an image?
            if (isset($media->field_media_image)) {
              $alt = $media->field_media_image[0]->alt;
            }
          }
          // @todo get alt from original or service file, if thumbnail
          // alt is empty.
          $replacements[$original] = $alt;
          break;
      }
    }
  }
  return $replacements;
}
