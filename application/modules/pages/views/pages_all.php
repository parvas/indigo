<?php if (!defined('SYSTEM')) exit('No direct script access allowed'); ?>
<?php $pages = Arr::item($data, 'pages'); ?>
<?php if (count($pages) > 0) : ?>
    <table>
        <thead>
            <tr>
                <td><?php echo _TITLE_; ?></td>
                <td><?php echo _SUMMARY_; ?></td>
                <td><?php echo _CONTENT_; ?></td>
                <td>Ημ/νία</td>
            </tr>
        </thead>
        <?php $i = 1; ?>
        <?php foreach ($pages as $index => $page) : ?>
        <?php $submit = $page['submit_date']; ?>
            <tr <?php if ($i % 2 == 0) echo 'class="even"' ?>>
                <td><?php echo HTML::a(WEB . "pages/show/{$page['_id']}", $page['title']); ?></td>
                <td><?php echo $page['summary']; ?></td>
                <td><?php echo $page['content']; ?></td>
                <td><?php echo date('Y/m/d H:i:s', $submit->sec); ?></td>
                <td><?php echo HTML::a(WEB . "pages/edit/{$page['_id']}", 'edit'); ?></td>
                <td><?php echo HTML::a(WEB . "pages/delete/{$page['_id']}", 'delete', array('onclick' => 'return confirm(\'Sure?\')')); ?></td>
            </tr>
        <?php $i++; ?>
        <?php endforeach; ?>
    </table>
<?php endif; ?>

