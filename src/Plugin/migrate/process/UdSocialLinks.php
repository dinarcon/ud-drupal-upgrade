<?php

namespace Drupal\ud_drupal_upgrade\Plugin\migrate\process;

use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\Row;

/**
 *
 * @MigrateProcessPlugin(
 *   id = "ud_social_links"
 * )
 */
class UdSocialLinks extends ProcessPluginBase {

  /**
   * {@inheritdoc}
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {
    $social_links = [];
    $result = [];

    foreach ($value as $urlField) {
      if (isset($urlField[0]['value'])) {
        $social_links[]  = $urlField[0]['value'];
      }
    }

    $patterns = [
      'twitter' => '/^(http(s)?\:\/\/)?(www\.)?twitter\.com\//',
      'facebook' => '/^(http(s)?\:\/\/)?(www\.)?facebook\.com\//',
    ];

    foreach ($social_links as $social_link) {
      foreach ($patterns as $schema_column => $pattern) {
        if (preg_match($pattern, $social_link)) {
          $result[] = [
            'social' => $schema_column,
            'link' => preg_replace($pattern, '', $social_link),
          ];
        }
      }
    }

    return $result;
  }
}
