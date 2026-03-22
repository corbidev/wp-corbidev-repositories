<?php
if (!defined('ABSPATH')) exit;

/** @var array $item */
/** @var string $type */
?>

<tr>
                    <td><?= esc_html($item['name']) ?></td>
                    <td><?= esc_html($item['description']) ?></td>
                    <td><?= esc_html($item['version']) ?></td>
                    <td>
                        <button
 
                           class="button corbidev-install"
data-action="install"
 
        data-name="<?php echo esc_attr($item['slug']); ?>"

                          data-repo="<?= esc_attr($item['name']) ?>"
                            data-owner="<?= esc_attr($item['owner']) ?>"
                            data-type="<?= esc_attr($type) ?>"
                        >
                            Installer
                        </button>
                    </td>
                </tr>





