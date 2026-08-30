INSERT INTO `sender_status` (`id`, `name`, `description`) VALUES (NULL, 'Unconfirmed', NULL), (NULL, 'Confirmed', NULL), (NULL, 'Declined', NULL), (NULL, 'Blacklisted', NULL);

ALTER TABLE `identifications` CHANGE `created_date` `created_at` TIMESTAMP NULL DEFAULT NULL;

ALTER TABLE `identifications` CHANGE `updated_date` `updated_date` TIMESTAMP NULL DEFAULT NULL;

INSERT INTO `identification_status` (`id`, `name`, `description`) VALUES (NULL, 'Pending', NULL), (NULL, 'Verified', NULL), (NULL, 'Declined', NULL);

INSERT INTO `identification_types` (`id`, `name`, `points`, `description`) VALUES (NULL, 'Document', '10', NULL);

ALTER TABLE `identifications` CHANGE `updated_date` `updated_at` TIMESTAMP NULL DEFAULT NULL;

/* Ask this null because no address for sender company */
ALTER TABLE `companies` CHANGE `addresses_id` `addresses_id` INT(11) NULL DEFAULT NULL;

/* New */
ALTER TABLE `transactions` ADD `created_at` TIMESTAMP NOT NULL AFTER `sender_companies_id`, ADD `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `created_at`;

INSERT INTO `transaction_document_type` (`id`, `name`, `description`) VALUES (NULL, 'Payment Receipt', NULL), (NULL, 'Deposit Slip', NULL), (NULL, 'Others', NULL);

