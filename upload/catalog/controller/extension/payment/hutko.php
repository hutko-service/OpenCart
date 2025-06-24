<?php
class ControllerExtensionPaymentHutko extends Controller
{
	private $order_separator = '#';
	private $checkout_url = 'https://pay.hutko.org/api/checkout/url/';

	public function index()
	{
		$this->load->language('extension/payment/hutko');
		$data['button_confirm'] = $this->language->get('button_confirm');
		$data['text_loading'] = $this->language->get('text_loading'); // For JS button state

		// URL for the form to POST to
		$data['action_redirect_to_gateway'] = $this->url->link('extension/payment/hutko/redirectToGateway', '', true);

		return $this->load->view('extension/payment/hutko', $data);
	}

	public function redirectToGateway()
	{
		$this->load->language('extension/payment/hutko');
		$this->load->model('checkout/order');

		if (!isset($this->session->data['order_id'])) {
			$this->logOC("Hutko redirectToGateway: No order_id in session.");
			$this->session->data['error'] = "Session expired or order ID missing."; // Generic error
			$this->response->redirect($this->url->link('checkout/failure', '', true));
			return;
		}

		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

		if (!$order_info) {
			$this->logOC("Hutko Error: Order info not found for order_id: " . $this->session->data['order_id']);
			$this->session->data['error'] = "Critical error: Order details not found.";
			$this->response->redirect($this->url->link('checkout/failure', '', true));
			return;
		}

		$this->model_checkout_order->addOrderHistory($order_info['order_id'], $this->config->get('payment_hutko_new_order_status_id'), $this->language->get('text_initiated_payment'), false);

		$requestData = $this->buildPaymentRequestDataOC($order_info);

		if (empty($requestData)) {
			$this->logOC("Hutko Error: Failed to build payment request data for order_id: " . $order_info['order_id']);
			$this->session->data['error'] = $this->language->get('error_payment_data_build');
			$this->response->redirect($this->url->link('checkout/failure', '', true));
			return;
		}
		// *** SAVE HUTKO TRANSACTION REFERENCE ***
		$this->load->model('extension/payment/hutko'); // Load our catalog model
		$this->model_extension_payment_hutko->addHutkoOrder($order_info['order_id'], $requestData['order_id']);
		$this->logOC("Hutko: Saved Hutko transaction ref '{$requestData['order_id']}' for OC order ID {$order_info['order_id']}.");
		// *** END SAVE ***
		$apiResponse = $this->sendAPICallOC($this->checkout_url, $requestData);

		if (isset($apiResponse['response']['response_status']) && $apiResponse['response']['response_status'] === 'success' && !empty($apiResponse['response']['checkout_url'])) {
			$comment = sprintf($this->language->get('text_redirecting_comment'), $requestData['order_id'], $apiResponse['response']['checkout_url']);
			$this->model_checkout_order->addOrderHistory($order_info['order_id'], $this->config->get('payment_hutko_new_order_status_id'), $comment, false);

			$this->response->redirect($apiResponse['response']['checkout_url']); // This redirect is fine now
		} else {
			$error_message = isset($apiResponse['response']['error_message']) ? $apiResponse['response']['error_message'] : $this->language->get('error_api_communication');
			$this->logOC("Hutko API Error (checkout_url) for order_id " . $order_info['order_id'] . ": " . $error_message . " | Response: " . json_encode($apiResponse));

			// Use a more generic failed status if available, or config_fraud_status_id
			$failed_status_id = $this->config->get('payment_hutko_declined_status_id') ? $this->config->get('payment_hutko_declined_status_id') : $this->config->get('config_fraud_status_id');
			$this->model_checkout_order->addOrderHistory($order_info['order_id'], $failed_status_id, 'Hutko API Error before redirect: ' . $error_message, false);

			$this->session->data['error'] = $this->language->get('error_api_communication') . (isset($apiResponse['response']['error_message']) ? (': ' . $apiResponse['response']['error_message']) : '');
			$this->response->redirect($this->url->link('checkout/failure', '', true));
		}
	}


