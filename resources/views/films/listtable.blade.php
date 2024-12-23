<table class="table">
    <thead>
        <tr>
            <th>Film-Identifikator</th>
            <th>Name</th>
            <th>Genre</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($films as $film) { ?>
        <tr>
            <td><?php echo $film->film_identifier; ?></td>
            <td>
                <strong><?php echo $film->name; ?></strong>
                <br><?php echo $film->description; ?>
            </td>
            <td>
                <?php
                    $genres = [];
                    foreach ($film->genres as $genre) {
                        $genres[] = $genre->name;
                    }
                    echo implode(', ', $genres);
                ?>
            </td>
            <td>
                <?php foreach ($film->userActions as $userAction) { ?>
                <span>
                    <a href="<?php echo $userAction['href']; ?>">
                        <img src="<?php echo $userAction['icon']; ?>" style='height: 15px; cursor: pointer; display: inline' title='<?php echo $userAction['title']; ?>'>
                    </a>
                </span>
               <?php } ?>
            </td>
        </tr>
        <?php } ?>
    </tbody>
</table>
