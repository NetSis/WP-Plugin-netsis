<?php
include_once(sprintf("%s/../../../../wp-load.php", dirname(__FILE__)));
include_once(sprintf("%s/../../../../wp-includes/pluggable.php", dirname(__FILE__)));

class NetSisUserUtil
{
	const UserRole_Administrator = 10;
	const UserRole_Editor = 20;
	const UserRole_Author = 30;
	const UserRole_Contributor = 40;
	const UserRole_Subscriber = 50;

	public static function GetCurrentUserRole()
	{
		return (is_user_logged_in()) ? NetSisUserUtil::GetUserRole(wp_get_current_user()) : null;
	}

	public static function GetUserRole($user)
	{
		if (user_can($user, 'manage_options'))
			return NetSisUserUtil::UserRole_Administrator;
		else if (user_can($user, 'edit_others_posts'))
			return NetSisUserUtil::UserRole_Editor;
		else if (user_can($user, 'publish_posts'))
			return NetSisUserUtil::UserRole_Author;
		else if (user_can($user, 'edit_posts'))
			return NetSisUserUtil::UserRole_Contributor;
		else
			return NetSisUserUtil::UserRole_Subscriber;
	}

	public static function CurrentUserCanActLike($user_role)
	{
		if ((($user_role == NetSisUserUtil::UserRole_Administrator) && (current_user_can('manage_options')))
			|| (($user_role == NetSisUserUtil::UserRole_Editor) && (current_user_can('edit_others_posts')))
			|| (($user_role == NetSisUserUtil::UserRole_Author) && (current_user_can('publish_posts')))
			|| (($user_role == NetSisUserUtil::UserRole_Contributor) && (current_user_can('edit_posts')))
			|| (($user_role == NetSisUserUtil::UserRole_Subscriber) && (current_user_can('read')))
			)
			return true;
		else
			return false;
	}

	public static function GetKeyPermission($user_role)
	{
		if (is_user_logged_in())
		{
			if ($user_role == NetSisUserUtil::UserRole_Administrator)
				return 'manage_options';
			else if ($user_role == NetSisUserUtil::UserRole_Editor)
				return 'edit_others_posts';
			else if ($user_role == NetSisUserUtil::UserRole_Author)
				return 'publish_posts';
			else if ($user_role == NetSisUserUtil::UserRole_Contributor)
				return 'edit_posts';
			else
				return NetSisUserUtil::UserRole_Subscriber;
		}
		else
			return null;
	}
}
?>