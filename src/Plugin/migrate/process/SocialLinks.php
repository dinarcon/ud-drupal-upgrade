<?php

namespace Drupal\ud_d8_upgrade\Plugin\migrate\process;

use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\Row;

/**
 *
 * @MigrateProcessPlugin(
 *   id = "gnuget_social_links"
 * )
 */
class SocialLinks extends ProcessPluginBase {

  /**
   * {@inheritdoc}
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {
    $network_values = $value;
    $network_ids = $this->configuration['network_ids'];
    $result = [];
    $domains_patterns = [
      'facebook' => '/(http(s)?\:\/\/)?(www\.)?facebook\.com\//',
      'twitter' => '/(http(s)?\:\/\/)?(www\.)?twitter\.com\//',
      'instagram' => '/(http(s)?\:\/\/)?(www\.)?instagram\.com\//',
      'tumblr' => '/^$/',
      'website' => '/^$/'
    ];

    foreach ($network_values as $key => $value) {
      if (empty($value)) {
        continue;
      }

      $raw_value = is_array($value) ? $value[0]['value'] : $value;

      // Remove the domain from the value (the social_links field) already
      // prepend the domain depending the social network.
      $clean_value = preg_replace($domains_patterns[$network_ids[$key]], '', $raw_value);

      // Put the data in the same format as expected by the field.
      $result[$network_ids[$key]] = [
        'value' => $clean_value,
        'title' => '',
        'attributes' => '',
      ];
    }

    return [
      'platform' => '',
      'value' => '',
      'platform_values' => $result,
    ];
  }
}
