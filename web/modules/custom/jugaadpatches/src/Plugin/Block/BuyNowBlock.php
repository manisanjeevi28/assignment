<?php

namespace Drupal\jugaadpatches\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Com\Tecnick\Barcode\Barcode;

/**
 * Provides a block for switching users.
 *
 * @Block(
 *   id = "buynow_qr",
 *   admin_label = @Translation("Buy Now"),
 *   category = "Custom"
 * )
 */
class BuyNowBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {

    $node = \Drupal::routeMatch()->getParameter('node');
    if ($node instanceof \Drupal\node\NodeInterface) {
      // You can get nid and anything else you need from the node object.
      $nid = $node->id();
      $alias = \Drupal::service('path_alias.manager')->getAliasByPath('/node/'.$nid);
    }

    // instantiate the barcode class
    $objBarcode = new Barcode();

    // generate a barcode
    $objQR = $objBarcode->getBarcodeObj(
                            'QRCODE,H', // barcode type and additional comma-separated parameters
                            "$alias", // data string to encode
                            -4, // bar width (use absolute or negative value as multiplication factor)
                            -4, // bar height (use absolute or negative value as multiplication factor)
                            'black', // foreground color
                            array(-2, -2, -2, -2)           // padding (use absolute or negative values as multiplication factors)
                          )->setBackgroundColor('white'); // background color

    return [
      '#theme' => 'buynow_block',
      '#qr_code' => $this->t($objQR->getHtmlDiv())
    ];
  }

}
