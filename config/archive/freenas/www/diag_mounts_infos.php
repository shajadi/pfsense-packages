<?php
/* $Id$ */
/* ========================================================================== */
/*
    diag_mounts_infos.php
    part of pfSense (http://www.pfSense.com)
    Copyright (C) 2006 Daniel S. Haischt <me@daniel.stefan.haischt.name>
    All rights reserved.

    Based on FreeNAS (http://www.freenas.org)
    Copyright (C) 2005-2006 Olivier Cochard-Labb� <olivier@freenas.org>.
    All rights reserved.

    Based on m0n0wall (http://m0n0.ch/wall)
    Copyright (C) 2003-2006 Manuel Kasper <mk@neon1.net>.
    All rights reserved.
                                                                              */
/* ========================================================================== */
/*
    Redistribution and use in source and binary forms, with or without
    modification, are permitted provided that the following conditions are met:

     1. Redistributions of source code must retain the above copyright notice,
        this list of conditions and the following disclaimer.

     2. Redistributions in binary form must reproduce the above copyright
        notice, this list of conditions and the following disclaimer in the
        documentation and/or other materials provided with the distribution.

    THIS SOFTWARE IS PROVIDED ``AS IS'' AND ANY EXPRESS OR IMPLIED WARRANTIES,
    INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY
    AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
    AUTHOR BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY,
    OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
    SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
    INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
    CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
    ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
    POSSIBILITY OF SUCH DAMAGE.
                                                                              */
/* ========================================================================== */

$pgtitle = array(gettext("Diagnostics"),
                 gettext("Mount Infos"));

require_once("freenas_config.inc");
require_once("guiconfig.inc");
require_once("freenas_guiconfig.inc");
require_once("freenas_functions.inc");

if (! empty($_POST))
{
  /* hash */
  unset($error_bucket);
  /* simple error list */
  unset($input_errors);

  if (is_array($error_bucket))
    foreach($error_bucket as $elem)
      $input_errors[] =& $elem["error"];
  
  /* if this is an AJAX caller then handle via JSON */
  if(isAjax() && is_array($error_bucket)) {
      input_errors2Ajax(NULL, $error_bucket);       
      exit;   
  }
}

include("head.inc");
/* put your custom HTML head content here        */
/* using some of the $pfSenseHead function calls */
echo $pfSenseHead->getHTML();

?>

<body link="#0000CC" vlink="#0000CC" alink="#0000CC" onload="<?= $jsevents["body"]["onload"] ?>">
<?php include("fbegin.inc"); ?>
<?php if ($input_errors) print_input_errors($input_errors); ?>
<?php if ($savemsg) print_info_box($savemsg); ?>

<form action="diag_mounts_infos.php" method="post" name="iform" id="iform">

<div id="inputerrors"></div>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td class="tabnavtbl">
<?php
  $tab_array = array();
  $tab_array[0] = array(gettext("Disks"),         false, "diag_disks_infos.php");
  $tab_array[1] = array(gettext("Partitions"),    false, "diag_part_infos.php");
  $tab_array[2] = array(gettext("SMART"),         false, "diag_smart_infos.php");
  $tab_array[3] = array(gettext("ataidle"),       false, "diag_ataidle_infos.php");
  $tab_array[4] = array(gettext("Space used"),    false, "diag_space_infos.php");
  $tab_array[5] = array(gettext("Mounts"),        true,  "diag_mounts_infos.php");
  $tab_array[6] = array(gettext("Software RAID"), false, "diag_raid_infos.php");
  $tab_array[7] = array(gettext("iSCSI"),         false, "diag_iscsi_infos.php");
  display_top_tabs($tab_array);
?>  
    </td>
  </tr>
  <tr>
    <td class="tabnavtbl">
<?php
  $tab_array = array();
  $tab_array[0] = array(gettext("Manage RAID"), false, "diag_ad_infos.php");
  display_top_tabs($tab_array);
?>  
    </td>
  </tr>
  <tr> 
    <td>
    <div id="mainarea">
    <table class="tabcont" width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td align="left" valign="top">
          <?php
            echo "<pre style=\"font-size: medium;\">";
            echo "<strong>List of mounted point:</strong><br />";
            exec("/sbin/mount",$mountrawdata);
            foreach ($mountrawdata as $line)
            {
              echo htmlspecialchars($line) . "<br />";
            }
            unset ($line);
            echo "</pre>";
          ?>
        </td>
      </tr>
    </table>
  </div>
  </td>
  </tr>
</table>
</form>
<?php include("fend.inc"); ?>
</body>
</html>
