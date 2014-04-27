<?php
/**
 * MyBB 1.6
 * Copyright 2010 MyBB Group, All Rights Reserved
 *
 * Website: http://mybb.com
 * License: http://mybb.com/about/license
 *
 * $Id$
 */

define("IN_MYBB", 1);
define('THIS_SCRIPT', 'forumdisplay.php');

$templatelist = "forumdisplay,forumdisplay_thread,forumbit_depth1_cat,forumbit_depth1_forum,forumbit_depth2_cat,forumbit_depth2_forum,forumdisplay_subforums,forumdisplay_threadlist,forumdisplay_moderatedby,forumdisplay_newthread,forumdisplay_searchforum,forumdisplay_orderarrow,forumdisplay_thread_rating,forumdisplay_threadlist_rating,forumdisplay_threadlist_sortrating,forumbit_moderators,forumbit_subforums,forumbit_depth2_forum_lastpost";
$templatelist .= ",forumbit_depth1_forum_lastpost,forumdisplay_thread_multipage_page,forumdisplay_thread_multipage,forumdisplay_thread_multipage_more";
$templatelist .= ",multipage_prevpage,multipage_nextpage,multipage_page_current,multipage_page,multipage_start,multipage_end,multipage";
$templatelist .= ",forumjump_advanced,forumjump_special,forumjump_bit,forumdisplay_password_wrongpass,forumdisplay_password";
$templatelist .= ",forumdisplay_usersbrowsing_user,forumdisplay_usersbrowsing,forumdisplay_inlinemoderation,forumdisplay_thread_modbit,forumdisplay_inlinemoderation_col,forumdisplay_inlinemoderation_selectall,forumdisplay_threadlist_clearpass";
$templatelist .= ",forumdisplay_announcements_announcement,forumdisplay_announcements,forumdisplay_threads_sep,forumbit_depth3_statusicon,forumbit_depth3,forumdisplay_sticky_sep,forumdisplay_thread_attachment_count,forumdisplay_threadlist_inlineedit_js,forumdisplay_rssdiscovery,forumdisplay_announcement_rating,forumdisplay_announcements_announcement_modbit,forumdisplay_rules,forumdisplay_rules_link,forumdisplay_thread_gotounread,forumdisplay_nothreads,forumdisplay_inlinemoderation_custom_tool,forumdisplay_inlinemoderation_custom";
require_once "./global.php";
require_once MYBB_ROOT."inc/functions_post.php";
require_once MYBB_ROOT."inc/functions_forumlist.php";
require_once MYBB_ROOT."inc/class_parser.php";
$parser = new postParser;

$orderarrow = $sortsel = array('rating' => '', 'subject' => '', 'starter' => '', 'started' => '', 'replies' => '', 'views' => '');
$ordersel = array('asc' => '', 'desc' => '');
$datecutsel = array(1 => '', 5 => '', 10 => '', 20 => '', 50 => '', 75 => '', 100 => '', 365 => '', 9999 => '');
$rules = '';

// Load global language phrases
$lang->load("forumdisplay");

$plugins->run_hooks("forumdisplay_start");

$fid = intval($mybb->input['fid']);
if($fid < 0)
{
	switch($fid)
	{
		case "-1":
			$location = "index.php";
			break;
		case "-2":
			$location = "search.php";
			break;
		case "-3":
			$location = "usercp.php";
			break;
		case "-4":
			$location = "private.php";
			break;
		case "-5":
			$location = "online.php";
			break;
	}
	if($location)
	{
		header("Location: ".$location);
		exit;
	}
}

// Get forum info
$foruminfo = get_forum($fid);
if(!$foruminfo)
{
	error($lang->error_invalidforum);
}

$archive_url = build_archive_link("forum", $fid);

$currentitem = $fid;
build_forum_breadcrumb($fid);
$parentlist = $foruminfo['parentlist'];

// To validate, turn & to &amp; but support unicode
$foruminfo['name'] = preg_replace("#&(?!\#[0-9]+;)#si", "&amp;", $foruminfo['name']);

$forumpermissions = forum_permissions();
$fpermissions = $forumpermissions[$fid];

