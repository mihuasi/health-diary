<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Welcome to CodeIgniter</title>

        <script type="text/javascript" src="<?php echo base_url();?>js/lib/jquery.1.11.full.js" ></script>
        <script type="text/javascript" src="<?php echo base_url();?>js/lib/jquery-ui-1.10.4.custom.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url();?>js/lib/tag-it.js"></script>
        <script type="text/javascript" src="<?php echo base_url();?>js/welcome.js" ></script>

        <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/base.css">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/tracker.css">
        <link rel="stylesheet" type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1/themes/flick/jquery-ui.css">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>jquery.tagit.css">

</head>
<body>

<div id="container">
    <h1>Tracker</h1>
    <div class="information">
        <p class="newEntryMessage">New Entry for <span class="messageDate"></span> added. <a href="" class="messageLink" alt="view entry" title="Slide to Entry">View Entry</a></p>
        <p class="editEntryMessage">Entry for <span class="messageDate"></span> edited. <a href="" class="messageLink" alt="view entry" title="Slide to Entry">View Entry</a></p>
        <div class="closeInfo" title="Hide message">[x]</div>
    </div>
    <div class="newEntryFormContainer">
        <?php echo renderForm($currentDay, $aspects, $influences); ?>
    </div>
    <div class="clearboth"></div>
    <div class="previousDaysContainer">
        <?php echo renderPreviousDays($previousDays); ?>
    </div>
</div>

</body>
</html>