	public function callback()
	{
		$this->load->language('extension/payment/hutko');
		$this->load->model('checkout/order');

		$callbackContent = json_decode(file_get_contents("php://input"), true);

		if ($this->config->get('payment_hutko_save_logs')) {
			$this->logOC("Hutko Callback received: " . json_encode($callbackContent));
		}

		if (!is_array($callbackContent) || empty($callbackContent)) {
			$this->logOC("Hutko Callback Error: Empty or invalid JSON payload.");
			http_response_code(400);
			echo "Empty request";
			exit;
		}

		if (!$this->validateResponseOC($callbackContent)) {
			$this->logOC("Hutko Callback Error: Invalid signature or merchant ID mismatch.");
			http_response_code(400);
			echo "Invalid signature";
			exit;
		}

		// Hutko's order_id is store_order_id#timestamp
		$hutko_order_id_parts = explode($this->order_separator, $callbackContent['order_id']);
		$order_id = (int)$hutko_order_id_parts[0];

		$order_info = $this->model_checkout_order->getOrder($order_id);

		if (!$order_info) {
			$this->logOC("Hutko Callback Error: Order not found for OC Order ID: " . $order_id . " (from Hutko ID: " . $callbackContent['order_id'] . ")");
			http_response_code(404);
			echo "Order not found";
			exit;
		}
		// *** VERIFY/SAVE HUTKO TRANSACTION REFERENCE FROM CALLBACK ***
		$this->load->model('extension/payment/hutko');
		$stored_hutko_order = $this->model_extension_payment_hutko->getHutkoOrder($order_id);

		if ($stored_hutko_order) {
			if ($stored_hutko_order['hutko_transaction_ref'] != $callbackContent['order_id']) {
				$this->logOC("Hutko Callback Warning: Mismatch for OC Order ID {$order_id}. Callback Hutko ID: {$callbackContent['order_id']}, Stored Hutko ID: {$stored_hutko_order['hutko_transaction_ref']}. Consider if update needed.");
				// Optionally update if callback is authoritative:
				// $this->model_extension_payment_hutko->addHutkoOrder($order_id, $hutko_order_id_from_callback);
			}
		} else {
			// If it was not saved during redirectToGateway (e.g., edge case, interruption), save it now from a valid callback.
			$this->logOC("Hutko Callback: Stored Hutko transaction ref not found for OC Order ID {$order_id}. Saving from callback: {$callbackContent['order_id']}.");
			$this->model_extension_payment_hutko->addHutkoOrder($order_id, $callbackContent['order_id']);
		}
		// *** END VERIFY/SAVE ***
		$order_status_callback = $callbackContent['order_status'] ?? 'unknown';
		$current_order_status_id = $order_info['order_status_id'];
		$comment_details = "Hutko Order ID: " . $callbackContent['order_id'] . ". Status: " . $order_status_callback . ". ";
		if (isset($callbackContent['rrn'])) $comment_details .= "RRN: " . $callbackContent['rrn'] . ". ";
		if (isset($callbackContent['approval_code'])) $comment_details .= "Approval Code: " . $callbackContent['approval_code'] . ". ";


		$notify_customer = true; // Usually true for final statuses

		switch ($order_status_callback) {
			case 'approved':
				// Ensure not already refunded or in a final error state
				if (isset($callbackContent['response_status']) && $callbackContent['response_status'] == 'success' && (!isset($callbackContent['reversal_amount']) || (int)$callbackContent['reversal_amount'] === 0)) {
					$target_status_id = (int)$this->config->get('payment_hutko_success_status_id');
					if ($current_order_status_id != $target_status_id) {
						$callbackAmount = $callbackContent['actual_amount'] ?? $callbackContent['amount'];
						$amountFloat = round($callbackAmount / 100, 2);
						$comment = $this->language->get('text_payment_approved') . " " . $this->currency->format($amountFloat, $callbackContent['currency'], '', false) . ". " . $comment_details;
						$this->model_checkout_order->addOrderHistory($order_id, $target_status_id, $comment, $notify_customer);
					}
					echo "OK"; // Hutko expects "OK" on success
				} else {
					$this->logOC("Hutko Callback: Approved status but response_status not success or reversal_amount present for order_id: " . $order_id);
					echo "Error: Approved but invalid details"; // Or a more generic OK to stop retries
				}
				break;
			case 'declined':
				$target_status_id = (int)$this->config->get('payment_hutko_declined_status_id');
				if ($current_order_status_id != $target_status_id) {
					$this->model_checkout_order->addOrderHistory($order_id, $target_status_id, $this->language->get('text_payment_declined') . $comment_details, $notify_customer);
				}
				echo "Order declined";
				break;
			case 'expired':
				$target_status_id = (int)$this->config->get('payment_hutko_expired_status_id');
				if ($current_order_status_id != $target_status_id) {
					$this->model_checkout_order->addOrderHistory($order_id, $target_status_id, $this->language->get('text_payment_expired') . $comment_details, $notify_customer);
				}
				echo "Order expired";
				break;
			case 'processing':
				// Potentially a specific "processing" status, or leave as is.
				echo "Order processing";
				break;
			default:
				$this->logOC("Hutko Callback: Unexpected order status '{$order_status_callback}' for OC Order ID: " . $order_id);
				echo "Unexpected status";
				break;
		}
		exit;
	}

