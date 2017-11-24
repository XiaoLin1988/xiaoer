<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo $page_title; ?></title>

    <?php
        foreach ($stylesheets as $style) {
            $url = starts_with($style, 'http') ? $style : base_url($style);

            echo "<link href='$url' rel='stylesheet' />".PHP_EOL;
        }

        foreach ($javascripts['head'] as $js) {
            $url = starts_with($js, 'http') ? $js : base_url($js);
            echo "<script src='$url'></script>".PHP_EOL;
        }
    ?>

    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
</head>
<body>
