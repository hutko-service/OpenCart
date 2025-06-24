<?php
// Hutko translation file
// Heading
$_['heading_title'] = 'Платежи Hutko';


$_['text_extension'] = 'Расширения';
$_['text_success'] = 'Успех: Вы изменили настройки модуля оплаты Hutko!';
$_['text_edit'] = 'Изменить настройки Hutko';

$_['text_hutko'] = '<a href="https://hutko.org/" target="_blank"><img src="view/image/payment/hutko.png" alt="Hutko" title="Hutko" style="border: 1px solid #EEEEEE; max-height:25px;" /></a>'; // Вам понадобится hutko.png в admin/view/image/payment/
$_['text_enabled'] = 'Включено';
$_['text_disabled'] = 'Отключено';
$_['text_yes'] = 'Да';
$_['text_no'] = 'Нет';
$_['text_info_merchant'] = 'Используйте "1700243" для  теста.';
$_['text_info_secret'] = 'Используйте "test" для теста.';
$_['text_logs_disabled'] = 'Ведение журнала в настоящее время отключено. Включите "Сохранить журналы", чтобы увидеть журналы.';
$_['text_no_logs_found'] = 'В главном файле журнала на сегодня не найдено никаких записей журнала Hutko, или ведение журнала отключено.';
$_['text_log_file_not_found'] = 'Файл журнала (%s) не найден.';
$_['text_refund_success_comment'] = 'Возмещение выполнен успешно для ID: %s. Сумма: %s. Комментарий: %s';
$_['text_refund_failed_comment'] = 'Попытка возмещения не удалась для ID: %s. Причина: %s';
$_['text_refund_success'] = 'Возмещение успешно обработано через Hutko.';
$_['text_refund_api_error'] = 'Ошибка API Hutko: %s';
$_['text_status_success'] = 'Статус успешно получен от Hutko.';
$_['text_status_api_error'] = 'Ошибка API Hutko при получении статуса: %s';
$_['text_unknown_error'] = 'Произошла неизвестная ошибка.';

$_['entry_merchant_id'] = 'ID продавца';
$_['entry_secret_key'] = 'Секретный ключ';
$_['entry_new_order_status'] = 'Статус нового заказа';
$_['entry_success_status'] = 'Статус при успешном платеже';
$_['entry_declined_status'] = 'Статус при отклоненном платеже';
$_['entry_expired_status'] = 'Статус при просроченном платеже';
$_['entry_refunded_status'] = 'Статус при возмещении платеже';
$_['entry_shipping_include'] = 'Включить стоимость доставки';
$_['entry_shipping_product_name'] = 'Наименование доставки в фискальном чеке';
$_['entry_shipping_product_code'] = 'Код доставки в фискальном чеке';
$_['entry_show_cards_logo'] = 'Показать логотип Visa/MasterCard';
$_['entry_save_logs'] = 'Сохранять журналы';
$_['entry_include_discount_to_total'] = 'Включить скидки в общую сумму (для API)';
$_['entry_total'] = 'Минимальная сумма заказа';
$_['entry_geo_zone'] = 'Геозона';
$_['entry_status'] = 'Статус';
$_['entry_sort_order'] = 'Порядок сортировки';


$_['help_total'] = 'Сумма, которую должен достичь заказ, прежде чем этот способ оплаты станет активным.';
$_['help_new_order_status'] = 'Статус для новых заказов до получения платежа.';
$_['help_success_status'] = 'Статус для успешно оплаченных заказов.';
$_['help_shipping_include'] = 'Включить стоимость доставки в суму платежа.';
$_['help_shipping_product_name'] = 'Название продукта/услуги для использования при фискализации для суммы доставки.';
$_['help_shipping_product_code'] = 'Код продукта/услуги для использования при фискализации для суммы доставки.';
$_['help_show_cards_logo'] = 'Отображать логотипы Visa/MasterCard рядом с названием способа оплаты при оформлении заказа.';
$_['help_save_logs'] = 'Записывать коммуникацию API и обратные вызовы в системный файл журнала.';
$_['help_include_discount_to_total'] = 'Если да, скидки по заказу будут вычтены из общей суммы платежа, это может помешать фискализации.';