	protected function buildPaymentRequestDataOC(array $order_info): array
	{
		$hutko_order_id = $order_info['order_id'] . $this->order_separator . time();
		$merchant_id = $this->config->get('payment_hutko_merchant_id');

		$description_parts = [
			$this->config->get('config_name'),
			$this->language->get('text_order'),
			$order_info['order_id'],
		];
		$order_description = implode(' ', array_filter($description_parts));
		$order_description = substr($order_description, 0, 254);

		$server_callback_url = $this->url->link('extension/payment/hutko/callback', '', true);
		$response_url = $this->url->link('checkout/success', '', true);
		$customer_email = $order_info['email'];
		$reservation_data_array = $this->buildReservationDataOC($order_info);
		$reservation_data_json = json_encode($reservation_data_array);
		$reservation_data_base64 = base64_encode($reservation_data_json);

		$products = $reservation_data_array['products'];
		$total_products = 0;
		foreach ($products as $product) {
			$total_products += $product['total_amount'];
		}
		$total = (float)$order_info['total'];
		$order_totals = $this->model_checkout_order->getOrderTotals($order_info['order_id']);
		$total_shipping_cost = 0;
		// we need update shipping cost only if shipping was not included in products array already
		if (!$this->config->get('payment_hutko_shipping_include')) {
			foreach ($order_totals as $total_line) {
				if ($total_line['code'] == 'shipping') {
					$total_shipping_cost = $this->currency->format($total_line['value'], $order_info['currency_code'], $order_info['currency_value'], false);
					break;
				}
			}
		}
		$total_discounts = $total - $total_shipping_cost - $total_products;
		if ($this->config->get('payment_hutko_include_discount_to_total')) {
			$amount = $total_products + $total_discounts;
		} else {
			$amount = $total_products;
		}


		$amount_int = (int)round($this->currency->format($amount, $order_info['currency_code'], $order_info['currency_value'], false) * 100);

		$data = [
			'order_id'            => $hutko_order_id,
			'merchant_id'         => $merchant_id,
			'order_desc'          => $order_description,
			'amount'              => $amount_int,
			'currency'            => $order_info['currency_code'],
			'server_callback_url' => $server_callback_url,
			'response_url'        => $response_url,
			'sender_email'        => $customer_email,
			'reservation_data'    => $reservation_data_base64,
		];
		$data['signature'] = $this->getSignatureOC($data);

		return $data;
	}

