<?php if (!defined('SYSTEM')) exit('No direct script access allowed'); ?>
<?php $projects = Arr::item($data, 'projects'); ?>
<?php if (count($projects) > 0) : ?>
    <table>
        <thead>
            <tr>
                <td><?php echo _TITLE_; ?></td>
                <td><?php echo _DESCRIPTION_; ?></td>
                <td>Ημ/νία</td>
            </tr>
        </thead>
        <?php $i = 1; ?>
        <?php foreach ($projects as $index => $project) : ?>
        <?php $submit = $project['submit_date']; ?>
            <tr <?php if ($i % 2 == 0) echo 'class="even"' ?>>
                <td><?php echo HTML::a(WEB . "projects/{$project['_id']}", $project['title']); ?></td>
                <td><?php echo $project['description']; ?></td>
                <td><?php echo date('Y/m/d H:i:s', $submit->sec); ?></td>
                <td><?php echo HTML::a(WEB . "projects/edit/{$project['_id']}", 'edit'); ?></td>
                <td><?php echo HTML::a(WEB . "projects/delete/{$project['_id']}", 'delete', array('onclick' => 'return confirm(\'Sure?\')')); ?></td>
            </tr>
        <?php $i++; ?>
        <?php endforeach; ?>
    </table>
<?php endif; ?>

