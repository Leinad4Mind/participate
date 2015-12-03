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
	'PARTICIPANTS'						=> 'Participants for this event',
	'NO_PARTICIPANTS'					=> 'No Participants for this event',

	'STATUS_TXT_NOT_PARTICIPATE'		=> 'You have not signed up.',
	'STATUS_TXT_PARTICIPATE'			=> 'You have signed up.',
	'STATUS_TXT_CANCEL_PARTICIPATE'		=> 'You have successfully unsubscribed.',

	'STATUS_TITLE_NOT_PARTICIPATE'		=> 'Sign me in directly.',
	'STATUS_TITLE_PARTICIPATE'			=> 'I unsubscribe again.',
	'STATUS_TITLE_CANCEL_PARTICIPATE'	=> 'Sign me in again.'
));
