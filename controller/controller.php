<?php
/**
*
* Participate extension for the phpBB Forum Software package.
* @copyright (c) 2015 ForumHulp.com
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace forumhulp\participate\controller;

use Symfony\Component\DependencyInjection\Container;

class controller
{
	protected $phpbb_container;
	protected $db;
	protected $template;
	protected $user;
	protected $helper;
	protected $request;
	protected $participate_table;

	/**
	* Constructor
	*/
	public function __construct(Container $phpbb_container, \phpbb\db\driver\driver_interface $db, \phpbb\template\template $template, \phpbb\user $user, \phpbb\request\request $request, \phpbb\controller\helper $helper, $participate_table)
	{
		$this->phpbb_container		= $phpbb_container;
		$this->db 					= $db;
		$this->template				= $template;
		$this->user					= $user;
		$this->request				= $request;
		$this->helper				= $helper;
		$this->participate_table	= $participate_table;
	}

	public function handle($name)
	{
		switch ($name)
		{
			default:
				$topicid = $this->request->variable('t', 0);

				if ($topicid)
				{
					$this->user->add_lang_ext('forumhulp/participate', 'participate');
					$sql = 'SELECT user_id, active FROM ' . $this->participate_table . ' WHERE topic_id = ' . $topicid . ' AND user_id = ' . $this->user->data['user_id'];
					$result = $this->db->sql_query($sql);
					$row = $this->db->sql_fetchrow($result);
					if (!$row['user_id'])
					{
						$sql = 'INSERT INTO ' . $this->participate_table . ' VALUES(' . $this->user->data['user_id'] . ', ' . $topicid . ', 1, ' . time() . ')';
						$row['active'] = 1;

					} else
					{
						$sql = 'UPDATE ' . $this->participate_table . ' 
								SET active = !active, post_time = ' . time() . ' WHERE topic_id = ' . $topicid . ' AND user_id = ' . $this->user->data['user_id'];
						$row['active'] = !$row['active'];
					}
					$this->db->sql_query($sql);

					$participants = '';
					$sql = 'SELECT u.username, u.user_colour, d.user_id, d.active
							FROM ' . $this->participate_table . ' AS d
							LEFT JOIN ' . USERS_TABLE . ' AS u ON (d.user_id = u.user_id)
							WHERE d.topic_id = ' . $topicid . '
							ORDER BY d.active DESC, d.post_time ASC';
					$result = $this->db->sql_query($sql);
					while ($row1 = $this->db->sql_fetchrow($result))
					{
						$participants .= (($participants == '') ? $this->user->lang['PARTICIPANTS'] . $this->user->lang['COLON'] . ' ': ', ') . '<span class="' . (($row1['active']) ? 'zwart' : 'grijs doorstreept') . '" style="color: #' . $row1['user_colour'] . ';">' . $row1['username'] . '</span>';
					}
					$info_url = '<a href="' . $this->helper->route('participate_controller', array('name' => 'index.html', 't' => 0)) . '" class="simpledialog"><i class="fa fa-info-circle" title="Extension Info"></i></a> <script>$("a.simpledialog").simpleDialog({opacity: 0.1,width: \'650px\',closeLabel: \'&times;\'});</script>';

					if ($this->request->is_ajax())
					{
						$json_response = new \phpbb\json_response();
						$json_response->send(array(
							'success'			=> true,
							'STATUS_CLASS'		=> ($row['active']) ? 'groen' : 'rood',
							'STATUS_TXT'		=> ($row['active']) ? $this->user->lang['STATUS_TXT_PARTICIPATE'] : $this->user->lang['STATUS_TXT_CANCEL_PARTICIPATE'],
							'KNOP_TXT'			=> ($row['active']) ? $this->user->lang['STATUS_TITLE_PARTICIPATE'] : $this->user->lang['STATUS_TITLE_CANCEL_PARTICIPATE'],
							'PARTICIPANTSBAR'	=> $info_url . $participants
						));
					}
					exit();
				} else
				{
					$this->user->add_lang_ext('forumhulp/participate', 'info_acp_participate');
					$this->user->add_lang('acp/common');
					$this->phpbb_container->get('forumhulp.helper')->detail('forumhulp/participate');
					$this->tpl_name = 'acp_ext_details';
					return $this->helper->render('acp_ext_details.html', 'detail');
				}
		}
	}
}
