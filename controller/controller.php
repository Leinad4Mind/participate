<?php
/**
*
* Participate extension for the phpBB Forum Software package.
* @copyright (c) 2015 ForumHulp.com
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace forumhulp\participate\controller;

class controller
{
	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\user */
	protected $user;

	/** @var \phpbb\request\request */
	protected $request;

	/**
	* The database tables
	*
	* @var string
	*/
	protected $participate_table;

	/**
	* Constructor
	*
	* @param \phpbb\config\db_text               $config_text    DB text object
	* @param \phpbb\db\driver\driver_interface   $db             Database object
	* @param \phpbb\controller\helper            $helper         Controller helper object
	* @param \phpbb\request\request              $request        Request object
	* @param \phpbb\user                         $user           User object
	* @return \forumhulp\bex\controller\controller
	* @access public
	*/
	public function __construct(\phpbb\db\driver\driver_interface $db, \phpbb\template\template $template, \phpbb\user $user, \phpbb\request\request $request, $participate_table)
	{
		$this->db 					= $db;
		$this->template				= $template;
		$this->user					= $user;
		$this->request				= $request;
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

					if ($this->request->is_ajax())
					{
						$json_response = new \phpbb\json_response();
						$json_response->send(array(
							'success'		=> true,
							'STATUS_CLASS'	=> ($row['active']) ? 'groen' : 'rood',
							'STATUS_TXT'	=> ($row['active']) ? $this->user->lang['STATUS_TXT_PARTICIPATE'] : $this->user->lang['STATUS_TXT_CANCEL_PARTICIPATE'],
							'KNOP_TXT'		=> ($row['active']) ? $this->user->lang['STATUS_TITLE_PARTICIPATE'] : $this->user->lang['STATUS_TITLE_CANCEL_PARTICIPATE'],
						));
					}
				}
				exit();
		}
	}
}
