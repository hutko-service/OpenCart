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
$_['text_info_merchant'] = 'Используйте "1700243" для теста.';
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












$_['heading_title'] = 'Платежі Hutko';


$_['text_extension'] = 'Розширення';
$_['text_success'] = 'Успішно: Ви змінили налаштування модуля оплати Hutko!';
$_['text_edit'] = 'Змінити налаштуваннь Hutko';

$_['text_hutko'] = '<a href="https://hutko.org/" target="_blank"><img src="view/image/payment/hutko.png" alt="Hutko" title="Hutko" style="border: 1px solid #EEEEEE; max-he; /></a>'; 
$_['text_enabled'] = 'Увімкнено';
$_['text_disabled'] = 'Вимкнено';
$_['text_yes'] = 'Так';
$_['text_no'] = 'Ні';
$_['text_info_merchant'] = 'Використовуйте "1700243" для тестування.';
$_['text_info_secret'] = 'Використовуйте "test" для тестування.';
$_['text_logs_disabled'] = 'Ведення журналу в даний час вимкнено. Увімкніть "Зберегти журнали", щоб побачити журнали.';
$_['text_no_logs_found'] = 'У головному файлі журналу на сьогодні не знайдено жодних записів журналу Hutko, або ведення журналу вимкнено.';
$_['text_log_file_not_found'] = 'Файл журналу (%s) не знайдено.';
$_['text_refund_success_comment'] = 'Відшкодування успішно виконано для ID: %s. Сума: %s. Коментар: %s';
$_['text_refund_failed_comment'] = 'Спроба відшкодування не вдалася для ID: %s. Причина: %s';
$_['text_refund_success'] = 'Відшкодування успішно оброблено через Hutko.';
$_['text_refund_api_error'] = 'Помилка API Hutko: %s';
$_['text_status_success'] = 'Статус успішно отримано від Hutko.';
$_['text_status_api_error'] = 'Помилка API Hutko при отриманні статусу: %s';
$_['text_unknown_error'] = 'Відбулася невідома помилка.';

$_['entry_merchant_id'] = 'ID продавця';
$_['entry_secret_key'] = 'Секретний ключ';
$_['entry_new_order_status'] = 'Статус нового замовлення';
$_['entry_success_status'] = 'Статус за успішного платежу';
$_['entry_declined_status'] = 'Статус при відхиленому платежі';
$_['entry_expired_status'] = 'Статус при простроченому платежі';
$_['entry_refunded_status'] = 'Статус при відшкодуванні платежу';
$_['entry_shipping_include'] = 'Включати вартість доставки';
$_['entry_shipping_product_name'] = 'Найменування доставки у фіскальному чеку';
$_['entry_shipping_product_code'] = 'Код доставки у фіскальному чеку';
$_['entry_show_cards_logo'] = 'Показати логотип Visa/MasterCard';
$_['entry_save_logs'] = 'Зберігати журнали';
$_['entry_include_discount_to_total'] = 'Включити фіксовані знижки в загальну суму оплати';
$_['entry_total'] = 'Мінімальна сума замовлення';
$_['entry_geo_zone'] = 'Геозона';
$_['entry_status'] = 'Статус';
$_['entry_sort_order'] = 'Порядок сортування';


$_['help_total'] = 'Сума, яку має досягти замовлення, перш ніж цей спосіб оплати стане активним.';
$_['help_new_order_status'] = 'Статус для нових замовлень до отримання платежу.';
$_['help_success_status'] = 'Статус для успішно оплачених замовлень.';
$_['help_shipping_include'] = 'Включити вартість доставки до суми платежу.';
$_['help_shipping_product_name'] = 'Назва продукту/послуги для використання при фіскалізації для суми доставки.';
$_['help_shipping_product_code'] = 'Код продукту/послуги для використання при фіскалізації для суми доставки.';
$_['help_show_cards_logo'] = 'Відображати логотипи Visa/MasterCard поруч із назвою способу оплати при оформленні замовлення.';
$_['help_save_logs'] = 'Записувати комунікацію API та зворотні виклики до системного файлу журналу.';
$_['help_include_discount_to_total'] = 'Якщо так, знижки на замовлення будуть вираховані із загальної суми платежу, це може перешкодити фіскалізації.';