if($fpermissions['canview'] != 1)
{
	error_no_permission();
}

if($mybb->user['uid'] == 0)
{
	// Cookie'd forum read time
	$forumsread = my_unserialize($mybb->cookies['mybb']['forumread']);

 	if(is_array($forumsread) && empty($forumsread))
 	{
 		if($mybb->cookies['mybb']['readallforums'])
		{
			$forumsread[$fid] = $mybb->cookies['mybb']['lastvisit'];
		}
		else
		{
 			$forumsread = array();
		}
 	}

	$query = $db->simple_select("forums", "*", "active != 0", array("order_by" => "pid, disporder"));
}
else
{
	// Build a forum cache from the database
	$query = $db->query("
		SELECT f.*, fr.dateline AS lastread
		FROM ".TABLE_PREFIX."forums f
		LEFT JOIN ".TABLE_PREFIX."forumsread fr ON (fr.fid=f.fid AND fr.uid='{$mybb->user['uid']}')
		WHERE f.active != 0
		ORDER BY pid, disporder
	");
}

while($forum = $db->fetch_array($query))
{
	if($mybb->user['uid'] == 0 && $forumsread[$forum['fid']])
	{
		$forum['lastread'] = $forumsread[$forum['fid']];
	}

	$fcache[$forum['pid']][$forum['disporder']][$forum['fid']] = $forum;
}

// Get the forum moderators if the setting is enabled.
if($mybb->settings['modlist'] != 0)
{
	$moderatorcache = $cache->read("moderators");
}

$bgcolor = "trow1";
if($mybb->settings['subforumsindex'] != 0)
{
	$showdepth = 3;
}
else
{
	$showdepth = 2;
}

$subforums = '';
$child_forums = build_forumbits($fid, 2);
$forums = $child_forums['forum_list'];

if($forums)
{
	$lang->sub_forums_in = $lang->sprintf($lang->sub_forums_in, $foruminfo['name']);
	eval("\$subforums = \"".$templates->get("forumdisplay_subforums")."\";");
}

$excols = "forumdisplay";

// Password protected forums
check_forum_password($foruminfo['fid']);

if($foruminfo['linkto'])
{
	header("Location: {$foruminfo['linkto']}");
	exit;
}

// Make forum jump...
if($mybb->settings['enableforumjump'] != 0)
{
	$forumjump = build_forum_jump("", $fid, 1);
}

if($foruminfo['type'] == "f" && $foruminfo['open'] != 0)
{
	eval("\$newthread = \"".$templates->get("forumdisplay_newthread")."\";");
}

if($fpermissions['cansearch'] != 0 && $foruminfo['type'] == "f")
{
	eval("\$searchforum = \"".$templates->get("forumdisplay_searchforum")."\";");
}

// Gather forum stats
$has_announcements = $has_modtools = false;
$forum_stats = $cache->read("forumsdisplay");

if(is_array($forum_stats))
{
	if(!empty($forum_stats[-1]['modtools']) || !empty($forum_stats[$fid]['modtools']))
	{
		// Mod tools are specific to forums, not parents
		$has_modtools = true;
	}

	if(!empty($forum_stats[-1]['announcements']) || !empty($forum_stats[$fid]['announcements']))
	{
		// Global or forum-specific announcements
		$has_announcements = true;
	}
}

$done_moderators = array(
	"users" => array(),
	"groups" => array()
);

$moderators = '';
$parentlistexploded = explode(",", $parentlist);

foreach($parentlistexploded as $mfid)
{
	// This forum has moderators
	if(is_array($moderatorcache[$mfid]))
	{
		// Fetch each moderator from the cache and format it, appending it to the list
		foreach($moderatorcache[$mfid] as $modtype)
		{
			foreach($modtype as $moderator)
			{
				if($moderator['isgroup'])
				{
					if(in_array($moderator['id'], $done_moderators['groups']))
					{
						continue;
					}
					$moderators .= $comma.htmlspecialchars_uni($moderator['title']);
					$done_moderators['groups'][] = $moderator['id'];
				}
				else
				{
					if(in_array($moderator['id'], $done_moderators['users']))
					{
						continue;
					}
					$moderators .= "{$comma}<a href=\"".get_profile_link($moderator['id'])."\">".format_name(htmlspecialchars_uni($moderator['username']), $moderator['usergroup'], $moderator['displaygroup'])."</a>";
					$done_moderators['users'][] = $moderator['id'];
				}
				$comma = $lang->comma;
			}
		}
	}

	if(!empty($forum_stats[$mfid]['announcements']))
	{
		$has_announcements = true;
	}
}
$comma = '';

// If we have a moderators list, load the template
if($moderators)
{
	eval("\$moderatedby = \"".$templates->get("forumdisplay_moderatedby")."\";");
}
else
{
	$moderatedby = '';
}

// Get the users browsing this forum.
if($mybb->settings['browsingthisforum'] != 0)
{
	$timecut = TIME_NOW - $mybb->settings['wolcutoff'];

	$comma = '';
	$guestcount = 0;
	$membercount = 0;
	$inviscount = 0;
	$onlinemembers = '';
	$doneusers = array();

	$query = $db->query("
		SELECT s.ip, s.uid, u.username, s.time, u.invisible, u.usergroup, u.usergroup, u.displaygroup
		FROM ".TABLE_PREFIX."sessions s
		LEFT JOIN ".TABLE_PREFIX."users u ON (s.uid=u.uid)
		WHERE s.time > '$timecut' AND location1='$fid' AND nopermission != 1
		ORDER BY u.username ASC, s.time DESC
	");

	while($user = $db->fetch_array($query))
	{
		if($user['uid'] == 0)
		{
			++$guestcount;
		}
		else
		{
			if(empty($doneusers[$user['uid']]) || $doneusers[$user['uid']] < $user['time'])
			{
				$doneusers[$user['uid']] = $user['time'];
				++$membercount;
				if($user['invisible'] == 1)
				{
					$invisiblemark = "*";
					++$inviscount;
				}
				else
				{
					$invisiblemark = '';
				}
				
				if($user['invisible'] != 1 || $mybb->usergroup['canviewwolinvis'] == 1 || $user['uid'] == $mybb->user['uid'])
				{
					$user['username'] = format_name($user['username'], $user['usergroup'], $user['displaygroup']);
					$user['profilelink'] = build_profile_link($user['username'], $user['uid']);
					eval("\$onlinemembers .= \"".$templates->get("forumdisplay_usersbrowsing_user", 1, 0)."\";");
					$comma = $lang->comma;
				}
			}
		}
	}

	$guestsonline = '';
	if($guestcount)
	{
		$guestsonline = $lang->sprintf($lang->users_browsing_forum_guests, $guestcount);
	}

	$onlinesep = '';
	if($guestcount && $onlinemembers)
	{
		$onlinesep = $lang->comma;
	}
	
	$invisonline = '';
	if($inviscount && $mybb->usergroup['canviewwolinvis'] != 1 && ($inviscount != 1 && $mybb->user['invisible'] != 1))
	{
		$invisonline = $lang->sprintf($lang->users_browsing_forum_invis, $inviscount);
	}

	$onlinesep2 = '';
	if($invisonline != '' && $guestcount)
	{
		$onlinesep2 = $lang->comma;
	}
	eval("\$usersbrowsing = \"".$templates->get("forumdisplay_usersbrowsing")."\";");
}

// Do we have any forum rules to show for this forum?
$forumrules = '';
if($foruminfo['rulestype'] != 0 && $foruminfo['rules'])
{
	if(!$foruminfo['rulestitle'])
	{
		$foruminfo['rulestitle'] = $lang->sprintf($lang->forum_rules, $foruminfo['name']);
	}
	
	$rules_parser = array(
		"allow_html" => 1,
		"allow_mycode" => 1,
		"allow_smilies" => 1,
		"allow_imgcode" => 1
	);

	$foruminfo['rules'] = $parser->parse_message($foruminfo['rules'], $rules_parser);
	if($foruminfo['rulestype'] == 1 || $foruminfo['rulestype'] == 3)
	{
		eval("\$rules = \"".$templates->get("forumdisplay_rules")."\";");
	}
	else if($foruminfo['rulestype'] == 2)
	{
		eval("\$rules = \"".$templates->get("forumdisplay_rules_link")."\";");
	}
}

$bgcolor = "trow1";

// Set here to fetch only approved topics (and then below for a moderator we change this).
$visibleonly = "AND visible='1'";
$tvisibleonly = "AND t.visible='1'";

// Check if the active user is a moderator and get the inline moderation tools.
if(is_moderator($fid))
{
	eval("\$inlinemodcol = \"".$templates->get("forumdisplay_inlinemoderation_col")."\";");
	$ismod = true;
	$inlinecount = "0";
	$inlinecookie = "inlinemod_forum".$fid;
	$visibleonly = " AND (visible='1' OR visible='0')";
	$tvisibleonly = " AND (t.visible='1' OR t.visible='0')";
}
else
{
	$inlinemod = '';
	$ismod = false;
}

if(is_moderator($fid, "caneditposts") || $fpermissions['caneditposts'] == 1)
{
	$can_edit_titles = 1;
}
else
{
	$can_edit_titles = 0;
}

unset($rating);

// Pick out some sorting options.
// First, the date cut for the threads.
$datecut = 0;
if(empty($mybb->input['datecut']))
{
	// If the user manually set a date cut, use it.
	if($mybb->user['daysprune'])
	{
		$datecut = $mybb->user['daysprune'];
	}
	else
	{
		// If the forum has a non-default date cut, use it.
		if(!empty($foruminfo['defaultdatecut']))
		{
			$datecut = $foruminfo['defaultdatecut'];
		}
	}
}
// If there was a manual date cut override, use it.
else
{
	$datecut = intval($mybb->input['datecut']);
}

$datecut = intval($datecut);
$datecutsel[$datecut] = "selected=\"selected\"";
if($datecut > 0 && $datecut != 9999)
{
	$checkdate = TIME_NOW - ($datecut * 86400);
	$datecutsql = "AND (lastpost >= '$checkdate' OR sticky = '1')";
	$datecutsql2 = "AND (t.lastpost >= '$checkdate' OR t.sticky = '1')";
}
else
{
	$datecutsql = '';
	$datecutsql2 = '';
}

// Pick the sort order.
if(!isset($mybb->input['order']) && !empty($foruminfo['defaultsortorder']))
{
	$mybb->input['order'] = $foruminfo['defaultsortorder'];
}

if(!empty($mybb->input['order']))
{
	$mybb->input['order'] = htmlspecialchars_uni($mybb->input['order']);
}
else
{
	$mybb->input['order'] = '';
}

switch(my_strtolower($mybb->input['order']))
{
	case "asc":
		$sortordernow = "asc";
        $ordersel['asc'] = "selected=\"selected\"";
		$oppsort = $lang->desc;
		$oppsortnext = "desc";
		break;
	default:
        $sortordernow = "desc";
		$ordersel['desc'] = "selected=\"selected\"";
        $oppsort = $lang->asc;
		$oppsortnext = "asc";
		break;
}

// Sort by which field?
if(!isset($mybb->input['sortby']) && !empty($foruminfo['defaultsortby']))
{
	$mybb->input['sortby'] = $foruminfo['defaultsortby'];
}

$t = "t.";
$sortfield2 = '';

if(!empty($mybb->input['sortby']))
{
	$sortby = htmlspecialchars_uni($mybb->input['sortby']);
}
else
{
	$mybb->input['sortby'] = '';
}

switch($mybb->input['sortby'])
{
	case "subject":
		$sortfield = "subject";
		break;
	case "replies":
		$sortfield = "replies";
		break;
	case "views":
		$sortfield = "views";
		break;
	case "starter":
		$sortfield = "username";
		break;
	case "rating":
		$t = "";
		$sortfield = "averagerating";
		$sortfield2 = ", t.totalratings DESC";
		break;
	case "started":
		$sortfield = "dateline";
		break;
	default:
		$sortby = "lastpost";
		$sortfield = "lastpost";
		$mybb->input['sortby'] = "lastpost";
		break;
}

$sortsel['rating'] = ''; // Needs to be initialized in order to speed-up things. Fixes #2031
$sortsel[$mybb->input['sortby']] =