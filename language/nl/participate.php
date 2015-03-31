<?php
/**
*
* Participate extension for the phpBB Forum Software package.
* @copyright (c) 2015 ForumHulp.com
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

$lang = array_merge($lang, array(
	'ACP_PARTICIPANTS_TITLE'	=> 'Deelnemers Forums',
	
	'PARTICIPANTS'						=> 'Deelnemers',
	'PARTICIPANTS_EXPLAIN'				=> 'Selecteer de forums waarbij leden zich kunnen aanmelden bij topics. Zo krijg je een mooie lijst van aan- en afgemelde leden.',
	'NO_PARTICIPANTS'					=> 'Er zijn nog geen deelnemers',
	
	'STATUS_TXT_NOT_PARTICIPATE'		=> 'U heeft zich nog niet opgegeven.',
	'STATUS_TXT_PARTICIPATE'			=> 'U heeft zich opgegeven.',
	'STATUS_TXT_CANCEL_PARTICIPATE'		=> 'U heeft zich afgemeld.',
	
	'STATUS_TITLE_NOT_PARTICIPATE'		=> 'Ik meld mij direct aan.',
	'STATUS_TITLE_PARTICIPATE'			=> 'Ik meld mij weer af.',
	'STATUS_TITLE_CANCEL_PARTICIPATE'	=> 'Ik meld mij toch wéér aan.'
));
