<?php
include_once(sprintf("%s/NetSisUserUtil.php", dirname(__FILE__)));

if(!class_exists('NetSisPlugin'))
{
    abstract class NetSisPlugin
    {
		const ERROR = 0;
		const NOTICE = 1;

		public $msg_errors = array();
		public $msg_notices = array();

		public function __construct()
        {
			add_action('in_admin_footer', array(&$this, 'admin_notices'));
			add_action('netsis_form_wrap_begin', array(&$this, 'netsis_form_wrap_begin'));
			add_action('netsis_form_wrap_end', array(&$this, 'netsis_form_wrap_end'));
        }

		protected function IncludePage($plugin_dir_path)
		{
			global $submenu;

			$page_found = false;

			do_action('netsis_form_wrap_begin');

			try
			{
				foreach($submenu as $menu_item)
					foreach($menu_item as $item)
						if ($item[2] == $_GET['page']) {
							$page_found = true;
							include_once($plugin_dir_path.'templates/'.$_GET['page'].'.php');
							break 2;
						}
						
				if (!$page_found)
					throw new Exception('Página inválida.');
			}
			catch(Exception $e)
			{
				echo '<h2>'.__('Error').'</h2>';
				$this->msg_errors[] = $e->getMessage();
			}

			do_action('netsis_form_wrap_end');
		}

		public function netsis_form_wrap_begin()
		{
			echo '<div class="form wrap">';
		}

		public function netsis_form_wrap_end()
		{
			echo '</div>';
		}

		public function admin_notices()
		{
			foreach($this->msg_notices as $msg)
				self::ShowNoticeMessage($msg);

			foreach($this->msg_errors as $msg)
				self::ShowErrorMessage($msg);
		}

		public static function ShowNoticeMessage($msg)
		{
			self::ShowMessage($msg);
		}

		public static function ShowErrorMessage($msg)
		{
			self::ShowMessage($msg, self::ERROR);
		}

		public static function ShowMessage($msg, $type = self::NOTICE)
		{
			if (strpos($msg, '<p>') === false)
				$msg = '<p>'.$msg.'</p>';

			echo '<div class="message ';
			switch($type) {
				case self::ERROR:
					echo 'error';
					break;

				case self::NOTICE:
					echo 'updated';
					break;
			}
			echo '">'.$msg.'</div>';
		}
    }
}
?>