	protected function buildReservationDataOC(array $order_info): array
	{
		$customer_state = $order_info['payment_zone']; // OpenCart provides zone name

		// Ensure phone is available
		$phone = !empty($order_info['telephone']) ? $order_info['telephone'] : '';
		$account = $order_info['customer_id'] ? (string)$order_info['customer_id'] : 'guest';

		$data = [
			"cms_name"         => "OpenCart",
			"cms_version"      => VERSION,
			"shop_domain"      => preg_replace("(^https?://)", "", HTTPS_SERVER), // remove scheme
			"path"             => HTTPS_SERVER . $this->request->server['REQUEST_URI'], // current path
			"phonemobile"      => $phone,
			"customer_address" => $order_info['payment_address_1'] . (!empty($order_info['payment_address_2']) ? ' ' . $order_info['payment_address_2'] : ''),
			"customer_country" => $order_info['shipping_iso_code_2'],
			"customer_state"   => $customer_state,
			"customer_name"    => $order_info['payment_firstname'] . ' ' . $order_info['payment_lastname'],
			"customer_city"    => $order_info['payment_city'],
			"customer_zip"     => $order_info['payment_postcode'],
			"account"          => $account,
			"uuid"             => hash('sha256', HTTPS_SERVER . $this->config->get('config_encryption') . $account),
			"products"         => $this->getProductsOC($order_info['order_id'], $order_info),
		];
		return $data;
	}

	protected function getProductsOC(int $order_id, array $order_info): array
	{
		$this->load->model('checkout/order');
		$order_products = $this->model_checkout_order->getOrderProducts($order_id);
		$products_data = [];

		foreach ($order_products as $product) {
			// Price per unit with tax
			$unit_price_incl_tax = $this->currency->format($product['price'] + ($product['tax'] / $product['quantity']), $order_info['currency_code'], $order_info['currency_value'], false);
			// Total for this line item with tax
			$total_price_incl_tax = $this->currency->format($product['total'] + $product['tax'], $order_info['currency_code'], $order_info['currency_value'], false);

			$products_data[] = [
				"id"           => $product['product_id'] . '_' . ($product['order_option'] ?? '0'),
				"name"         => $product['name'] . ' ' . $product['model'],
				"price"        => round((float)$unit_price_incl_tax, 2),
				"total_amount" => round((float)$total_price_incl_tax, 2),
				"quantity"     => (int)$product['quantity'],
			];
		}

		// Handle shipping if enabled
		if ($this->config->get('payment_hutko_shipping_include')) {
			$order_totals = $this->model_checkout_order->getOrderTotals($order_id);
			$shipping_cost = 0;
			foreach ($order_totals as $total_line) {
				if ($total_line['code'] == 'shipping') {
					$shipping_cost = $this->currency->format($total_line['value'], $order_info['currency_code'], $order_info['currency_value'], false);
					break;
				}
			}
			if ($shipping_cost > 0) {
				$products_data[] = [
					"id"           => $this->config->get('payment_hutko_shipping_product_code') ?: 'SHIPPING_001',
					"name"         => $this->config->get('payment_hutko_shipping_product_name') ?: 'Shipping',
					"price"        => round((float)$shipping_cost, 2),
					"total_amount" => round((float)$shipping_cost, 2),
					"quantity"     => 1,
				];
			}
		}
		return $products_data;
	}

	protected function getSlugOC(string $text, bool $removeSpaces = false, bool $lowerCase = false): string
	{
		// 1. Transliterate non-ASCII characters to ASCII.
		$text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

		// 2. Remove any characters that are not alphanumeric or spaces.
		$text = preg_replace("/[^a-zA-Z0-9 ]/", "", $text);

		// 3. Trim leading and trailing spaces.
		$text = trim($text, ' ');

		// 4. Optionally replace spaces with hyphens.
		if ($removeSpaces) {
			$text = str_replace(' ', '-', $text);
		}

		// 5. Optionally convert the slug to lowercase.
		if ($lowerCase) {
			$text = strtolower($text);
		}

		// 6. Return the generated slug.
		return $text;
	}

