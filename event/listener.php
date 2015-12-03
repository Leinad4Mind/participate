<?php
/**
*
* Participate extension for the phpBB Forum Software package.
* @copyright (c) 2015 ForumHulp.com
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
* Inspired by Erkelens
*
*/

namespace forumhulp\participate\event;

/**
* @ignore
*/
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
* Event listener
*/
class listener implements EventSubscriberInterface
{
	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\controller\helper */
	protected $controller_helper;

	/** @var \phpbb\request\request */
	protected $request;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\user */
	protected $user;

	/**
	* The database tables
	*
	* @var string
	*/
	protected $participate_table;

	public function __construct(\phpbb\db\driver\driver_interface $db, \phpbb\config\config $config, \phpbb\controller\helper $controller_helper, \phpbb\request\request $request, \phpbb\template\template $template, \phpbb\user $user, $participate_table)
	{
		$this->db						= $db;
		$this->config					= $config;
		$this->controller_helper		= $controller_helper;
		$this->request					= $request;
		$this->template					= $template;
		$this->user						= $user;
		$this->participate_table		= $participate_table;
	}

	static public function getSubscribedEvents()
	{
		return array(
			'core.viewtopic_modify_post_data'	=> 'participate',
			'core.acp_board_config_edit_add'	=> 'add_config'
		);
	}

	public function participate($event)
	{
		$this->user->add_lang_ext('forumhulp/participate', 'participate');
		$forum_selected = explode(',', $this->config['participate_forum_ids']);

		$post_id = $event['rowset']['post_id'];
		$data = $event['topic_data'];
		
		if (in_array($data['forum_id'], $forum_selected) && $post_id = $data['topic_first_post_id'])
		{
			$sql = 'SELECT active FROM ' . $this->participate_table . ' WHERE user_id = ' . $this->user->data['user_id'] . ' AND topic_id = ' . $data['topic_id'];
			$result = $this->db->sql_query($sql);
			$row = $this->db->sql_fetchrow($result);

			$this->template->assign_vars(array(
				'S_PARTICIPATE'	=> ($post_id == $data['topic_first_post_id']) ? true : false,
				'STATUS_CLASS'	=> (!$row) ? 'grijs' : (($row['active']) ? 'groen' : 'rood'),
				'STATUS_TXT'	=> (!$row) ? $this->user->lang['STATUS_TXT_NOT_PARTICIPATE'] :
									(($row['active']) ? $this->user->lang['STATUS_TXT_PARTICIPATE'] : $this->user->lang['STATUS_TXT_CANCEL_PARTICIPATE']),
				'BUTTON_TXT'	=> (!$row) ? $this->user->lang['STATUS_TITLE_NOT_PARTICIPATE'] :
									(($row['active']) ? $this->user->lang['STATUS_TITLE_PARTICIPATE'] : $this->user->lang['STATUS_TITLE_CANCEL_PARTICIPATE']),
				'STATUS_URL'	=> $this->controller_helper->route('participate_controller', array('name' => 'index.html', 't' => $data['topic_id'])),
				'INFO_URL'		=> '<a href="' . $this->controller_helper->route('participate_controller', array('name' => 'index.html', 't' => 0)) . '" class="simpledialog"><i class="fa fa-info-circle" title="Extension Info"></i></a>'
			));

			$sql = 'SELECT u.username, u.user_colour, d.user_id, d.active
					FROM ' . $this->participate_table . ' AS d
					LEFT JOIN ' . USERS_TABLE . ' AS u ON (d.user_id = u.user_id)
					WHERE d.topic_id = ' . $data['topic_id']. '
					ORDER BY d.active DESC, d.post_time ASC';
			$result = $this->db->sql_query($sql);
			while ($row = $this->db->sql_fetchrow($result))
			{
				$this->template->assign_block_vars('participants', array(
					'USERNAME' 		=> $row['username'],
					'USERCOLOUR' 	=> $row['user_colour'],
					'USERID' 		=> $row['user_id'],
					'CLASS'			=> ($row['active']) ? 'zwart' : 'grijs doorstreept'
				));
			}
		}
	}

	public function add_config($event)
	{
		if($event['mode'] == 'settings')
		{
			if ($this->request->is_set_post('submit'))
			{
				$$new_vars = $this->request->variable('participate_forum_ids', array('' => ''), true);
				$this->config->set('participate_forum_ids', implode(',' , $$new_vars));
			}
			
			$this->user->add_lang_ext('forumhulp/participate', 'participate');
			$display_vars = $event['display_vars'];
			/* We add a new legend, but we need to search for the last legend instead of hard-coding */
			$submit_key = array_search('ACP_SUBMIT_CHANGES', $display_vars['vars']);
			$submit_legend_number = substr($submit_key, 6);
			$display_vars['vars']['legend'.$submit_legend_number] = 'ACP_PARTICIPANTS';
			$new_vars = array(
				'participate_forum_ids' => array('lang' => 'ACP_PARTICIPANTS', 'validate' => 'string', 'type' => 'custom', 'function' => __NAMESPACE__.'\listener::forums_select', 'explain' => true),
				'legend'.($submit_legend_number + 1)	=> 'ACP_SUBMIT_CHANGES',
			);
			$display_vars['vars'] = phpbb_insert_config_array($display_vars['vars'], $new_vars, array('after' => $submit_key));
			$event['display_vars'] = $display_vars;
		}
	}

	static function forums_select($value, $key)
	{
		global $user, $config;

		$forum_list = make_forum_select(false, false, true, true, true, false, true);
		$forum_selected = explode(',', $config['participate_forum_ids']);

		// Build forum options
		$s_forum_options = '<select id="' . $key . '" name="' . $key . '[]" multiple="multiple">';
		foreach ($forum_list as $f_id => $f_row)
		{
			$s_forum_options .= '<option value="' . $f_id . '"' . ((in_array($f_id, $forum_selected)) ? ' selected="selected"' : '') . (($f_row['disabled']) ? ' disabled="disabled" class="disabled-option"' : '') . '>' . $f_row['padding'] . $f_row['forum_name'] . '</option>';
		}
		$s_forum_options .= '</select>';

		return $s_forum_options;
	}
}
