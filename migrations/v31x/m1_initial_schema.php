<?php
/**
*
* Participate extension for the phpBB Forum Software package.
* @copyright (c) 2015 ForumHulp.com
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace forumhulp\participate\migrations\v31x;

/**
* Migration stage 1: Initial schema changes to the database
*/
class m1_initial_schema extends \phpbb\db\migration\migration
{
	/**
	* Add the table.
	*
	* @return array Array of table schema
	* @access public
	*/
	public function update_schema()
	{
		return array(
			'add_tables'	=> array(
			$this->table_prefix . 'participate' => array(
				'COLUMNS'		=> array(
					'user_id'	=> array('UINT:11', 0),
					'topic_id'	=> array('UINT:11', 0),
					'active'	=> array('BOOL', 1),
					'post_time' => array('UINT:11', 0),
				),
				'KEYS'			=> array(
					'id'		=> array('UNIQUE', array('user_id', 'topic_id')))
				)
			)
		);
	}

	public function update_data()
	{
		return array(
			array('config.add', array('participate_forum_ids', '')),
		);
	}

	/**
	* Drop the table.
	*
	* @return array Array of table schema
	* @access public
	*/
	public function revert_schema()
	{
		return array(
			'drop_tables' => array(
				$this->table_prefix . 'participate')
		);
	}
}
