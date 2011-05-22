<?php if (!defined('SYSTEM')) exit('No direct script access allowed'); ?>
<?php $products = Arr::item($data, 'products')?>
<?php if (count($products) > 0) : ?>
    <table>
        <thead>
            <tr>
                <td><?php echo _NAME_; ?></td>
                <td><?php echo _DESCRIPTION_; ?></td>
            </tr>
        </thead>
        <?php $i = 1; ?>
        <?php foreach ($products as $index => $product) : ?>
            <tr <?php if ($i % 2 == 0) echo 'class="even"' ?>>
                <td><?php echo HTML::a(WEB . "products/show/{$product['_id']}", $product['name']); ?></td>
                <td><?php echo $product['description']; ?></td>
                <td><?php echo HTML::a(WEB . "products/edit/{$product['_id']}", 'edit'); ?></td>
                <td><?php echo HTML::a(WEB . "products/delete/{$product['_id']}", 'delete'); ?></td>
            </tr>
        <?php $i++; ?>
        <?php endforeach; ?>
    </table>
<?php endif; ?>