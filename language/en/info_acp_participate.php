<?php
/**
*
* @package Search Results
* @copyright (c) 2014 John Peskens (http://ForumHulp.com)
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
	'ACP_PARTICIPANTS'			=> 'Participate Forums',
	'ACP_PARTICIPANTS_EXPLAIN'	=> 'Select forums to participate. Get a nice list of participated members.',
	'FH_HELPER_NOTICE'		=> 'Forumhulp helper application does not exist!<br />Download <a href="https://github.com/ForumHulp/helper" target="_blank">forumhulp/helper</a> and copy the helper folder to your forumhulp extension folder.',
	'PARTICIPATE_NOTICE'		=> '<div class="phpinfo"><p class="entry">Config settings are in %1$s » %2$s » %3$s » %4$s.</p></div>',
));

// Description of extension
$lang = array_merge($lang, array(
	'DESCRIPTION_PAGE'		=> 'Description',
	'DESCRIPTION_NOTICE'	=> 'Extension note',
	'ext_details' => array(
		'details' => array(
			'DESCRIPTION_1'		=> 'Participate to events',
			'DESCRIPTION_2'		=> 'Switchable forums',
			'DESCRIPTION_3'		=> 'Memberlist above event-topic',
		),
		'note' => array(
			'NOTICE_1'			=> 'phpBB 3.2 ready'
		)
	)
));
