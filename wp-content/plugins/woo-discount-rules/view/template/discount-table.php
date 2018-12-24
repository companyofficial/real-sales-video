<?php
/**
 * List matched Rules in Table format
 *
 * This template can be overridden by copying it to yourtheme/plugin-folder-name/discount-table.php
 */

if (!defined('ABSPATH')) exit; // Exit if accessed directly
if (!isset($table_data) || empty($table_data)) return false;
$base_config = (is_string($data)) ? json_decode($data, true) : (is_array($data) ? $data : array());
?>
<table>
    <thead>
    <tr>
        <?php if (isset($base_config['show_discount_title_table'])) {
            if ($base_config['show_discount_title_table'] == 'show') {
                ?>
                <td><?php esc_html_e('Name', 'woo-discount-rules'); ?></td>
            <?php }
        } ?>
        <td><?php esc_html_e('Range', 'woo-discount-rules'); ?></td>
        <td><?php esc_html_e('Discount', 'woo-discount-rules'); ?></td>
    </tr>
    </thead>
    <tbody>
    <?php
    $have_discount = false;
    $table = $table_data;
    foreach ($table as $index => $item) {
        if ($item) {
            foreach ($item as $id => $value) {
                ?>
                <tr>
                    <?php if (isset($base_config['show_discount_title_table'])) {
                        if ($base_config['show_discount_title_table'] == 'show') {
                            ?>
                            <td><?php echo $table_data_content[$index.$id]['title']; ?></td>
                        <?php }
                    } ?>
                    <td><?php echo $table_data_content[$index.$id]['condition']; ?></td>
                    <td><?php echo $table_data_content[$index.$id]['discount']; ?></td>
                </tr>
            <?php }
            $have_discount = true;
        }
    }
    if (!$have_discount) {
        ?>
        <tr>
            <td colspan="2">
                <?php esc_html_e('No Active Discounts.', 'woo-discount-rules'); ?>
            </td>
        </tr>
    <?php
    }
    ?>
    </tbody>
</table>