$_['error_permission'] = 'Внимание: у вас нет разрешения на изменение платежного модуля Hutko!';
$_['error_merchant_id_required'] = 'Требуется идентификатор продавца!';
$_['error_merchant_id_numeric'] = 'Идентификатор продавца должен быть числовым!';
$_['error_secret_key_required'] = 'Требуется секретный ключ!';
$_['error_secret_key_invalid'] = 'Секретный ключ должен быть "test" или содержать не менее 10 символов и не состоять полностью из цифр.';
$_['error_invalid_request'] = 'Недопустимые данные запроса на возмещения/статус.';
$_['error_missing_params'] = 'Отсутствуют обязательные параметры для возмещения/статуса.';


$_['tab_general'] = 'Общие';
$_['tab_order_statuses'] = 'Статусы заказов';
$_['tab_fiscalization'] = 'Фискализация';
$_['tab_advanced'] = 'Дополнительно';
$_['tab_logs'] = 'Журналы';
$_['text_payment_information'] = 'История платежей';
$_['text_not_available'] = 'Н/Д';
$_['text_hutko_transaction_ref_label'] = 'Идентификатор заказа в Hutko';
$_['text_hutko_refund_title'] = 'Возмещение Hutko';
$_['text_hutko_status_title'] = 'Проверка статуса Hutko';
$_['button_hutko_refund'] = 'Обработать возмещение через Hutko';
$_['button_hutko_status_check'] = 'Проверить статус платежа Hutko';
$_['entry_refund_amount'] = 'Сумма возмещения';
$_['entry_refund_comment'] = 'Комментарий к возмещению (необязательно)';
$_['text_refund_success_comment'] = 'Возмещение средств по ID %s успешно. Сумма: %s. Комментарий: %s';
$_['text_refund_failed_comment'] = 'Попытка возмещения средств по ID %s не удалась. Ошибка шлюза: %s';
$_['text_refund_api_error'] = 'Ошибка API возмещения Hutko: %s';
$_['text_status_api_error'] = 'Ошибка API статуса Hutko: %s';
$_['text_unknown_error'] = 'Произошла неизвестная ошибка API.';
$_['error_missing_order_id'] = 'Ошибка: в запросе отсутствует идентификатор заказа.';
$_['error_hutko_transaction_ref_not_found_db'] = 'Ошибка: идентификатор заказа Hutko не найден в базе данных для этого заказа.';
$_['error_hutko_transaction_ref_missing'] = 'Ошибка: идентификатор заказа Hutko требуется для этой операции.';
$_['error_invalid_refund_amount'] = 'Ошибка: недопустимая сумма возврата. Должна быть больше 0.';
$_['error_missing_refund_amount'] = 'Ошибка: требуется сумма возврата.';

$_['error_payment_data_build'] = 'Ошибка: не удалось подготовить данные платежа. Повторите попытку или обратитесь в службу поддержки.';
$_['error_api_communication'] = 'Ошибка: не удалось связаться с платежным шлюзом. Повторите попытку.';
$_['text_redirecting_comment'] = 'Перенаправление на Hutko. Идентификатор заказа Hutko: %s. URL: %s';

// Для обратного вызова
$_['text_payment_approved'] = 'Платеж одобрен Hutko.';
$_['text_payment_declined'] = 'Платеж отклонен Hutko.';
$_['text_payment_expired'] = 'Срок платежа истек в Hutko.';
$_['text_payment_processing'] = 'Платеж обрабатывается в Hutko.';
$_['text_confirm_refund'] = 'Вы уверены, что хотите возместить оплату через Hutko? Это действие нельзя отменить.';
$_['text_loading'] = 'Загрузка...'; 
$_['error_order_not_found'] = 'Ошибка: заказ не найден.';