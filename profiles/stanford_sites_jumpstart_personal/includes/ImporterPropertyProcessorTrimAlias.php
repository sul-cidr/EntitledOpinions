<?php
/**
 * @file
 * ImporterPropertyProcessorTrimAlias Class
 */

class ImporterPropertyProcessorTrimAlias extends ImporterFieldProcessor {

  /**
   * [process description]
   * @param  [type] $entity      [description]
   * @param  [type] $entity_type [description]
   * @param  [type] $property    [description]
   * @return [type]              [description]
   */
  public function process(&$entity, $entity_type, $property) {
    if (!isset($entity->{$property}[0]['alias'])) {
      return;
    }

    $alias = $entity->{$property}[0]['alias'];
    $parts = explode('-', $alias);

    // Check to see if the last item is numeric
    $last = array_pop($parts);
    // The less than 10 is arbitrary in order to allow paths with numbers at the
    // end that may be intentional. IE: Some event 2014.
    if (!is_numeric($last) || $last >= 10) {
      $parts[] = $last;
    }

    $alias = implode("-", $parts);
    $entity->{$property}[0]['alias'] = $alias;

  }

}
