<?php if (!defined('SYSTEM')) exit('No direct script access allowed'); ?>

<?php if (count($categories) > 0) : ?>
    <table>
        <thead>
            <tr>
                <td><?php echo _NAME_; ?></td>
                <td><?php echo _DESCRIPTION_; ?></td>
            </tr>
        </thead>
        <?php $i = 1; ?>
        <?php foreach ($categories as $index => $category) : ?>
            <tr <?php if ($i % 2 == 0) echo 'class="even"' ?>>
                <td><?php echo HTML::a(WEB . "categories/show/{$category['_id']}", $category['name']); ?></td>
                <td><?php echo $category['description']; ?></td>
                <td><?php echo HTML::a(WEB . "categories/edit/{$category['_id']}", 'edit'); ?></td>
                <td><?php echo HTML::a(WEB . "categories/delete/{$category['_id']}", 'delete', array('onclick' => 'return confirm(\'Sure?\')')); ?></td>
            </tr>
        <?php $i++; ?>
        <?php endforeach; ?>
    </table>
<?php endif; ?>