<?php echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>"; ?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML+RDFa 1.0//EN" "http://www.w3.org/MarkUp/DTD/xhtml-rdfa-1.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-us">
  <head>
    <title><?php if (isset($sPageTitle) && !empty($sPageTitle)) echo $sPageTitle . ' - '; ?>BNETDocs</title>
<?php if (isset($sPageAdditionalStyle) && !empty($sPageAdditionalStyle)) {
?>    <link rel="stylesheet" href="<?php echo $sPageAdditionalStyle; ?>" type="text/css" media="all" />
<?php }
?>    <link rel="stylesheet" href="<?php echo BNETDocs::fGetCurrentFullURL('/main.css', true); ?>" type="text/css" media="all" />
    <script type="application/javascript" src="<?php echo BNETDocs::fGetCurrentFullURL('/BNETDocs.js', true); ?>" />
    <link rel="alternate" href="<?php echo BNETDocs::fGetCurrentFullURL('/rss/news', true); ?>" type="application/rss+xml" title="BNETDocs News" />
    <meta name="description" content="Battle.net logon sequences, packets, information, and protocols reference site." />
    <meta name="keywords" content="battle.net, starcraft, warcraft, diablo, blizzard, logon sequences, packets, information, protocols, reference, programming, coding" />
<?php
    foreach ($aOpenGraphItems as $sKey => $sVal) { ?>
    <meta property="<?php echo $sKey; ?>" content="<?php echo $sVal; ?>" />
<?php } ?>
  </head>
  <body>
    <div id="header">
      Battle.net Documentation
    </div>
    <div id="container">
      <div class="sidebar" id="sidebar_left">
        <div>Portal</div>
        <a href="<?php echo BNETDocs::fGetCurrentFullURL('/', true); ?>">Home</a>
        <a href="<?php echo BNETDocs::fGetCurrentFullURL('/news', true); ?>">News</a>
        <div>Account Management</div>
        <a href="<?php echo BNETDocs::fGetCurrentFullURL('/user/login', true); ?>">Account Login</a>
        <a href="<?php echo BNETDocs::fGetCurrentFullURL('/user/register', true); ?>">Create Account</a>
        <div>Documents</div>
        <a href="<?php echo BNETDocs::fGetCurrentFullURL('/documents/search', true); ?>">Search</a>
        <a href="<?php echo BNETDocs::fGetCurrentFullURL('/documents/popular', true); ?>">Popular</a>
        <div>Packets</div>
        <a href="<?php echo BNETDocs::fGetCurrentFullURL('/packets/search', true); ?>">Search</a>
        <a href="<?php echo BNETDocs::fGetCurrentFullURL('/packets/popular', true); ?>">Popular</a>
      </div>
      <div id="content">
