<!DOCTYPE html>
<html lang="en">
    <head>
        <title><?php echo System::get('page_title'); ?> - <?php echo System::get('site_name'); ?></title>
        <meta charset="utf-8" />
        <meta name="keywords" content="Keywords" />
        <meta name="description" content="Description" />
        <meta name="robots" content="all" />
        <link rel="shortcut icon" href="<?php echo System::get('webroot'); ?>public/images/other/favicon.png" />

		<?php
		foreach(System::get('css_files') as $file) {
			echo '<link href="' . $file . '" rel="stylesheet" type="text/css" />';
		}
		foreach(System::get('js_files') as $file) {
			echo '<script type="text/javascript" src="' . $file . '"></script>';
		}
		?>

        <script src="<?php echo System::getPageJSFile(); ?>" type="text/javascript"></script>
    </head>
    <body>

        <h1>Ace MVC</h1>


        <?php echo $contents; ?>
         
 	</body>
 </html>