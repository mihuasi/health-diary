<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Welcome to CodeIgniter</title>

        <script type="text/javascript" src="<?php echo base_url();?>js/lib/jquery.1.11.full.js" ></script>
        <script type="text/javascript" src="<?php echo base_url();?>js/lib/jquery-ui-1.10.4.custom.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url();?>js/lib/tag-it.js"></script>
        <script type="text/javascript" src="<?php echo base_url();?>js/lib/editable.1.7.1.js"></script>
        <script type="text/javascript" src="<?php echo base_url();?>js/settings.js" ></script>

        <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/base.css">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/settings.css">
        <link rel="stylesheet" type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1/themes/flick/jquery-ui.css">
<link href="css/jquery.tagit.css" rel="stylesheet" type="text/css">

</head>
<body>

<div id="container">
    <h1>Settings</h1>
    <div class="influences-settings-wrapper">
        <?php echo renderInfluencesSettings($influences, $tableClass); ?>
    </div>
    <div class="health-aspects-settings-wrapper">
        <?php echo renderAspectsSettings($aspects, $tableClass); ?>
    </div>
</div>

</body>
</html>