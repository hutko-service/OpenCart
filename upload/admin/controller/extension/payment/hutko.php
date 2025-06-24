<?php
class ControllerExtensionPaymentHutko extends Controller
{
    private $error = array();
    private $order_separator = '#';
    private $checkout_url = 'https://pay.hutko.org/api/checkout/url/';
    private $refund_url = 'https://pay.hutko.org/api/reverse/order_id';
    private $status_url = 'https://pay.hutko.org/api/status/order_id';

    public function index()
    {


        $this->load->language('extension/payment/hutko');
        $this->document->setTitle($this->language->get('heading_title'));
        $this->load->model('setting/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_setting_setting->editSetting('payment_hutko', $this->request->post);
            $this->session->data['success'] = $this->language->get('text_success');
            $this->response->redirect($this->url->link('extension/payment/hutko', 'user_token=' . $this->session->data['user_token'], true));
        }

        $data['heading_title'] = $this->language->get('heading_title');
        // Populate $data with language strings and current settings
        $fields = [
            'payment_hutko_merchant_id',
            'payment_hutko_secret_key',
            'payment_hutko_shipping_include',
            'payment_hutko_shipping_product_name',
            'payment_hutko_shipping_product_code',
            'payment_hutko_new_order_status_id',
            'payment_hutko_success_status_id',
            'payment_hutko_declined_status_id',
            'payment_hutko_expired_status_id',
            'payment_hutko_refunded_status_id',
            'payment_hutko_include_discount_to_total',
            'payment_hutko_status',
            'payment_hutko_sort_order',
            'payment_hutko_geo_zone_id',
            'payment_hutko_total',
            'payment_hutko_save_logs'
        ];

        foreach ($fields as $field) {
            if (isset($this->request->post[$field])) {
                $data[$field] = $this->request->post[$field];
            } else {
                $data[$field] = $this->config->get($field);
            }
        }

        // Default values for new installs
        if (is_null($data['payment_hutko_shipping_product_name'])) {
            $data['payment_hutko_shipping_product_name'] = 'Package material';
        }
        if (is_null($data['payment_hutko_shipping_product_code'])) {
            $data['payment_hutko_shipping_product_code'] = '0_0_1';
        }
        if (is_null($data['payment_hutko_total'])) {
            $data['payment_hutko_total'] = '0.01';
        }


        // Error messages
        $errors = ['warning', 'merchant_id', 'secret_key'];
        foreach ($errors as $err_key) {
            if (isset($this->error[$err_key])) {
                $data['error_' . $err_key] = $this->error[$err_key];
            } else {
                $data['error_' . $err_key] = '';
            }
        }

        $data['breadcrumbs'] = array();
        $data['breadcrumbs'][] = array('text' => $this->language->get('text_home'), 'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true));
        $data['breadcrumbs'][] = array('text' => $this->language->get('text_extension'), 'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true));
        $data['breadcrumbs'][] = array('text' => $this->language->get('heading_title'), 'href' => $this->url->link('extension/payment/hutko', 'user_token=' . $this->session->data['user_token'], true));

        $data['action'] = $this->url->link('extension/payment/hutko', 'user_token=' . $this->session->data['user_token'], true);
        $data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true);

        $this->load->model('localisation/order_status');
        $data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

        $this->load->model('localisation/geo_zone');
        $data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

        // Logs (simplified)
        $data['log_content'] = $this->displayLastDayLog();
        $data['user_token'] = $this->session->data['user_token']; // Ensure it's passed to the view



        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/payment/hutko', $data));
    }

    protected function validate()
    {
        if (!$this->user->hasPermission('modify', 'extension/payment/hutko')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        $merchantId = $this->request->post['payment_hutko_merchant_id'];
        $secretKey = $this->request->post['payment_hutko_secret_key'];

        if (empty($merchantId)) {
            $this->error['merchant_id'] = $this->language->get('error_merchant_id_required');
        } elseif (!is_numeric($merchantId)) {
            $this->error['merchant_id'] = $this->language->get('error_merchant_id_numeric');
        }

        if (empty($secretKey)) {
            $this->error['secret_key'] = $this->language->get('error_secret_key_required');
        } elseif ($secretKey != 'test' && (strlen($secretKey) < 10 || is_numeric($secretKey))) {
            $this->error['secret_key'] = $this->language->get('error_secret_key_invalid');
        }

        return !$this->error;
    }

    public function install()
    {
        $this->load->model('extension/payment/hutko'); // Load our custom model
        $this->model_extension_payment_hutko->install(); // Call install method from our model
        $this->load->model('setting/setting');
        $defaults = array(
            'payment_hutko_status' => 0,
            'payment_hutko_sort_order' => 1,
            'payment_hutko_total' => '0.01',
            'payment_hutko_new_order_status_id' => $this->config->get('config_order_status_id'), // Default pending
            'payment_hutko_success_status_id' => 2, // Processing
            'payment_hutko_declined_status_id' => 10, // Failed
            'payment_hutko_expired_status_id' => 14, // Expired
            'payment_hutko_refunded_status_id' => 11, // Refunded
            'payment_hutko_shipping_include' => 1,
            'payment_hutko_shipping_product_name' => 'Shipping',
            'payment_hutko_shipping_product_code' => 'SHIPPING_001',
            'payment_hutko_save_logs' => 1,
            'payment_hutko_include_discount_to_total' => 1,
        );
        $this->model_setting_setting->editSetting('payment_hutko', $defaults);


        // Register event for displaying info on admin order page (OC 3.x+)
        if (defined('VERSION') && version_compare(VERSION, '3.0.0.0', '>=')) {
            $this->load->model('setting/event');
            $this->model_setting_event->addEvent(
                'hutko_admin_order_info_panel', // event_code (unique)
                'admin/view/sale/order_info/after', // trigger (after main view is rendered)
                'extension/payment/hutko/inject_admin_order_panel', // action (controller route)
                1, // status (1 = enabled)
                0 // sort_order
            );
        }
    }

    public function uninstall()
    {
        $this->load->model('extension/payment/hutko'); // Load our custom model
        $this->model_extension_payment_hutko->uninstall(); // Call uninstall method from our model

        $this->load->model('setting/setting');
        $this->model_setting_setting->deleteSetting('payment_hutko');

        // Unregister event (OC 3.x+)
        if (defined('VERSION') && version_compare(VERSION, '3.0.0.0', '>=')) {
            $this->load->model('setting/event');
            $this->model_setting_event->deleteEventByCode('hutko_admin_order_info_panel');
        }
    }
    /**
     * Event handler to inject Hutko panel into the admin order view output.
     * Triggered by: admin/view/sale/order_info/after
     */
    public function inject_admin_order_panel(&$route, &$data, &$output)
    {
        // Ensure order_id is available
        if (!isset($data['order_id'])) {
            // If order_id is not in $data, we cannot proceed.
            // This would be unusual for the sale/order/info route.
            $this->logOC("Hutko inject_admin_order_panel: order_id not found in \$data array.");
            return;
        }

        $order_id = (int)$data['order_id'];
        $current_payment_code = '';

        // Check if payment_code is already in $data 
        if (isset($data['payment_code'])) {
            $current_payment_code = $data['payment_code'];
        } else {
            // If not in $data, load the order info to get the payment_code
            $this->load->model('sale/order'); // Standard OpenCart order model
            $order_info = $this->model_sale_order->getOrder($order_id);
            if ($order_info && isset($order_info['payment_code'])) {
                $current_payment_code = $order_info['payment_code'];
                // Optionally, add it back to $data if other parts of your logic expect it,
                // though for this specific function, having $current_payment_code is enough.
                // $data['payment_code'] = $order_info['payment_code'];
            } else {
                $this->logOC("Hutko inject_admin_order_panel: Could not retrieve payment_code for order_id: " . $order_id);
                return; // Can't determine payment method
            }
        }

        // Now, check if this is a Hutko payment order
        if ($current_payment_code == 'hutko') {
            $this->load->language('extension/payment/hutko');
            $this->load->model('extension/payment/hutko'); 

            $hutko_order_data = $this->model_extension_payment_hutko->getHutkoOrder($order_id);

            $panel_data = []; 

            if ($hutko_order_data && !empty($hutko_order_data['hutko_transaction_ref'])) {
                $panel_data['hutko_transaction_ref_display'] = $hutko_order_data['hutko_transaction_ref'];
            } else {
                $panel_data['hutko_transaction_ref_display'] = $this->language->get('text_not_available');
            }

            $panel_data['hutko_refund_action_url'] = $this->url->link('extension/payment/hutko/refund', '', true);
            $panel_data['hutko_status_action_url'] = $this->url->link('extension/payment/hutko/status', '', true);
            $panel_data['order_id'] = $order_id;
            $panel_data['user_token_value'] = $this->session->data['user_token'];

            // Language strings for the panel template
            $panel_data['text_payment_information'] = $this->language->get('text_payment_information');
            $panel_data['text_hutko_refund_title'] = $this->language->get('text_hutko_refund_title');
            $panel_data['text_hutko_status_title'] = $this->language->get('text_hutko_status_title');
            $panel_data['button_hutko_refund'] = $this->language->get('button_hutko_refund');
            $panel_data['button_hutko_status_check'] = $this->language->get('button_hutko_status_check');
            $panel_data['text_hutko_transaction_ref_label'] = $this->language->get('text_hutko_transaction_ref_label');
            $panel_data['entry_refund_amount'] = $this->language->get('entry_refund_amount');
            $panel_data['entry_refund_comment'] = $this->language->get('entry_refund_comment');
            $panel_data['text_not_available'] = $this->language->get('text_not_available');
            $panel_data['text_loading'] = $this->language->get('text_loading');
            $panel_data['text_confirm_refund'] = $this->language->get('text_confirm_refund');
            $panel_data['user_token'] = $this->session->data['user_token'];
            $panel_data['order_id'] = $order_id;


            // Render the Hutko panel HTML
            $hutko_panel_html = $this->load->view('extension/payment/hutko_order_info_panel', $panel_data);



            // Try common injection points for better theme compatibility
            $possible_markers = [
                '{{ history }}',                       // Default Twig variable for history
                '<div id="history">',                  // Common ID for history section
                '<div class="tab-pane active" id="tab-history">', // Another common structure
                '</fieldset>\s*<fieldset>'             // Before the next fieldset after payment details
            ];

            $injected = false;
            foreach ($possible_markers as $marker) {
                if (strpos($output, $marker) !== false) {
                    $output = str_replace($marker, $hutko_panel_html . $marker, $output);
                    $injected = true;
                    break;
                } else if (preg_match('/' . preg_quote($marker, '/') . '/i', $output)) { // Case-insensitive for HTML tags
                    $output = preg_replace('/(' . preg_quote($marker, '/') . ')/i', $hutko_panel_html . '$1', $output, 1);
                    $injected = true;
                    break;
                }
            }

            if (!$injected) {
                // Fallback: if no specific marker found, try appending before the last major closing div of the form or content area.
                // This is less precise and might need adjustment based on common admin theme structures.
                $fallback_markers = [
                    '</form>', // Before closing form tag
                    '<div id="content"', // Appending inside the main content div as the last element (less ideal, but a last resort)
                ];
                foreach ($fallback_markers as $marker) {
                    if (strpos($output, $marker) !== false) {
                        if ($marker == '<div id="content"') { // If appending to content, add it before its closing tag
                            $output = preg_replace('/(<div id="content"[^>]*>)(.*)(<\/div>)/is', '$1$2' . $hutko_panel_html . '$3', $output, 1);
                        } else {
                            $output = str_replace($marker, $hutko_panel_html . $marker, $output);
                        }
                        $injected = true;
                        $this->logOC("Hutko inject_admin_order_panel: Used fallback marker '$marker'.");
                        break;
                    }
                }
            }

            if (!$injected) {
                $this->logOC("Hutko inject_admin_order_panel: Could not find any suitable injection marker in order_info output for order_id: " . $order_id);
                // As a very last resort, you could append to the end of $output, but this is usually not desired.
                // $output .= $hutko_panel_html;
            }
        }
    }

    public function refund()
    {
        $this->load->language('extension/payment/hutko');
        $this->load->model('extension/payment/hutko'); // Your custom model for hutko_transaction_ref
        $this->load->model('sale/order'); // Correct admin order model

        $json = array();

        // Check if order_id is coming from post (from JS AJAX call definition)
        if (!isset($this->request->post['order_id'])) {
            $json['error'] = $this->language->get('error_missing_order_id');
            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($json));
            return;
        }
        $order_id = (int)$this->request->post['order_id'];

        // Get Hutko transaction reference from custom table
        $hutko_order_info = $this->model_extension_payment_hutko->getHutkoOrder($order_id);
        if (!$hutko_order_info || empty($hutko_order_info['hutko_transaction_ref'])) {
            $json['error'] = $this->language->get('error_hutko_transaction_ref_not_found_db');
            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($json));
            return;
        }
        $hutko_transaction_ref = $hutko_order_info['hutko_transaction_ref'];

        // Check for refund amount and comment from POST data
        if (!isset($this->request->post['refund_amount'])) {
            $json['error'] = $this->language->get('error_missing_refund_amount');
            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($json));
            return;
        }
        $amount = (float)$this->request->post['refund_amount'];
        $comment = isset($this->request->post['refund_comment']) ? substr(trim($this->request->post['refund_comment']), 0, 1024) : '';


