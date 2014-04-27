<?php
    define("IN_MYBB", 1);
    require_once("forum/global.php"); // Change this if needed
    $tlimit = 5; // How many titles you want
            
    $query = $db->query("SELECT * FROM ".TABLE_PREFIX."threads ORDER BY `tid` DESC LIMIT $tlimit");
    
        $list = '';
        while($fetch = $db->fetch_array($query))
        {
            $list .= "<li><a class=\"forum_post\" href=\"forum/showthread.php?tid={$fetch['tid']}\" target=\"_blank\">".htmlspecialchars_uni($fetch['subject'])."</a><br>";
            $list .= "<span class=\"author\">By: <a href=\"http://www.hyakkiscorner.com/forum/member.php?action=profile&uid={$fetch['uid']}\">{$fetch['username']}</a></span></li>";
        }
    echo $list;
?>