	protected function getSignatureOC(array $data, bool $encoded = true): string
	{
		$password = $this->config->get('payment_hutko_secret_key');
		if (!$password || empty($password)) {
			$this->logOC('Hutko Error: Merchant secret not set for signature generation.');
			return '';
		}
		$filteredData = array_filter($data, function ($value) {
			return $value !== '' && $value !== null;
		});
		ksort($filteredData);
		$stringToHash = $password;
		foreach ($filteredData as $value) {
			$stringToHash .= '|' . $value;
		}
		if ($encoded) {
			return sha1($stringToHash);
		} else {
			return $stringToHash;
		}
	}

	protected function validateResponseOC(array $response): bool
	{
		if ((string)$this->config->get('payment_hutko_merchant_id') != (string)($response['merchant_id'] ?? '')) {
			$this->logOC("Hutko validateResponseOC: Merchant ID mismatch. Expected: " . $this->config->get('payment_hutko_merchant_id') . ", Received: " . ($response['merchant_id'] ?? 'NULL'));
			return false;
		}
		$responseSignature = $response['signature'] ?? '';
		unset($response['response_signature_string'], $response['signature']);

		$calculatedSignature = $this->getSignatureOC($response);

		if (!hash_equals($calculatedSignature, $responseSignature)) {
			$this->logOC("Hutko validateResponseOC: Signature mismatch. Calculated: " . $calculatedSignature . ", Received: " . $responseSignature . ". Data for calc: " . json_encode($response));
			if ($this->config->get('payment_hutko_save_logs')) { // Log string used for calc if sig fails
				$this->logOC("Hutko validateResponseOC: String for calc (before sha1): " . $this->getSignatureOC($response, false));
			}
			return false;
		}
		return true;
	}

	protected function sendAPICallOC(string $url, array $data, int $timeout = 60): array
	{
		// This is duplicated from admin controller, ideally in a shared library/trait
		if ($this->config->get('payment_hutko_save_logs')) {
			$this->logOC('Hutko API Request to ' . $url . ': ' . json_encode(['request' => $data]));
		}

		$requestPayload = ['request' => $this->sanitizeForApi($data)];
		$jsonPayload = json_encode($requestPayload, JSON_UNESCAPED_UNICODE);

		if ($jsonPayload === false) {
			$error_msg = 'Failed to encode request data to JSON: ' . json_last_error_msg();
			$this->logOC('Hutko API Error: ' . $error_msg);
			return ['response' => ['response_status' => 'failure', 'error_message' => $error_msg]];
		}

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonPayload);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json', 'Content-Length: ' . strlen($jsonPayload)]);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
		$response_body = curl_exec($ch);
		$curl_error = curl_error($ch);
		$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);

		if ($curl_error) {
			$error_msg = 'CURL Error: ' . $curl_error;
			$this->logOC('Hutko API CURL Error: ' . $error_msg . ' (HTTP Code: ' . $http_code . ')');
			return ['response' => ['response_status' => 'failure', 'error_message' => $error_msg, 'http_code' => $http_code]];
		}

		if ($this->config->get('payment_hutko_save_logs')) {
			$this->logOC('Hutko API Response from ' . $url . ': ' . $response_body);
		}

		$responseData = json_decode($response_body, true);
		if (json_last_error() !== JSON_ERROR_NONE) {
			$error_msg = 'Invalid JSON response from API: ' . json_last_error_msg();
			$this->logOC('Hutko API JSON Decode Error: ' . $error_msg . ' (Raw: ' . $response_body . ')');
			return ['response' => ['response_status' => 'failure', 'error_message' => $error_msg, 'raw_response' => $response_body]];
		}
		return $responseData;
	}

	protected function logOC(string $message): void
	{
		if ($this->config->get('payment_hutko_save_logs')) {
			$this->log->write('Hutko Payment: ' . $message);
		}
	}



	protected function sanitizeForApi(array $array): array
	{
		$result = [];
		foreach ($array as $key => $value) {
			$result[$key] = str_replace('|', '_', $value);
		}
		// Remove pipe symbols and potentially other problematic characters for this specific API
		return $result;
	}
}
