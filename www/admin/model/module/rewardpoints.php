<?php
class ModelModuleRewardPoints extends Model {

	public function getTotalPendingReminders() {
		if ($this->config->get('rewardpoints_email_status')) {
			$accepted_statuses = $this->config->get('rewardpoints_email_status');
		} else {
			$accepted_statuses = array('99999');
		}

		$days_interval = ($this->config->get('rewardpoints_email_days')) ? $this->config->get('rewardpoints_email_days') : 0;
		$check_date = ($this->config->get('rewardpoints_email_days')) ? 'date_modified' : 'date_added';

		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` WHERE product_review_reminder = '0' AND date_add(" . $check_date . ", INTERVAL " . $days_interval . " DAY) <= NOW() AND order_status_id IN (" . implode(',', $accepted_statuses) . ")");
		return $query->row['total'];
	}

	public function sendEmailReminders() {
		$total_dispatched = 0;
		$content = $this->config->get('rewardpoints_email_content');
		$lang = $this->config->get('config_language_id');
		$accepted_statuses = $this->config->get('rewardpoints_email_status');
		if (empty($accepted_statuses)) { return 0; }
		$check_date = ($this->config->get('rewardpoints_email_days')) ? 'date_modified' : 'date_added';
		$reminders = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order` WHERE product_review_reminder = '0' AND date_add(" . $check_date . ", INTERVAL " . $this->config->get('rewardpoints_email_days') . " DAY) <= NOW() AND order_status_id IN (" . implode(',', $accepted_statuses) . ")");

		if ($reminders->num_rows) {
			foreach ($reminders->rows as $reminder) {
				$subject = $content[$lang]['subject'];
				$subject = str_replace('{first_name}', $reminder['firstname'], $subject);
				$subject = str_replace('{last_name}', $reminder['lastname'], $subject);
				$subject = str_replace('{order_id}',  $reminder['order_id'], $subject);
				$subject = str_replace('{review_bonus}',  $this->config->get('rewardpoints_review_bonus'), $subject);
				$subject = str_replace('{review_limit}',  $this->config->get('rewardpoints_review_limit'), $subject);

				$message = $content[$lang]['content'];
				$message = str_replace('{first_name}', $reminder['firstname'], $message);
				$message = str_replace('{last_name}', $reminder['lastname'], $message);
				$message = str_replace('{order_id}',  $reminder['order_id'], $message);
				$message = str_replace('{review_bonus}',  $this->config->get('rewardpoints_review_bonus'), $message);
				$message = str_replace('{review_limit}',  $this->config->get('rewardpoints_review_limit'), $message);

				$mail = new Mail();
				$mail->protocol = $this->config->get('config_mail_protocol');
				$mail->parameter = $this->config->get('config_mail_parameter');
				$mail->hostname = $this->config->get('config_smtp_host');
				$mail->username = $this->config->get('config_smtp_username');
				$mail->password = $this->config->get('config_smtp_password');
				$mail->port = $this->config->get('config_smtp_port');
				$mail->timeout = $this->config->get('config_smtp_timeout');
				$mail->setTo($reminder['email']);
				$mail->setFrom($this->config->get('config_email'));
				$mail->setSender($this->config->get('config_name'));
				$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
				$mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
				//$mail->send();

				$this->db->query("UPDATE `" . DB_PREFIX . "order` SET product_review_reminder = '1' WHERE order_id = '" . $reminder['order_id'] . "'");
				$total_dispatched = $total_dispatched + 1;
			}
		}
		return $total_dispatched;
	}

	public function sendEmailTest() {
		$content = $this->config->get('rewardpoints_email_content');
		$lang = $this->config->get('config_language_id');

		$subject = $content[$lang]['subject'];
		$subject = str_replace('{first_name}', 'FirstNameTest', $subject);
		$subject = str_replace('{last_name}', 'LastNameTest', $subject);
		$subject = str_replace('{order_id}',  'OrderIDTest', $subject);
		$subject = str_replace('{review_bonus}',  $this->config->get('rewardpoints_review_bonus'), $subject);
		$subject = str_replace('{review_limit}',  $this->config->get('rewardpoints_review_limit'), $subject);

		$message = $content[$lang]['content'];
		$message = str_replace('{first_name}', 'FirstNameTest', $message);
		$message = str_replace('{last_name}', 'LastNameTest', $message);
		$message = str_replace('{order_id}',  'OrderIDTest', $message);
		$message = str_replace('{review_bonus}',  $this->config->get('rewardpoints_review_bonus'), $message);
		$message = str_replace('{review_limit}',  $this->config->get('rewardpoints_review_limit'), $message);

		$mail = new Mail();
		$mail->protocol = $this->config->get('config_mail_protocol');
		$mail->parameter = $this->config->get('config_mail_parameter');
		$mail->hostname = $this->config->get('config_smtp_host');
		$mail->username = $this->config->get('config_smtp_username');
		$mail->password = $this->config->get('config_smtp_password');
		$mail->port = $this->config->get('config_smtp_port');
		$mail->timeout = $this->config->get('config_smtp_timeout');
		$mail->setTo($this->config->get('rewardpoints_email_test'));
		$mail->setFrom($this->config->get('config_email'));
		$mail->setSender($this->config->get('config_name'));
		$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
		$mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
		$mail->send();
	}
}
?>