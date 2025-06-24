<?php
class ModelExtensionPaymentHutko extends Model
{
	public function getMethod($address, $total)
	{
		$this->load->language('extension/payment/hutko');
		// Define allowed currencies for Hutko
		$allowed_currencies = array('UAH', 'USD', 'EUR', 'GBP', 'CZK');
		$current_currency_code = $this->session->data['currency'];

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('payment_hutko_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

		if ($this->config->get('payment_hutko_total') > 0 && $this->config->get('payment_hutko_total') > $total) {
			$status = false;
		} elseif (!$this->config->get('payment_hutko_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}
		if ($status) { // Only proceed if still active
			if (!in_array(strtoupper($current_currency_code), $allowed_currencies)) {
				$this->log->write('Hutko Payment: Disabled because current currency (' . $current_currency_code . ') is not in allowed list: ' . implode(', ', $allowed_currencies));
				$status = false;
			}
		}
		$method_data = array();

		if ($status && $this->config->get('payment_hutko_status')) {
			$title = $this->language->get('text_title');

			$method_data = array(
				'code'       => 'hutko',
				'title'      => $title,
				'terms'      => '',
				'sort_order' => $this->config->get('payment_hutko_sort_order')
			);
		}
		return $method_data;
	}

	public function addHutkoOrder($order_id, $hutko_transaction_ref)
	{
		$this->db->query("INSERT INTO `" . DB_PREFIX . "hutko_order` SET `order_id` = '" . (int)$order_id . "', `hutko_transaction_ref` = '" . $this->db->escape($hutko_transaction_ref) . "', `date_added` = NOW() ON DUPLICATE KEY UPDATE `hutko_transaction_ref` = '" . $this->db->escape($hutko_transaction_ref) . "', `date_added` = NOW()");
	}

	public function getHutkoOrder($order_id)
	{
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "hutko_order` WHERE `order_id` = '" . (int)$order_id . "'");
		return $query->row;
	}
}
