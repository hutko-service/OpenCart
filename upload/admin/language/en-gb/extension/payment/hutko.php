<?php
// Hutko translation file
// Heading
$_['heading_title']      = 'Hutko Payments';

// Text
$_['text_extension']     = 'Extensions';
$_['text_success']       = 'Success: You have modified Hutko payment module settings!';
$_['text_edit']          = 'Edit Hutko Payments';
$_['text_hutko']        = '<a href="https://hutko.org/" target="_blank"><img src="view/image/payment/hutko.png" alt="Hutko" title="Hutko" style="border: 1px solid #EEEEEE; max-height:25px;" /></a>'; // You'll need a hutko.png in admin/view/image/payment/
$_['text_enabled']       = 'Enabled';
$_['text_disabled']      = 'Disabled';
$_['text_yes']           = 'Yes';
$_['text_no']            = 'No';
$_['text_info_merchant'] = 'Use "1700243" for test setup.';
$_['text_info_secret']   = 'Use "test" for test setup.';
$_['text_logs_disabled'] = 'Logging is currently disabled. Enable "Save Logs" to see logs.';
$_['text_no_logs_found'] = 'No Hutko specific log entries found in the main log file for today, or logging is disabled.';
$_['text_log_file_not_found'] = 'Log file (%s) not found.';
$_['text_refund_success_comment'] = 'Refund Successful for ID: %s. Amount: %s. Comment: %s';
$_['text_refund_failed_comment']  = 'Refund Attempt Failed for ID: %s. Reason: %s';
$_['text_refund_success']         = 'Refund processed successfully via Hutko.';
$_['text_refund_api_error']       = 'Hutko API Error: %s';
$_['text_status_success']         = 'Status retrieved successfully from Hutko.';
$_['text_status_api_error']       = 'Hutko API Error fetching status: %s';
$_['text_unknown_error']          = 'An unknown error occurred.';


// Entry
$_['entry_merchant_id']  = 'Merchant ID';
$_['entry_secret_key']   = 'Secret Key';
$_['entry_new_order_status'] = 'New Order Status';
$_['entry_success_status'] = 'Successful Payment Status';
$_['entry_declined_status'] = 'Declined Payment Status';
$_['entry_expired_status'] = 'Expired Payment Status';
$_['entry_refunded_status'] = 'Refunded Payment Status';
$_['entry_shipping_include'] = 'Include Shipping Cost';
$_['entry_shipping_product_name'] = 'Shipping Fiscal Name';
$_['entry_shipping_product_code'] = 'Shipping Fiscal Code';
$_['entry_show_cards_logo'] = 'Show Visa/MasterCard Logo';
$_['entry_save_logs']      = 'Save Logs';
$_['entry_include_discount_to_total'] = 'Include Discounts in Total (for API)';
$_['entry_total']        = 'Minimum Order Total';
$_['entry_geo_zone']     = 'Geo Zone';
$_['entry_status']       = 'Status';
$_['entry_sort_order']   = 'Sort Order';

// Help
$_['help_total']         = 'The checkout total the order must reach before this payment method becomes active.';
$_['help_new_order_status'] = 'Status for new orders before payment redirection.';
$_['help_success_status'] = 'Status for successfully paid orders.';
$_['help_shipping_include'] = 'Include shipping cost as a separate item in the payment request details.';
$_['help_shipping_product_name'] = 'Name of product/service to use in fiscalization for shipping amount.';
$_['help_shipping_product_code'] = 'Code of product/service to use in fiscalization for shipping amount.';
$_['help_show_cards_logo'] = 'Display Visa/MasterCard logos next to the payment method name on checkout.';
$_['help_save_logs']       = 'Log API communication and callbacks to the system log file.';
$_['help_include_discount_to_total'] = 'If Yes, order discounts will be subtracted from the payment total, this may prevent fiscalization.';


// Error
$_['error_permission']   = 'Warning: You do not have permission to modify Hutko payment module!';
$_['error_merchant_id_required'] = 'Merchant ID is required!';
$_['error_merchant_id_numeric'] = 'Merchant ID must be numeric!';
$_['error_secret_key_required'] = 'Secret Key is required!';
$_['error_secret_key_invalid']  = 'Secret key must be "test" or at least 10 characters long and not entirely numeric.';
$_['error_invalid_request']     = 'Invalid request data for refund/status.';
$_['error_missing_params']      = 'Missing required parameters for refund/status.';


// Tab
$_['tab_general']        = 'General';
$_['tab_order_statuses'] = 'Order Statuses';
$_['tab_fiscalization']  = 'Fiscalization';
$_['tab_advanced']       = 'Advanced';
$_['tab_logs']           = 'Logs';
$_['text_payment_information']  = 'Payments History';


$_['text_not_available']                 = 'N/A';
$_['text_hutko_transaction_ref_label']    = 'Hutko Transaction ID';
$_['text_hutko_refund_title']            = 'Hutko Refund';
$_['text_hutko_status_title']            = 'Hutko Status Check';
$_['button_hutko_refund']                = 'Process Hutko Refund';
$_['button_hutko_status_check']          = 'Check Hutko Payment Status';
$_['entry_refund_amount']                = 'Refund Amount';
$_['entry_refund_comment']               = 'Refund Comment (optional)';

$_['text_refund_success_comment']        = 'Refund for Hutko ID %s successful. Amount: %s. Comment: %s';
$_['text_refund_failed_comment']         = 'Refund attempt for Hutko ID %s failed. Gateway error: %s';
$_['text_refund_api_error']              = 'Hutko Refund API Error: %s';
$_['text_status_api_error']              = 'Hutko Status API Error: %s';
$_['text_unknown_error']                 = 'An unknown error occurred with the API.';


$_['error_missing_order_id']             = 'Error: Order ID is missing from the request.';
$_['error_hutko_transaction_ref_not_found_db'] = 'Error: Hutko Transaction ID not found in database for this order.';
$_['error_hutko_transaction_ref_missing'] = 'Error: Hutko Transaction ID is required for this operation.';
$_['error_invalid_refund_amount']        = 'Error: Invalid refund amount. Must be greater than 0.';
$_['error_missing_refund_amount']        = 'Error: Refund amount is required.';

// For catalog side (checkout process)
$_['error_payment_data_build']           = 'Error: Could not prepare payment data. Please try again or contact support.';
$_['error_api_communication']            = 'Error: Could not communicate with the payment gateway. Please try again.';
$_['text_redirecting_comment']           = 'Redirecting to Hutko. Hutko Order ID: %s. URL: %s';

// For callback
$_['text_payment_approved']              = 'Payment Approved by Hutko.';
$_['text_payment_declined']              = 'Payment Declined by Hutko.';
$_['text_payment_expired']               = 'Payment Expired at Hutko.';
$_['text_payment_processing']            = 'Payment is Processing at Hutko.';
$_['text_confirm_refund'] = 'Are you sure you want to refund this transaction via Hutko? This action cannot be undone.';

$_['text_loading']                       = 'Loading...'; 
$_['error_order_not_found']              = 'Error: Order not found.';
