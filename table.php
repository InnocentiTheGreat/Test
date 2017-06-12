<!DOCTYPE html>
<html>
<head>
    <title>Reddit</title>
    <?php

    include 'db.php';
    include 'reddit_class.php';
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    ?>
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
    <link href="style.css" rel="stylesheet" type="text/css">
</head>
<body>

<?php

$reddit = new reddit_class();

if (isset($_POST['subreddit']) && !empty($_POST['subreddit'])) { ?>

    <form method="POST" action="table.php">
        <select name="subreddit">
            <?php
            foreach ($reddit->showAllSubreddits() as $sub) {
                echo "<option value='" . $sub[0] . "'>". $sub[0] ."</option>";
            }
            ?>
    </select>
    <input type="submit">
        <?php echo "Currently viewing: " . $_POST['subreddit'] ?>
    </form>
    <table id='myTable' class='tablesorter tablesorter-materialize' border='0'>
        <thead>
        <th>ID</th>
        <th>Subreddit</th>
        <th>Upvotes</th>
        <th>Title</th>
        <th>Date</th>
        <th>Date (human readable)</th>
        <th>Link</th>
        <th>Reddit link</th>
        <th>IMG</th>
        </thead>
        <tbody>
        <?php
        foreach ($reddit->showSubreddit($_POST['subreddit']) as $entry) { ?>
            <tr>
                <td><?php echo $entry->id ?></td>
                <td><?php echo $entry->subreddit ?></td>
                <td><?php echo $entry->upvotes ?></td>
                <td><?php echo $entry->title ?></td>
                <td><?php echo $entry->created ?></td>
                <td><?php echo date('Y-m-d H:i:s', $entry->created) ?></td>
                <td><?php echo "<a href='".$entry->url."'>URL</a>"?></td>
                <td><?php echo "<a href='www.reddit.com". $entry->permalink ."'>Permalink</a>"?></td>
                <td><?php echo (empty($entry->preview_img)) ? "Not available" : "<img src='".$entry->preview_img."' alt='Preview Image' height='150' width='150'>" ?></td>
            </tr>
            <?php
        } ?>
        </tbody>
    </table>

<?php } else { ?>

    <form method="POST" action="table.php">
        <select name="subreddit">
            <?php
            foreach ($reddit->showAllSubreddits() as $sub) {
                echo "<option value='" . $sub[0] . "'>". $sub[0] ."</option>";
            }
            ?>
        </select>
        <input type="submit">
    </form>
    <table id='myTable' class='tablesorter tablesorter-materialize' border='0'>
        <thead>
        <th>ID</th>
        <th>Subreddit</th>
        <th>Upvotes</th>
        <th>Title</th>
        <th>Date</th>
        <th>Date (human readable)</th>
        <th>Link</th>
        <th>Reddit link</th>
        <th>IMG</th>
        </thead>
        <tbody>
        <?php
        foreach ($reddit->showAll() as $entry) { ?>
            <tr>
                <td><?php echo $entry->id ?></td>
                <td><?php echo $entry->subreddit ?></td>
                <td><?php echo $entry->upvotes ?></td>
                <td><?php echo $entry->title ?></td>
                <td><?php echo $entry->created ?></td>
                <td><?php echo date('Y-m-d H:i:s', $entry->created) ?></td>
                <td><?php echo "<a href='".$entry->url."'>URL</a>"?></td>
                <td><?php echo "<a href='www.reddit.com". $entry->permalink ."'>Permalink</a>"?></td>
                <td><?php echo (empty($entry->preview_img)) ? "Not available" : "<img src='".$entry->preview_img."' alt='Preview Image' height='150' width='150'>" ?></td>
            </tr>
            <?php
        } ?>
        </tbody>
    </table>
    <?php
}
?>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script type="text/javascript" src="jquery.tablesorter.min.js"></script>
<script type="text/javascript">
    $(function(){
        $("#myTable").tablesorter({
            //theme : 'blue',
            //sortList: [0,0]
        });
    });
</script>
</body>
</html>