$_['error_permission'] = 'Увага: у вас немає дозволу на зміну платіжного модуля Hutko!';
$_['error_merchant_id_required'] = 'Потрібен ідентифікатор продавця!';
$_['error_merchant_id_numeric'] = 'Ідентифікатор продавця має бути числовим!';
$_['error_secret_key_required'] = 'Потрібен секретний ключ!';
$_['error_secret_key_invalid'] = 'Секретний ключ повинен бути "test" або містити не менше 10 символів і не складатися повністю із цифр.';
$_['error_invalid_request'] = 'Неприпустимі дані запиту на відшкодування/статус.';
$_['error_missing_params'] = 'Відсутні обов\'язкові параметри для відшкодування/статусу.';


$_['tab_general'] = 'Загальні';
$_['tab_order_statuses'] = 'Статуси замовлень';
$_['tab_fiscalization'] = 'Фіскалізація';
$_['tab_advanced'] = 'Додатково';
$_['tab_logs'] = 'Журнали';
$_['text_payment_information'] = 'Історія платежів';
$_['text_not_available'] = 'Н/Д';
$_['text_hutko_transaction_ref_label'] = 'Ідентифікатор замовлення в Hutko';
$_['text_hutko_refund_title'] = 'Відшкодування Hutko';
$_['text_hutko_status_title'] = 'Перевірка статусу Hutko';
$_['button_hutko_refund'] = 'Обробити відшкодування через Hutko';
$_['button_hutko_status_check'] = 'Перевірити статус платежу Hutko';
$_['entry_refund_amount'] = 'Сума відшкодування';
$_['entry_refund_comment'] = 'Коментар до відшкодування (необов\'язково)';
$_['text_refund_success_comment'] = 'Відшкодування коштів за ID %s успішно. Сума: %s. Коментар: %s';
$_['text_refund_failed_comment'] = 'Спроба відшкодування коштів за ID %s не вдалася. Помилка шлюзу: %s';
$_['text_refund_api_error'] = 'Помилка API відшкодування Hutko: %s';
$_['text_status_api_error'] = 'Помилка API статусу Hutko: %s';
$_['text_unknown_error'] = 'Відбулася невідома помилка API.';
$_['error_missing_order_id'] = 'Помилка: у запиті відсутній ідентифікатор замовлення.';
$_['error_hutko_transaction_ref_not_found_db'] = 'Помилка: ідентифікатор замовлення Hutko не знайдений у базі даних для цього замовлення.';
$_['error_hutko_transaction_ref_missing'] = 'Помилка: ідентифікатор замовлення Hutko потрібний для цієї операції.';
$_['error_invalid_refund_amount'] = 'Помилка: неприпустима сума повернення. Має бути більше 0.';
$_['error_missing_refund_amount'] = 'Помилка: потрібна сума повернення.';

$_['error_payment_data_build'] = 'Помилка: не вдалося підготувати дані платежу. Повторіть спробу або зверніться до служби підтримки.';
$_['error_api_communication'] = 'Помилка: не вдалося зв\'язатися з платіжним шлюзом. Повторіть спробу.';
$_['text_redirecting_comment'] = 'Перенаправлення на Hutko. Ідентифікатор замовлення Hutko: %s. URL: %s';

// Для зворотного виклику
$_['text_payment_approved'] = 'Платіж схвалений Hutko.';
$_['text_payment_declined'] = 'Платіж відхилений Hutko.';
$_['text_payment_expired'] = 'Термін платежу минув у Hutko.';
$_['text_payment_processing'] = 'Платіж обробляється в Hutko.';
$_['text_confirm_refund'] = 'Ви впевнені, що хочете відшкодувати оплату через Hutko? Цю дію не можна скасувати.';
$_['text_loading'] = 'Завантаження...';
$_['error_order_not_found'] = 'Помилка: замовлення не знайдено.';