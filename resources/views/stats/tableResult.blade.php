<?php
    /** @var \App\Services\Statistic\Model\TableResult $tableResult */
    /** @var string $headline */
?>
<?php echo $headline; ?>
<table class="table">
    <thead>
        <tr>
            <?php foreach ($tableResult->getHeader() as $column) { ?>
            <td><?php echo $column; ?></td>
            <?php } ?>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($tableResult->getResult() as $row) { ?>
        <tr>
            <?php foreach ($row as $value) { ?>
            <td><?php echo $value; ?></td>
            <?php } ?>
        </tr>
    <?php } ?>
    </tbody>
</table>
