<?php
class ModelExtensionPaymentHutko extends Model {
    public function install() {
        $this->db->query("
            CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "hutko_order` (
                `hutko_order_pk_id` INT(11) NOT NULL AUTO_INCREMENT,
                `order_id` INT(11) NOT NULL,
                `hutko_transaction_ref` VARCHAR(255) NOT NULL,
                `date_added` DATETIME NOT NULL,
                PRIMARY KEY (`hutko_order_pk_id`),
                UNIQUE KEY `idx_order_id` (`order_id`),
                KEY `idx_hutko_transaction_ref` (`hutko_transaction_ref`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
        ");
    }

    public function uninstall() {
      //  $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "hutko_order`;");
    }

    public function addHutkoOrder($order_id, $hutko_transaction_ref) {
        $this->db->query("INSERT INTO `" . DB_PREFIX . "hutko_order` SET `order_id` = '" . (int)$order_id . "', `hutko_transaction_ref` = '" . $this->db->escape($hutko_transaction_ref) . "', `date_added` = NOW() ON DUPLICATE KEY UPDATE `hutko_transaction_ref` = '" . $this->db->escape($hutko_transaction_ref) . "', `date_added` = NOW()");
    }

    public function getHutkoOrder($order_id) {
        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "hutko_order` WHERE `order_id` = '" . (int)$order_id . "'");
        return $query->row;
    }
}