        $order_info = $this->model_sale_order->getOrder($order_id);

        if ($order_info && $hutko_transaction_ref && $amount > 0) {
            $response = $this->refundAPICallOC($hutko_transaction_ref, $amount, $order_info['currency_code'], $comment);

            if (isset($response['response']['reverse_status']) && $response['response']['reverse_status'] === 'approved' && isset($response['response']['response_status']) && $response['response']['response_status'] === 'success') {
                $refund_amount_returned = round((int)$response['response']['reversal_amount'] / 100, 2);
                $history_comment_text = sprintf($this->language->get('text_refund_success_comment'), $hutko_transaction_ref, $this->currency->format($refund_amount_returned, $order_info['currency_code'], $order_info['currency_value'], true), $comment);
                $this->addOrderHistory($order_id, $this->config->get('payment_hutko_refunded_status_id'), $history_comment_text, true);
                $json['success'] = $this->language->get('text_refund_success');
            } else {
                $error_message = isset($response['response']['error_message']) ? $response['response']['error_message'] : $this->language->get('text_unknown_error');
                $history_comment_text = sprintf($this->language->get('text_refund_failed_comment'), $hutko_transaction_ref, $error_message);
                $this->addOrderHistory($order_id, $order_info['order_status_id'], $history_comment_text, false); // Keep current status on failure
                $json['error'] = sprintf($this->language->get('text_refund_api_error'), $error_message);
                $this->logOC("Hutko Refund API Error for OC Order ID $order_id / Hutko ID $hutko_transaction_ref: " . json_encode($response));
            }
        } else {
            if (!$order_info) {
                $json['error'] = $this->language->get('error_order_not_found'); // Add this lang string
            } elseif ($amount <= 0) {
                $json['error'] = $this->language->get('error_invalid_refund_amount');
            } else {
                $json['error'] = $this->language->get('error_invalid_request');
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    /**
     * Helper function to add order history from admin.
     * This replicates the core logic found in admin/controller/sale/order.php history() method.
     */
    private function addOrderHistory($order_id, $order_status_id, $comment = '', $notify = false, $override = false)
    {
        $this->load->model('sale/order'); 
        // Get order info to prevent status update if not necessary or if order is complete/cancelled
        $order_info = $this->model_sale_order->getOrder($order_id);
        if (!$order_info) {
            $this->logOC("addOrderHistory: Order ID {$order_id} not found.");
            return; // Order not found
        }
        // Add history
        $this->db->query("INSERT INTO " . DB_PREFIX . "order_history SET order_id = '" . (int)$order_id . "', order_status_id = '" . (int)$order_status_id . "', notify = '" . (int)$notify . "', comment = '" . $this->db->escape($comment) . "', date_added = NOW()");
        // Update the order status
        $this->db->query("UPDATE `" . DB_PREFIX . "order` SET order_status_id = '" . (int)$order_status_id . "', date_modified = NOW() WHERE order_id = '" . (int)$order_id . "'");
    }

    public function status()
    {
        $this->load->language('extension/payment/hutko');
        $json = array();

        if (isset($this->request->post['hutko_transaction_ref'])) {
            $hutko_transaction_ref = $this->request->post['hutko_transaction_ref'];
            $response = $this->getOrderPaymentStatusOC($hutko_transaction_ref);

            if (isset($response['response']['response_status']) && $response['response']['response_status'] === 'success') {
                $json['success'] = $this->language->get('text_status_success');
                // Remove sensitive or overly verbose data before sending to frontend
                unset($response['response']['response_signature_string'], $response['response']['signature']);
                if (isset($response['response']['additional_info'])) {
                    $additional_info_decoded = json_decode($response['response']['additional_info'], true);
                    if (isset($additional_info_decoded['reservation_data'])) {
                        $additional_info_decoded['reservation_data_decoded'] = json_decode(base64_decode($additional_info_decoded['reservation_data']), true);
                        unset($additional_info_decoded['reservation_data']);
                    }
                    $response['response']['additional_info_decoded'] = $additional_info_decoded;
                    unset($response['response']['additional_info']);
                }
                $json['data'] = $response['response'];
            } else {
                $error_message = isset($response['response']['error_message']) ? $response['response']['error_message'] : $this->language->get('text_unknown_error');
                $json['error'] = sprintf($this->language->get('text_status_api_error'), $error_message);
                $this->logOC("Hutko Status API Error for Hutko ID $hutko_transaction_ref: " . json_encode($response));
            }
        } else {
            $json['error'] = $this->language->get('error_missing_params');
        }
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
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

    protected function sendAPICallOC(string $url, array $data, int $timeout = 60): array
    {
        if ($this->config->get('payment_hutko_save_logs')) {
            $this->logOC('Hutko API Request to ' . $url . ': ' . json_encode(['request' => $data]));
        }

        $requestPayload = ['request' => $data];
        $jsonPayload = json_encode($requestPayload);

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

    protected function refundAPICallOC(string $hutko_order_id, float $amount, string $currencyISO, string $comment = ''): array
    {
        $data = [
            'order_id'    => $hutko_order_id,
            'merchant_id' => $this->config->get('payment_hutko_merchant_id'),
            'version'     => '1.0',
            'amount'      => round($amount * 100),
            'currency'    => $currencyISO,
        ];
        if (!empty($comment)) {
            $data['comment'] = $comment;
        }
        $data['signature'] = $this->getSignatureOC($data);
        return $this->sendAPICallOC($this->refund_url, $data);
    }

    protected function getOrderPaymentStatusOC(string $hutko_order_id): array
    {
        $data = [
            'order_id'    => $hutko_order_id,
            'merchant_id' => $this->config->get('payment_hutko_merchant_id'),
            'version'     => '1.0',
        ];
        $data['signature'] = $this->getSignatureOC($data);
        return $this->sendAPICallOC($this->status_url, $data);
    }

    protected function logOC(string $message): void
    {
        if ($this->config->get('payment_hutko_save_logs')) {
            $this->log->write('Hutko Payment: ' . $message);
        }
    }

    protected function displayLastDayLog()
    {
        if (!$this->config->get('payment_hutko_save_logs')) {
            return '<p>' . $this->language->get('text_logs_disabled') . '</p>';
        }

        $log_file = DIR_LOGS . 'error.log'; 
        // More sophisticated would be to filter for "Hutko Payment:" lines
        // For simplicity, just show tail of general log file
        $content = '';
        if (file_exists($log_file)) {
            $size = filesize($log_file);
            // Read last N KB or N lines
            $lines_to_show = 100; // Show last 100 lines containing "Hutko Payment"
            $buffer_size = 4096;
            $hutko_lines = [];

            if ($size > 0) {
                $fp = fopen($log_file, 'r');
                if ($size > $buffer_size * 5) { // If file is large, seek towards the end
                    fseek($fp, $size - ($buffer_size * 5));
                }

                while (!feof($fp) && count($hutko_lines) < $lines_to_show * 2) { // Read a bit more to filter
                    $line = fgets($fp);
                    if ($line && strpos($line, 'Hutko Payment:') !== false) {
                        $hutko_lines[] = htmlspecialchars($line, ENT_QUOTES, 'UTF-8');
                    }
                }
                fclose($fp);
                $hutko_lines = array_slice($hutko_lines, -$lines_to_show); // Get the actual last N lines
            }


            if (!empty($hutko_lines)) {
                $content .= '<div style="background-color: #f8f8f8; border: 1px solid #ddd; padding: 10px; max-height: 400px; overflow-y: auto; font-family: monospace; white-space: pre-wrap; word-wrap: break-word;">';
                $content .= implode("<br>", array_reverse($hutko_lines)); // Show newest first
                $content .= '</div>';
            } else {
                $content = '<p>' . $this->language->get('text_no_logs_found') . '</p>';
            }
        } else {
            $content = '<p>' . sprintf($this->language->get('text_log_file_not_found'), $log_file) . '</p>';
        }
        return $content;
    }
}
