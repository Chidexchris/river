-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 11, 2025 at 01:56 PM
-- Server version: 8.0.30
-- PHP Version: 8.3.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `database`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `name`, `email`, `username`, `contact`, `address`, `email_verified_at`, `image`, `password`, `remember_token`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Marcus Harrison', 'admin@example.com', 'admin', '3037774786', '1893 S Pearl St, Denver, Colorado, United States', NULL, '6667e37a558631718084474.png', '$2y$12$ZwOySjmq/bndEjhenqGEGurwGpCydWhag3srMcDBUFoPQlIxV7jKq', NULL, 1, NULL, '2025-03-15 08:38:32');

-- --------------------------------------------------------

--
-- Table structure for table `admin_notifications`
--

CREATE TABLE `admin_notifications` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL DEFAULT '0',
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_read` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `click_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `admin_password_resets`
--

CREATE TABLE `admin_password_resets` (
  `id` bigint UNSIGNED NOT NULL,
  `email` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint UNSIGNED NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `beneficiaries`
--

CREATE TABLE `beneficiaries` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `beneficiaryable_id` bigint UNSIGNED NOT NULL,
  `beneficiaryable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `details` json NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `branches`
--

CREATE TABLE `branches` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `routing_number` int UNSIGNED NOT NULL,
  `swift_code` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact_number` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `map_location` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0: Inactive, 1: Active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `branch_staff`
--

CREATE TABLE `branch_staff` (
  `branch_id` bigint UNSIGNED NOT NULL,
  `staff_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint UNSIGNED NOT NULL DEFAULT '0' COMMENT '1 -> Answered, 0 -> Unanswered',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `deposits`
--

CREATE TABLE `deposits` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL DEFAULT '0',
  `branch_id` bigint UNSIGNED DEFAULT NULL,
  `staff_id` bigint UNSIGNED DEFAULT NULL,
  `method_code` int UNSIGNED NOT NULL DEFAULT '0',
  `method_currency` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `charge` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `rate` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `final_amount` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `details` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `btc_amount` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `btc_wallet` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `trx` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_try` int NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1 => success, 2 => pending, 3 => cancel',
  `from_api` tinyint(1) NOT NULL DEFAULT '0',
  `admin_feedback` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `deposit_pension_schemes`
--

CREATE TABLE `deposit_pension_schemes` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `plan_name` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `scheme_code` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `per_installment` decimal(28,8) NOT NULL,
  `installment_interval` int UNSIGNED NOT NULL,
  `total_installment` int UNSIGNED NOT NULL,
  `given_installment` int UNSIGNED NOT NULL DEFAULT '0',
  `total_deposit_amount` decimal(28,8) NOT NULL,
  `interest_rate` decimal(5,2) NOT NULL,
  `profit_amount` decimal(28,8) NOT NULL,
  `maturity_amount` decimal(28,8) NOT NULL,
  `delay_duration` int UNSIGNED NOT NULL,
  `per_installment_late_fee` decimal(28,8) NOT NULL,
  `total_late_fees` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `late_fee_last_notified_at` timestamp NULL DEFAULT NULL,
  `transfer_at` timestamp NULL DEFAULT NULL,
  `status` tinyint UNSIGNED NOT NULL DEFAULT '1' COMMENT '0 -> closed, 1 -> running, 2 -> matured',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `deposit_pension_scheme_plans`
--

CREATE TABLE `deposit_pension_scheme_plans` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `per_installment` decimal(28,8) NOT NULL,
  `installment_interval` int UNSIGNED NOT NULL,
  `total_installment` int UNSIGNED NOT NULL,
  `total_deposit_amount` decimal(28,8) NOT NULL,
  `interest_rate` decimal(5,2) NOT NULL,
  `profit_amount` decimal(28,8) NOT NULL,
  `maturity_amount` decimal(28,8) NOT NULL,
  `delay_duration` int UNSIGNED NOT NULL,
  `fixed_charge` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `percentage_charge` decimal(5,2) NOT NULL DEFAULT '0.00',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0 -> Plan is inactive, 1 -> Plan is active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fixed_deposit_schemes`
--

CREATE TABLE `fixed_deposit_schemes` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `plan_name` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `scheme_code` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `interest_rate` decimal(5,2) NOT NULL,
  `deposit_amount` decimal(28,8) NOT NULL COMMENT 'The amount deposited at the time of opening the FDS',
  `interest_payout_interval` int UNSIGNED NOT NULL,
  `per_installment` decimal(28,8) NOT NULL COMMENT 'The amount provided by the bank in each installment',
  `profit_amount` decimal(28,8) NOT NULL DEFAULT '0.00000000' COMMENT 'The total profit amount',
  `next_installment_date` date NOT NULL,
  `locked_until` date NOT NULL,
  `transfer_at` timestamp NULL DEFAULT NULL,
  `status` tinyint UNSIGNED NOT NULL DEFAULT '1' COMMENT '0 -> closed, 1 -> running',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fixed_deposit_scheme_plans`
--

CREATE TABLE `fixed_deposit_scheme_plans` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `interest_rate` decimal(5,2) NOT NULL,
  `interest_payout_interval` int UNSIGNED NOT NULL,
  `lock_in_period` int UNSIGNED NOT NULL,
  `minimum_amount` decimal(28,8) NOT NULL,
  `maximum_amount` decimal(28,8) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0 -> Plan is inactive, 1 -> Plan is active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `forms`
--

CREATE TABLE `forms` (
  `id` bigint UNSIGNED NOT NULL,
  `act` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `form_data` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gateways`
--

CREATE TABLE `gateways` (
  `id` bigint UNSIGNED NOT NULL,
  `form_id` int UNSIGNED NOT NULL DEFAULT '0',
  `code` int UNSIGNED DEFAULT NULL,
  `name` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alias` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'NULL',
  `status` tinyint UNSIGNED NOT NULL DEFAULT '1' COMMENT '1=>Active, 2=>Inactive',
  `gateway_parameters` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `supported_currencies` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `crypto` tinyint UNSIGNED NOT NULL DEFAULT '0' COMMENT '0: fiat currency, 1: crypto currency',
  `extra` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `guideline` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gateways`
--

INSERT INTO `gateways` (`id`, `form_id`, `code`, `name`, `alias`, `status`, `gateway_parameters`, `supported_currencies`, `crypto`, `extra`, `guideline`, `created_at`, `updated_at`) VALUES
(1, 0, 507, 'BTCPay', 'BTCPay', 0, '{\"store_id\":{\"title\":\"Store Id\",\"global\":true,\"value\":\"-----------------\"},\"api_key\":{\"title\":\"Api Key\",\"global\":true,\"value\":\"-----------------\"},\"server_name\":{\"title\":\"Server Name\",\"global\":true,\"value\":\"-----------------\"},\"secret_code\":{\"title\":\"Secret Code\",\"global\":true,\"value\":\"-----------------\"}}', '{\"BTC\":\"Bitcoin\",\"LTC\":\"Litecoin\"}', 1, '{\"webhook\":{\"title\": \"IPN URL\",\"value\":\"ipn.BTCPay\"}}', NULL, NULL, '2025-05-03 12:41:15'),
(2, 0, 102, 'Perfect Money', 'PerfectMoney', 0, '{\"passphrase\":{\"title\":\"Alternate Passpharse\",\"global\":true,\"value\":\"-----------------\"},\"wallet_id\":{\"title\":\"PM Wallet\",\"global\":false,\"value\":\"\"}}', '{\"USD\":\"$\",\"EUR\":\"\\u20ac\"}', 0, NULL, NULL, '2019-09-14 07:14:22', '2025-05-03 12:43:00'),
(3, 0, 106, 'Payeer', 'Payeer', 0, '{\"merchant_id\":{\"title\":\"Merchant ID\",\"global\":true,\"value\":\"-----------------\"},\"secret_key\":{\"title\":\"Secret key\",\"global\":true,\"value\":\"-----------------\"}}', '{\"USD\":\"USD\",\"EUR\":\"EUR\",\"RUB\":\"RUB\"}', 0, '{\"status\":{\"title\": \"Status URL\",\"value\":\"ipn.Payeer\"}}', NULL, '2019-09-14 07:14:22', '2025-05-03 12:42:30'),
(4, 0, 107, 'PayStack', 'Paystack', 0, '{\"public_key\":{\"title\":\"Public Key\",\"global\":true,\"value\":\"-----------------\"},\"secret_key\":{\"title\":\"Secret Key\",\"global\":true,\"value\":\"-----------------\"}}', '{\"USD\":\"USD\",\"NGN\":\"NGN\"}', 0, '{\"callback\":{\"title\": \"Callback URL\",\"value\":\"ipn.Paystack\"},\"webhook\":{\"title\": \"Webhook URL\",\"value\":\"ipn.Paystack\"}}\r\n', NULL, '2019-09-14 07:14:22', '2025-05-03 12:42:51'),
(6, 0, 109, 'Flutterwave', 'Flutterwave', 0, '{\"public_key\":{\"title\":\"Public Key\",\"global\":true,\"value\":\"-----------------\"},\"secret_key\":{\"title\":\"Secret Key\",\"global\":true,\"value\":\"-----------------\"},\"encryption_key\":{\"title\":\"Encryption Key\",\"global\":true,\"value\":\"-----------------\"}}', '{\"BIF\":\"BIF\",\"CAD\":\"CAD\",\"CDF\":\"CDF\",\"CVE\":\"CVE\",\"EUR\":\"EUR\",\"GBP\":\"GBP\",\"GHS\":\"GHS\",\"GMD\":\"GMD\",\"GNF\":\"GNF\",\"KES\":\"KES\",\"LRD\":\"LRD\",\"MWK\":\"MWK\",\"MZN\":\"MZN\",\"NGN\":\"NGN\",\"RWF\":\"RWF\",\"SLL\":\"SLL\",\"STD\":\"STD\",\"TZS\":\"TZS\",\"UGX\":\"UGX\",\"USD\":\"USD\",\"XAF\":\"XAF\",\"XOF\":\"XOF\",\"ZMK\":\"ZMK\",\"ZMW\":\"ZMW\",\"ZWD\":\"ZWD\"}', 0, NULL, NULL, '2019-09-14 07:14:22', '2025-05-03 12:42:04'),
(7, 0, 503, 'CoinPayments', 'Coinpayments', 0, '{\"public_key\":{\"title\":\"Public Key\",\"global\":true,\"value\":\"----------\"},\"private_key\":{\"title\":\"Private Key\",\"global\":true,\"value\":\"----------\"},\"merchant_id\":{\"title\":\"Merchant ID\",\"global\":true,\"value\":\"----------\"}}', '{\"BTC\":\"Bitcoin\",\"BTC.LN\":\"Bitcoin (Lightning Network)\",\"LTC\":\"Litecoin\",\"CPS\":\"CPS Coin\",\"VLX\":\"Velas\",\"APL\":\"Apollo\",\"AYA\":\"Aryacoin\",\"BAD\":\"Badcoin\",\"BCD\":\"Bitcoin Diamond\",\"BCH\":\"Bitcoin Cash\",\"BCN\":\"Bytecoin\",\"BEAM\":\"BEAM\",\"BITB\":\"Bean Cash\",\"BLK\":\"BlackCoin\",\"BSV\":\"Bitcoin SV\",\"BTAD\":\"Bitcoin Adult\",\"BTG\":\"Bitcoin Gold\",\"BTT\":\"BitTorrent\",\"CLOAK\":\"CloakCoin\",\"CLUB\":\"ClubCoin\",\"CRW\":\"Crown\",\"CRYP\":\"CrypticCoin\",\"CRYT\":\"CryTrExCoin\",\"CURE\":\"CureCoin\",\"DASH\":\"DASH\",\"DCR\":\"Decred\",\"DEV\":\"DeviantCoin\",\"DGB\":\"DigiByte\",\"DOGE\":\"Dogecoin\",\"EBST\":\"eBoost\",\"EOS\":\"EOS\",\"ETC\":\"Ether Classic\",\"ETH\":\"Ethereum\",\"ETN\":\"Electroneum\",\"EUNO\":\"EUNO\",\"EXP\":\"EXP\",\"Expanse\":\"Expanse\",\"FLASH\":\"FLASH\",\"GAME\":\"GameCredits\",\"GLC\":\"Goldcoin\",\"GRS\":\"Groestlcoin\",\"KMD\":\"Komodo\",\"LOKI\":\"LOKI\",\"LSK\":\"LSK\",\"MAID\":\"MaidSafeCoin\",\"MUE\":\"MonetaryUnit\",\"NAV\":\"NAV Coin\",\"NEO\":\"NEO\",\"NMC\":\"Namecoin\",\"NVST\":\"NVO Token\",\"NXT\":\"NXT\",\"OMNI\":\"OMNI\",\"PINK\":\"PinkCoin\",\"PIVX\":\"PIVX\",\"POT\":\"PotCoin\",\"PPC\":\"Peercoin\",\"PROC\":\"ProCurrency\",\"PURA\":\"PURA\",\"QTUM\":\"QTUM\",\"RES\":\"Resistance\",\"RVN\":\"Ravencoin\",\"RVR\":\"RevolutionVR\",\"SBD\":\"Steem Dollars\",\"SMART\":\"SmartCash\",\"SOXAX\":\"SOXAX\",\"STEEM\":\"STEEM\",\"STRAT\":\"STRAT\",\"SYS\":\"Syscoin\",\"TPAY\":\"TokenPay\",\"TRIGGERS\":\"Triggers\",\"TRX\":\" TRON\",\"UBQ\":\"Ubiq\",\"UNIT\":\"UniversalCurrency\",\"USDT\":\"Tether USD (Omni Layer)\",\"USDT.BEP20\":\"Tether USD (BSC Chain)\",\"USDT.ERC20\":\"Tether USD (ERC20)\",\"USDT.TRC20\":\"Tether USD (Tron/TRC20)\",\"VTC\":\"Vertcoin\",\"WAVES\":\"Waves\",\"XCP\":\"Counterparty\",\"XEM\":\"NEM\",\"XMR\":\"Monero\",\"XSN\":\"Stakenet\",\"XSR\":\"SucreCoin\",\"XVG\":\"VERGE\",\"XZC\":\"ZCoin\",\"ZEC\":\"ZCash\",\"ZEN\":\"Horizen\"}', 1, NULL, NULL, '2019-09-14 07:14:22', '2024-04-21 01:47:54'),
(8, 0, 506, 'Coinbase Commerce', 'CoinbaseCommerce', 0, '{\"api_key\":{\"title\":\"API Key\",\"global\":true,\"value\":\"-----------------\"},\"secret\":{\"title\":\"Webhook Shared Secret\",\"global\":true,\"value\":\"-----------------\"}}', '{\"USD\":\"USD\",\"EUR\":\"EUR\",\"JPY\":\"JPY\",\"GBP\":\"GBP\",\"AUD\":\"AUD\",\"CAD\":\"CAD\",\"CHF\":\"CHF\",\"CNY\":\"CNY\",\"SEK\":\"SEK\",\"NZD\":\"NZD\",\"MXN\":\"MXN\",\"SGD\":\"SGD\",\"HKD\":\"HKD\",\"NOK\":\"NOK\",\"KRW\":\"KRW\",\"TRY\":\"TRY\",\"RUB\":\"RUB\",\"INR\":\"INR\",\"BRL\":\"BRL\",\"ZAR\":\"ZAR\",\"AED\":\"AED\",\"AFN\":\"AFN\",\"ALL\":\"ALL\",\"AMD\":\"AMD\",\"ANG\":\"ANG\",\"AOA\":\"AOA\",\"ARS\":\"ARS\",\"AWG\":\"AWG\",\"AZN\":\"AZN\",\"BAM\":\"BAM\",\"BBD\":\"BBD\",\"BDT\":\"BDT\",\"BGN\":\"BGN\",\"BHD\":\"BHD\",\"BIF\":\"BIF\",\"BMD\":\"BMD\",\"BND\":\"BND\",\"BOB\":\"BOB\",\"BSD\":\"BSD\",\"BTN\":\"BTN\",\"BWP\":\"BWP\",\"BYN\":\"BYN\",\"BZD\":\"BZD\",\"CDF\":\"CDF\",\"CLF\":\"CLF\",\"CLP\":\"CLP\",\"COP\":\"COP\",\"CRC\":\"CRC\",\"CUC\":\"CUC\",\"CUP\":\"CUP\",\"CVE\":\"CVE\",\"CZK\":\"CZK\",\"DJF\":\"DJF\",\"DKK\":\"DKK\",\"DOP\":\"DOP\",\"DZD\":\"DZD\",\"EGP\":\"EGP\",\"ERN\":\"ERN\",\"ETB\":\"ETB\",\"FJD\":\"FJD\",\"FKP\":\"FKP\",\"GEL\":\"GEL\",\"GGP\":\"GGP\",\"GHS\":\"GHS\",\"GIP\":\"GIP\",\"GMD\":\"GMD\",\"GNF\":\"GNF\",\"GTQ\":\"GTQ\",\"GYD\":\"GYD\",\"HNL\":\"HNL\",\"HRK\":\"HRK\",\"HTG\":\"HTG\",\"HUF\":\"HUF\",\"IDR\":\"IDR\",\"ILS\":\"ILS\",\"IMP\":\"IMP\",\"IQD\":\"IQD\",\"IRR\":\"IRR\",\"ISK\":\"ISK\",\"JEP\":\"JEP\",\"JMD\":\"JMD\",\"JOD\":\"JOD\",\"KES\":\"KES\",\"KGS\":\"KGS\",\"KHR\":\"KHR\",\"KMF\":\"KMF\",\"KPW\":\"KPW\",\"KWD\":\"KWD\",\"KYD\":\"KYD\",\"KZT\":\"KZT\",\"LAK\":\"LAK\",\"LBP\":\"LBP\",\"LKR\":\"LKR\",\"LRD\":\"LRD\",\"LSL\":\"LSL\",\"LYD\":\"LYD\",\"MAD\":\"MAD\",\"MDL\":\"MDL\",\"MGA\":\"MGA\",\"MKD\":\"MKD\",\"MMK\":\"MMK\",\"MNT\":\"MNT\",\"MOP\":\"MOP\",\"MRO\":\"MRO\",\"MUR\":\"MUR\",\"MVR\":\"MVR\",\"MWK\":\"MWK\",\"MYR\":\"MYR\",\"MZN\":\"MZN\",\"NAD\":\"NAD\",\"NGN\":\"NGN\",\"NIO\":\"NIO\",\"NPR\":\"NPR\",\"OMR\":\"OMR\",\"PAB\":\"PAB\",\"PEN\":\"PEN\",\"PGK\":\"PGK\",\"PHP\":\"PHP\",\"PKR\":\"PKR\",\"PLN\":\"PLN\",\"PYG\":\"PYG\",\"QAR\":\"QAR\",\"RON\":\"RON\",\"RSD\":\"RSD\",\"RWF\":\"RWF\",\"SAR\":\"SAR\",\"SBD\":\"SBD\",\"SCR\":\"SCR\",\"SDG\":\"SDG\",\"SHP\":\"SHP\",\"SLL\":\"SLL\",\"SOS\":\"SOS\",\"SRD\":\"SRD\",\"SSP\":\"SSP\",\"STD\":\"STD\",\"SVC\":\"SVC\",\"SYP\":\"SYP\",\"SZL\":\"SZL\",\"THB\":\"THB\",\"TJS\":\"TJS\",\"TMT\":\"TMT\",\"TND\":\"TND\",\"TOP\":\"TOP\",\"TTD\":\"TTD\",\"TWD\":\"TWD\",\"TZS\":\"TZS\",\"UAH\":\"UAH\",\"UGX\":\"UGX\",\"UYU\":\"UYU\",\"UZS\":\"UZS\",\"VEF\":\"VEF\",\"VND\":\"VND\",\"VUV\":\"VUV\",\"WST\":\"WST\",\"XAF\":\"XAF\",\"XAG\":\"XAG\",\"XAU\":\"XAU\",\"XCD\":\"XCD\",\"XDR\":\"XDR\",\"XOF\":\"XOF\",\"XPD\":\"XPD\",\"XPF\":\"XPF\",\"XPT\":\"XPT\",\"YER\":\"YER\",\"ZMW\":\"ZMW\",\"ZWL\":\"ZWL\"}\r\n\r\n', 0, '{\"endpoint\":{\"title\": \"Webhook Endpoint\",\"value\":\"ipn.CoinbaseCommerce\"}}', NULL, '2019-09-14 07:14:22', '2025-05-03 12:41:44'),
(9, 0, 113, 'Paypal Express', 'PaypalSdk', 0, '{\"clientId\":{\"title\":\"Paypal Client ID\",\"global\":true,\"value\":\"-----------------\"},\"clientSecret\":{\"title\":\"Client Secret\",\"global\":true,\"value\":\"-----------------\"}}', '{\"AUD\":\"AUD\",\"BRL\":\"BRL\",\"CAD\":\"CAD\",\"CZK\":\"CZK\",\"DKK\":\"DKK\",\"EUR\":\"EUR\",\"HKD\":\"HKD\",\"HUF\":\"HUF\",\"INR\":\"INR\",\"ILS\":\"ILS\",\"JPY\":\"JPY\",\"MYR\":\"MYR\",\"MXN\":\"MXN\",\"TWD\":\"TWD\",\"NZD\":\"NZD\",\"NOK\":\"NOK\",\"PHP\":\"PHP\",\"PLN\":\"PLN\",\"GBP\":\"GBP\",\"RUB\":\"RUB\",\"SGD\":\"SGD\",\"SEK\":\"SEK\",\"CHF\":\"CHF\",\"THB\":\"THB\",\"USD\":\"$\"}', 0, NULL, NULL, '2019-09-14 07:14:22', '2025-05-03 12:42:42'),
(10, 0, 114, 'Stripe Checkout', 'StripeV3', 0, '{\"secret_key\":{\"title\":\"Secret Key\",\"global\":true,\"value\":\"-----------------\"},\"publishable_key\":{\"title\":\"PUBLISHABLE KEY\",\"global\":true,\"value\":\"-----------------\"},\"end_point\":{\"title\":\"End Point Secret\",\"global\":true,\"value\":\"-----------------\"}}', '{\"USD\":\"USD\",\"AUD\":\"AUD\",\"BRL\":\"BRL\",\"CAD\":\"CAD\",\"CHF\":\"CHF\",\"DKK\":\"DKK\",\"EUR\":\"EUR\",\"GBP\":\"GBP\",\"HKD\":\"HKD\",\"INR\":\"INR\",\"JPY\":\"JPY\",\"MXN\":\"MXN\",\"MYR\":\"MYR\",\"NOK\":\"NOK\",\"NZD\":\"NZD\",\"PLN\":\"PLN\",\"SEK\":\"SEK\",\"SGD\":\"SGD\"}', 0, '{\"webhook\":{\"title\": \"Webhook Endpoint\",\"value\":\"ipn.StripeV3\"}}', NULL, '2019-09-14 07:14:22', '2025-05-03 12:43:49'),
(11, 0, 119, 'Mercado Pago', 'MercadoPago', 0, '{\"access_token\":{\"title\":\"Access Token\",\"global\":true,\"value\":\"-----------------\"}}', '{\"USD\":\"USD\",\"CAD\":\"CAD\",\"CHF\":\"CHF\",\"DKK\":\"DKK\",\"EUR\":\"EUR\",\"GBP\":\"GBP\",\"NOK\":\"NOK\",\"PLN\":\"PLN\",\"SEK\":\"SEK\",\"AUD\":\"AUD\",\"NZD\":\"NZD\"}', 0, NULL, NULL, NULL, '2025-05-03 12:42:16'),
(12, 0, 120, 'Authorize.net', 'Authorize', 0, '{\"login_id\":{\"title\":\"Login ID\",\"global\":true,\"value\":\"-----------------\"},\"transaction_key\":{\"title\":\"Transaction Key\",\"global\":true,\"value\":\"-----------------\"}}', '{\"USD\":\"USD\",\"CAD\":\"CAD\",\"CHF\":\"CHF\",\"DKK\":\"DKK\",\"EUR\":\"EUR\",\"GBP\":\"GBP\",\"NOK\":\"NOK\",\"PLN\":\"PLN\",\"SEK\":\"SEK\",\"AUD\":\"AUD\",\"NZD\":\"NZD\"}', 0, NULL, NULL, NULL, '2025-05-03 12:41:01'),
(13, 0, 509, 'Now Payments Checkout', 'NowPaymentsCheckout', 0, '{\"api_key\":{\"title\":\"API Key\",\"global\":true,\"value\":\"----------\"},\"secret_key\":{\"title\":\"Secret Key\",\"global\":true,\"value\":\"----------\"}}', '{\"BTG\":\"BTG\",\"ETH\":\"ETH\",\"XMR\":\"XMR\",\"ZEC\":\"ZEC\",\"XVG\":\"XVG\",\"ADA\":\"ADA\",\"LTC\":\"LTC\",\"BCH\":\"BCH\",\"QTUM\":\"QTUM\",\"DASH\":\"DASH\",\"XLM\":\"XLM\",\"XRP\":\"XRP\",\"XEM\":\"XEM\",\"DGB\":\"DGB\",\"LSK\":\"LSK\",\"DOGE\":\"DOGE\",\"TRX\":\"TRX\",\"KMD\":\"KMD\",\"REP\":\"REP\",\"BAT\":\"BAT\",\"ARK\":\"ARK\",\"WAVES\":\"WAVES\",\"BNB\":\"BNB\",\"XZC\":\"XZC\",\"NANO\":\"NANO\",\"TUSD\":\"TUSD\",\"VET\":\"VET\",\"ZEN\":\"ZEN\",\"GRS\":\"GRS\",\"FUN\":\"FUN\",\"NEO\":\"NEO\",\"GAS\":\"GAS\",\"PAX\":\"PAX\",\"USDC\":\"USDC\",\"ONT\":\"ONT\",\"XTZ\":\"XTZ\",\"LINK\":\"LINK\",\"RVN\":\"RVN\",\"BNBMAINNET\":\"BNBMAINNET\",\"ZIL\":\"ZIL\",\"BCD\":\"BCD\",\"USDT\":\"USDT\",\"USDTERC20\":\"USDTERC20\",\"CRO\":\"CRO\",\"DAI\":\"DAI\",\"HT\":\"HT\",\"WABI\":\"WABI\",\"BUSD\":\"BUSD\",\"ALGO\":\"ALGO\",\"USDTTRC20\":\"USDTTRC20\",\"GT\":\"GT\",\"STPT\":\"STPT\",\"AVA\":\"AVA\",\"SXP\":\"SXP\",\"UNI\":\"UNI\",\"OKB\":\"OKB\",\"BTC\":\"BTC\"}', 1, '', NULL, NULL, '2024-04-21 01:46:31'),
(14, 0, 122, '2Checkout', 'TwoCheckout', 0, '{\"merchant_code\":{\"title\":\"Merchant Code\",\"global\":true,\"value\":\"-----------------\"},\"secret_key\":{\"title\":\"Secret Key\",\"global\":true,\"value\":\"-----------------\"}}', '{\"AFN\": \"AFN\",\"ALL\": \"ALL\",\"DZD\": \"DZD\",\"ARS\": \"ARS\",\"AUD\": \"AUD\",\"AZN\": \"AZN\",\"BSD\": \"BSD\",\"BDT\": \"BDT\",\"BBD\": \"BBD\",\"BZD\": \"BZD\",\"BMD\": \"BMD\",\"BOB\": \"BOB\",\"BWP\": \"BWP\",\"BRL\": \"BRL\",\"GBP\": \"GBP\",\"BND\": \"BND\",\"BGN\": \"BGN\",\"CAD\": \"CAD\",\"CLP\": \"CLP\",\"CNY\": \"CNY\",\"COP\": \"COP\",\"CRC\": \"CRC\",\"HRK\": \"HRK\",\"CZK\": \"CZK\",\"DKK\": \"DKK\",\"DOP\": \"DOP\",\"XCD\": \"XCD\",\"EGP\": \"EGP\",\"EUR\": \"EUR\",\"FJD\": \"FJD\",\"GTQ\": \"GTQ\",\"HKD\": \"HKD\",\"HNL\": \"HNL\",\"HUF\": \"HUF\",\"INR\": \"INR\",\"IDR\": \"IDR\",\"ILS\": \"ILS\",\"JMD\": \"JMD\",\"JPY\": \"JPY\",\"KZT\": \"KZT\",\"KES\": \"KES\",\"LAK\": \"LAK\",\"MMK\": \"MMK\",\"LBP\": \"LBP\",\"LRD\": \"LRD\",\"MOP\": \"MOP\",\"MYR\": \"MYR\",\"MVR\": \"MVR\",\"MRO\": \"MRO\",\"MUR\": \"MUR\",\"MXN\": \"MXN\",\"MAD\": \"MAD\",\"NPR\": \"NPR\",\"TWD\": \"TWD\",\"NZD\": \"NZD\",\"NIO\": \"NIO\",\"NOK\": \"NOK\",\"PKR\": \"PKR\",\"PGK\": \"PGK\",\"PEN\": \"PEN\",\"PHP\": \"PHP\",\"PLN\": \"PLN\",\"QAR\": \"QAR\",\"RON\": \"RON\",\"RUB\": \"RUB\",\"WST\": \"WST\",\"SAR\": \"SAR\",\"SCR\": \"SCR\",\"SGD\": \"SGD\",\"SBD\": \"SBD\",\"ZAR\": \"ZAR\",\"KRW\": \"KRW\",\"LKR\": \"LKR\",\"SEK\": \"SEK\",\"CHF\": \"CHF\",\"SYP\": \"SYP\",\"THB\": \"THB\",\"TOP\": \"TOP\",\"TTD\": \"TTD\",\"TRY\": \"TRY\",\"UAH\": \"UAH\",\"AED\": \"AED\",\"USD\": \"USD\",\"VUV\": \"VUV\",\"VND\": \"VND\",\"XOF\": \"XOF\",\"YER\": \"YER\"}', 1, '{\"approved_url\":{\"title\": \"Approved URL\",\"value\":\"ipn.TwoCheckout\"}}', NULL, NULL, '2025-05-03 12:44:07'),
(15, 0, 123, 'Checkout', 'Checkout', 0, '{\"secret_key\":{\"title\":\"Secret Key\",\"global\":true,\"value\":\"-----------------\"},\"public_key\":{\"title\":\"Public Key\",\"global\":true,\"value\":\"-----------------\"},\"processing_channel_id\":{\"title\":\"Processing Channel\",\"global\":true,\"value\":\"-----------------\"}}', '{\"USD\":\"USD\",\"EUR\":\"EUR\",\"GBP\":\"GBP\",\"HKD\":\"HKD\",\"AUD\":\"AUD\",\"CAN\":\"CAN\",\"CHF\":\"CHF\",\"SGD\":\"SGD\",\"JPY\":\"JPY\",\"NZD\":\"NZD\"}', 0, NULL, NULL, NULL, '2025-05-03 12:41:25'),
(16, 0, 110, 'RazorPay', 'Razorpay', 0, '{\"key_id\":{\"title\":\"Key Id\",\"global\":true,\"value\":\"-----------------\"},\"key_secret\":{\"title\":\"Key Secret \",\"global\":true,\"value\":\"-----------------\"}}', '{\"INR\":\"INR\"}', 0, NULL, NULL, NULL, '2025-05-03 12:43:09'),
(19, 0, 111, 'Stripe Storefront', 'StripeJs', 0, '{\"secret_key\":{\"title\":\"Secret Key\",\"global\":true,\"value\":\"-----------------\"},\"publishable_key\":{\"title\":\"PUBLISHABLE KEY\",\"global\":true,\"value\":\"-----------------\"}}', '{\"USD\":\"USD\",\"AUD\":\"AUD\",\"BRL\":\"BRL\",\"CAD\":\"CAD\",\"CHF\":\"CHF\",\"DKK\":\"DKK\",\"EUR\":\"EUR\",\"GBP\":\"GBP\",\"HKD\":\"HKD\",\"INR\":\"INR\",\"JPY\":\"JPY\",\"MXN\":\"MXN\",\"MYR\":\"MYR\",\"NOK\":\"NOK\",\"NZD\":\"NZD\",\"PLN\":\"PLN\",\"SEK\":\"SEK\",\"SGD\":\"SGD\"}', 0, NULL, NULL, NULL, '2025-05-03 12:43:31');

-- --------------------------------------------------------

--
-- Table structure for table `gateway_currencies`
--

CREATE TABLE `gateway_currencies` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `symbol` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `method_code` int UNSIGNED DEFAULT NULL,
  `gateway_alias` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `min_amount` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `max_amount` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `percent_charge` decimal(5,2) NOT NULL DEFAULT '0.00',
  `fixed_charge` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `rate` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `gateway_parameter` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `installments`
--

CREATE TABLE `installments` (
  `id` bigint UNSIGNED NOT NULL,
  `installmentable_id` bigint UNSIGNED NOT NULL,
  `installmentable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `installment_date` date DEFAULT NULL,
  `given_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

CREATE TABLE `languages` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_default` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `languages`
--

INSERT INTO `languages` (`id`, `name`, `code`, `is_default`, `status`, `created_at`, `updated_at`) VALUES
(1, 'English', 'en', 1, 1, NULL, '2025-05-01 14:39:59');

-- --------------------------------------------------------

--
-- Table structure for table `loans`
--

CREATE TABLE `loans` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `plan_name` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `scheme_code` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount_requested` decimal(28,8) NOT NULL,
  `form_data` json DEFAULT NULL,
  `per_installment` decimal(28,8) NOT NULL,
  `installment_interval` int UNSIGNED NOT NULL,
  `total_installment` int UNSIGNED NOT NULL,
  `given_installment` int UNSIGNED NOT NULL DEFAULT '0',
  `delay_duration` int UNSIGNED NOT NULL,
  `per_installment_late_fee` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `total_late_fees` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `late_fee_last_notified_at` timestamp NULL DEFAULT NULL,
  `status` tinyint UNSIGNED NOT NULL DEFAULT '3' COMMENT '0 -> rejected, 1 -> running, 2 -> paid, 3 -> pending',
  `approved_at` timestamp NULL DEFAULT NULL,
  `admin_feedback` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `loan_plans`
--

CREATE TABLE `loan_plans` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `minimum_amount` decimal(28,8) NOT NULL,
  `maximum_amount` decimal(28,8) NOT NULL,
  `installment_rate` decimal(5,2) NOT NULL,
  `installment_interval` int UNSIGNED NOT NULL,
  `total_installments` int UNSIGNED NOT NULL,
  `instruction` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `delay_duration` int UNSIGNED NOT NULL,
  `fixed_charge` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `percentage_charge` decimal(5,2) NOT NULL DEFAULT '0.00',
  `form_id` bigint UNSIGNED DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0 -> Plan is inactive, 1 -> Plan is active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\Admin', 1);

-- --------------------------------------------------------

--
-- Table structure for table `money_transfers`
--

CREATE TABLE `money_transfers` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `beneficiary_id` bigint UNSIGNED DEFAULT NULL,
  `trx` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(28,8) NOT NULL,
  `charge` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `wire_transfer_payload` json DEFAULT NULL,
  `status` tinyint UNSIGNED NOT NULL DEFAULT '2' COMMENT '0 -> failed, 1 -> completed, 2 -> pending',
  `rejection_reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notification_templates`
--

CREATE TABLE `notification_templates` (
  `id` bigint UNSIGNED NOT NULL,
  `act` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subj` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_body` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `sms_body` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `shortcodes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `email_status` tinyint(1) NOT NULL DEFAULT '1',
  `sms_status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notification_templates`
--

INSERT INTO `notification_templates` (`id`, `act`, `name`, `subj`, `email_body`, `sms_body`, `shortcodes`, `email_status`, `sms_status`, `created_at`, `updated_at`) VALUES
(1, 'BAL_ADD', 'Balance - Added', 'Your Account has been Credited', '<p>{{amount}} {{site_currency}} has been added to your account.</p><p>Transaction Number : {{trx}}</p><p>Your Current Balance is :&nbsp;{{post_balance}}&nbsp; {{site_currency}}<br>&nbsp;</p><p>Admin note:&nbsp;<strong>{{remark}}</strong></p>', '{{amount}} {{site_currency}} credited in your account. Your Current Balance {{post_balance}} {{site_currency}} . Transaction: #{{trx}}. Admin note is \"{{remark}}\"', '{\"trx\":\"Transaction number for the action\",\"amount\":\"Amount inserted by the admin\",\"remark\":\"Remark inserted by the admin\",\"post_balance\":\"Balance of the user after this transaction\"}', 1, 0, '2024-11-28 12:00:00', '2025-05-03 08:54:59'),
(2, 'BAL_SUB', 'Balance - Subtracted', 'Your Account has been Debited', '<div style=\"font-family: Montserrat, sans-serif;\">{{amount}} {{site_currency}} has been subtracted from your account .</div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><div style=\"font-family: Montserrat, sans-serif;\">Transaction Number : {{trx}}</div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><span style=\"color: rgb(33, 37, 41); font-family: Montserrat, sans-serif;\">Your Current Balance is :&nbsp;</span><font style=\"font-family: Montserrat, sans-serif;\"><span style=\"font-weight: bolder;\">{{post_balance}}&nbsp; {{site_currency}}</span></font><br><div><font style=\"font-family: Montserrat, sans-serif;\"><span style=\"font-weight: bolder;\"><br></span></font></div><div>Admin Note: {{remark}}</div>', '{{amount}} {{site_currency}} debited from your account. Your Current Balance {{post_balance}} {{site_currency}} . Transaction: #{{trx}}. Admin Note is {{remark}}', '{\"trx\":\"Transaction number for the action\",\"amount\":\"Amount inserted by the admin\",\"remark\":\"Remark inserted by the admin\",\"post_balance\":\"Balance of the user after this transaction\"}', 1, 0, '2021-11-03 12:00:00', '2022-04-03 02:24:11'),
(7, 'PASS_RESET_CODE', 'Password - Reset - Code', 'Password Reset', '<p>We have received a request to reset the password for your account on&nbsp;{{time}} .<br>&nbsp;</p><p>Requested From IP:&nbsp;{{ip}}&nbsp;using&nbsp;{{browser}}&nbsp;on&nbsp;{{operating_system}}&nbsp;.<br>&nbsp;</p><p>Your account recovery code is:&nbsp;&nbsp; {{code}}</p><p>If you do not wish to reset your password, please disregard this message.</p>', 'Your account recovery code is: {{code}}', '{\"code\":\"Verification code for password reset\",\"ip\":\"IP address of the user\",\"browser\":\"Browser of the user\",\"operating_system\":\"Operating system of the user\",\"time\":\"Time of the request\"}', 1, 0, '2021-11-03 12:00:00', '2024-08-10 07:18:36'),
(8, 'PASS_RESET_DONE', 'Password - Reset - Confirmation', 'You have reset your password', '<p>You have successfully reset your password.</p><p>You changed from&nbsp; IP:&nbsp;{{ip}}&nbsp;using&nbsp;{{browser}}&nbsp;on&nbsp;{{operating_system}}&nbsp;&nbsp;on&nbsp;{{time}}<br>&nbsp;</p><p>If you did not change that, please contact us as soon as possible.</p>', 'Your password has been changed successfully.', '{\"ip\":\"IP address of the user\",\"browser\":\"Browser of the user\",\"operating_system\":\"Operating system of the user\",\"time\":\"Time of the request\"}', 1, 1, '2021-11-03 12:00:00', '2024-08-10 07:18:54'),
(9, 'ADMIN_SUPPORT_REPLY', 'Support - Reply', 'Reply Support Ticket', '<div><p><span data-mce-style=\"font-size: 11pt;\" style=\"font-size: 11pt;\"><span style=\"font-weight: bolder;\">A member from our support team has replied to the following ticket:</span></span></p><p><span style=\"font-weight: bolder;\"><span data-mce-style=\"font-size: 11pt;\" style=\"font-size: 11pt;\"><span style=\"font-weight: bolder;\"><br></span></span></span></p><p><span style=\"font-weight: bolder;\">[Ticket#{{ticket_id}}] {{ticket_subject}}<br><br>Click here to reply:&nbsp; {{link}}</span></p><p>----------------------------------------------</p><p>Here is the reply :<br></p><p>{{reply}}<br></p></div><div><br style=\"font-family: Montserrat, sans-serif;\"></div>', 'Your Ticket#{{ticket_id}} :  {{ticket_subject}} has been replied.', '{\"ticket_id\":\"ID of the support ticket\",\"ticket_subject\":\"Subject  of the support ticket\",\"reply\":\"Reply made by the admin\",\"link\":\"URL to view the support ticket\"}', 1, 1, '2021-11-03 12:00:00', '2022-03-20 20:47:51'),
(10, 'EVER_CODE', 'Verification - Email', 'Please verify your email address', '<br><div><div style=\"font-family: Montserrat, sans-serif;\">Thanks For joining us.<br></div><div style=\"font-family: Montserrat, sans-serif;\">Please use the below code to verify your email address.<br></div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><div style=\"font-family: Montserrat, sans-serif;\">Your email verification code is:<font size=\"6\"><span style=\"font-weight: bolder;\">&nbsp;{{code}}</span></font></div></div>', '---', '{\"code\":\"Email verification code\"}', 1, 0, '2021-11-03 12:00:00', '2022-04-03 02:32:07'),
(11, 'SVER_CODE', 'Verification - SMS', 'Verify Your Mobile Number', '---', 'Your phone verification code is: {{code}}', '{\"code\":\"SMS Verification Code\"}', 0, 1, '2021-11-03 12:00:00', '2022-03-20 19:24:37'),
(12, 'WITHDRAW_APPROVE', 'Withdraw - Approved', 'Withdrawal Approval Notification', '<p>We are pleased to inform you that your withdrawal request has been approved by our administration team.<br>&nbsp;</p><p>Here are the details of your approved withdrawal:&nbsp;<br>&nbsp;</p><p><strong>Amount:</strong>&nbsp;{{amount}} {{site_currency}}</p><p><strong>Charge:</strong>&nbsp;{{charge}} {{site_currency}}</p><p><strong>Conversion Rate:</strong>&nbsp;1 {{site_currency}} = {{rate}} {{method_currency}}</p><p><strong>Receivable Amount:</strong>&nbsp;{{method_amount}} {{method_currency}}</p><p><strong>Withdraw&nbsp;Via:</strong>&nbsp;{{method_name}}<br>&nbsp;</p><p><strong>Transaction Number:</strong>&nbsp;{{trx}}</p><p><strong>Post Balance:</strong>&nbsp;{{post_balance}} {{site_currency}}</p><p>&nbsp;</p><p>{{admin_details}}<br>&nbsp;</p><p>Your withdrawal request has been processed successfully. If you have any further questions or require assistance, please feel free to contact us.<br>&nbsp;</p><p>Thank you for your patience and cooperation.<br>&nbsp;</p><p>Best&nbsp;regards,</p>', 'Good news! Your withdrawal request has been approved.\r\n\r\nDetails of your withdrawal:\r\n\r\nAmount: {{amount}} {{site_currency}}\r\nCharge: {{charge}} {{site_currency}}\r\nConversion Rate: 1 {{site_currency}} = {{rate}} {{method_currency}}\r\nReceivable Amount: {{method_amount}} {{method_currency}}\r\nWithdraw Via: {{method_name}}\r\nTransaction Number: {{trx}}\r\nPost Balance: {{post_balance}} {{site_currency}}\r\n\r\n{{admin_details}}\r\n\r\nFor further assistance, contact us anytime.', '{\"trx\":\"Transaction number for the withdraw\",\"amount\":\"Amount requested by the user\",\"charge\":\"Gateway charge set by the admin\",\"rate\":\"Conversion rate between base currency and method currency\",\"method_name\":\"Name of the withdraw method\",\"method_currency\":\"Currency of the withdraw method\",\"method_amount\":\"Amount after conversion between base currency and method currency\",\"post_balance\":\"Balance of the user after this transaction\",\"admin_details\":\"Details provided by the admin\"}', 1, 1, '2021-11-03 12:00:00', '2024-08-10 07:20:12'),
(13, 'WITHDRAW_REJECT', 'Withdraw - Rejected', 'Withdrawal Rejection Notification', '<p>We regret to inform you that your withdrawal request has been rejected by our administration team.<br>&nbsp;</p><p>Upon careful review, we found that there were certain discrepancies or issues that prevented us from processing your withdrawal at this time. We apologize for any inconvenience this may cause.<br>&nbsp;</p><p>Here are the details of your withdrawal:<br>&nbsp;</p><p><strong>Amount:</strong>&nbsp;{{amount}} {{site_currency}}</p><p><strong>Charge:</strong>&nbsp;{{charge}} {{site_currency}}</p><p><strong>Conversion Rate:</strong>&nbsp;1 {{site_currency}} = {{rate}} {{method_currency}}</p><p><strong>You should have received:</strong>&nbsp;{{method_amount}} {{method_currency}}</p><p><strong>Withdraw&nbsp;Via:</strong>&nbsp;{{method_name}}<br>&nbsp;</p><p><strong>Transaction Number:</strong>&nbsp;{{trx}}</p><p>&nbsp;</p><p>{{admin_details}}<br>&nbsp;</p><p>{{amount}} {{site_currency}} has been refunded to your account, and your current balance is {{post_balance}} {{site_currency}}.</p><p>&nbsp;</p><p>If you have any questions or concerns regarding this decision, or if you believe there has been a misunderstanding, please don\'t hesitate to reach out to us. We are here to assist you and address any concerns you may have.</p><p>Thank you for your understanding.<br>&nbsp;</p><p>Best&nbsp;regards,</p>', 'We regret to inform you that your withdrawal request of {{amount}} {{site_currency}} via {{method_name}} has been rejected.\r\n\r\nDetails of your withdrawal:\r\n\r\nAmount: {{amount}} {{site_currency}}\r\nCharge: {{charge}} {{site_currency}}\r\nConversion Rate: 1 {{site_currency}} = {{rate}} {{method_currency}}\r\nYou should have received: {{method_amount}} {{method_currency}}\r\nWithdraw Via: {{method_name}}\r\nTransaction Number: {{trx}}\r\n\r\n{{admin_details}}\r\n\r\n{{amount}} {{site_currency}} has been refunded to your account. Your current balance is {{post_balance}} {{site_currency}}.\r\n\r\nIf you have any questions, feel free to reach out.\r\n\r\nBest regards,', '{\"trx\":\"Transaction number for the withdraw\",\"amount\":\"Amount requested by the user\",\"charge\":\"Gateway charge set by the admin\",\"rate\":\"Conversion rate between base currency and method currency\",\"method_name\":\"Name of the withdraw method\",\"method_currency\":\"Currency of the withdraw method\",\"method_amount\":\"Amount after conversion between base currency and method currency\",\"post_balance\":\"Balance of the user after fter this action\",\"admin_details\":\"Rejection message by the admin\"}', 1, 1, '2021-11-03 12:00:00', '2024-08-10 07:20:52'),
(14, 'WITHDRAW_REQUEST', 'Withdraw - Requested', 'Withdrawal Request Confirmation', '<p>We are pleased to inform you that your withdrawal request has been successfully submitted.<br>&nbsp;</p><p>Here are the details of your withdrawal:&nbsp;<br>&nbsp;</p><p><strong>Amount:</strong>&nbsp;{{amount}} {{site_currency}}</p><p><strong>Charge:</strong>&nbsp;{{charge}} {{site_currency}}</p><p><strong>Conversion Rate:</strong>&nbsp;1 {{site_currency}} = {{rate}} {{method_currency}}</p><p><strong>Receivable Amount:</strong>&nbsp;{{method_amount}} {{method_currency}}</p><p><strong>Withdraw&nbsp;Via:</strong>&nbsp;{{method_name}}<br>&nbsp;</p><p><strong>Transaction Number:</strong>&nbsp;{{trx}}</p><p><strong>Post Balance:</strong>&nbsp;{{post_balance}} {{site_currency}}</p><p>&nbsp;</p><p>If you have any questions or concerns regarding this transaction, please feel free to contact us. Thank you for choosing {{site_name}}.</p><p>Best&nbsp;regards,</p>', 'Your withdrawal request of {{amount}} {{site_currency}} via {{method_name}} has been successfully submitted.\r\n\r\nDetails of your withdrawal:\r\n\r\nAmount: {{amount}} {{site_currency}}\r\nCharge: {{charge}} {{site_currency}}\r\nConversion Rate: 1 {{site_currency}} = {{rate}} {{method_currency}}\r\nReceivable Amount: {{method_amount}} {{method_currency}}\r\nWithdraw Via: {{method_name}}\r\nTransaction Number: {{trx}}\r\nPost Balance: {{post_balance}} {{site_currency}}\r\n\r\nFor any queries, reach out to us.', '{\"trx\":\"Transaction number for the withdraw\",\"amount\":\"Amount requested by the user\",\"charge\":\"Gateway charge set by the admin\",\"rate\":\"Conversion rate between base currency and method currency\",\"method_name\":\"Name of the withdraw method\",\"method_currency\":\"Currency of the withdraw method\",\"method_amount\":\"Amount after conversion between base currency and method currency\",\"post_balance\":\"Balance of the user after this transaction\"}', 1, 1, '2021-11-03 12:00:00', '2024-08-10 07:21:25'),
(15, 'DEFAULT', 'Default Template', '{{subject}}', '{{message}}', '{{message}}', '{\"subject\":\"Subject\",\"message\":\"Message\"}', 1, 0, '2019-09-14 13:14:22', '2024-02-18 08:37:31'),
(16, 'KYC_APPROVE', 'KYC Approved Successfully', 'KYC has been approved', NULL, NULL, '[]', 1, 1, NULL, NULL),
(17, 'KYC_REJECT', 'KYC Rejected Successfully', 'KYC has been rejected', NULL, NULL, '[]', 1, 1, NULL, NULL),
(29, 'DEPOSIT_COMPLETE', 'Deposit - Automated - Successful', 'Deposit Confirmation - Your Recent Deposit was Successful!', '<p>We are pleased to inform you that your recent deposit of {{amount}} {{site_currency}} has been successfully credited to your account. Thank you for choosing our services!</p><p>Here are the details of your transaction:</p><p>&nbsp;</p><p><strong>Deposit Amount:</strong>&nbsp;{{amount}} {{site_currency}}</p><p><strong>Charge:</strong>&nbsp;{{charge}} {{site_currency}}</p><p><strong>Conversion Rate:</strong>&nbsp;1 {{site_currency}} = {{rate}} {{method_currency}}</p><p><strong>Received Amount:</strong>&nbsp;{{method_amount}} {{method_currency}}</p><p><strong>Paid&nbsp;via:</strong>&nbsp;{{method_name}}<br>&nbsp;</p><p><strong>Transaction Number:</strong>&nbsp;{{trx}}</p><p><strong>Post Balance:&nbsp;</strong>{{post_balance}}&nbsp;{{site_currency}}<br>&nbsp;</p><p>You can view the updated balance and transaction history by logging into your account.</p><p>If you have any questions or need further assistance, please do not hesitate to contact our support team. We are here to help!<br>&nbsp;</p><p>Thank you for your continued trust in us.<br>&nbsp;</p><p>Best regards,</p>', 'Your deposit of {{amount}} {{site_currency}} has been successfully credited to your account. Thank you!', '{\"trx\":\"Transaction number for the deposit\",\"amount\":\"Amount inserted by the user\",\"charge\":\"Gateway charge set by the admin\",\"rate\":\"Conversion rate between base currency and method currency\",\"method_name\":\"Name of the payment gateway\",\"method_currency\":\"Currency of the payment gateway\",\"method_amount\":\"Amount after conversion between base currency and gateway currency\",\"post_balance\":\"Balance of the user after this transaction\"}', 1, 1, '2024-05-25 12:00:00', '2024-08-10 07:06:22'),
(30, 'DEPOSIT_REQUEST', 'Deposit - Manual - Requested', 'Deposit Request Received - Awaiting Approval', '<p>Thank you for your recent deposit request of {{amount}} {{site_currency}}&nbsp;to your account. We have successfully received your request and it is now pending approval from our admin team.<br>&nbsp;</p><p>Here are the details of your transaction:</p><p>&nbsp;</p><p><strong>Deposit Amount:</strong>&nbsp;{{amount}} {{site_currency}}</p><p><strong>Charge:</strong>&nbsp;{{charge}} {{site_currency}}</p><p><strong>Conversion Rate:</strong>&nbsp;1 {{site_currency}} = {{rate}} {{method_currency}}</p><p><strong>Receivable Amount:</strong>&nbsp;{{method_amount}} {{method_currency}}</p><p><strong>Paid&nbsp;via:</strong>&nbsp;{{method_name}}<br>&nbsp;</p><p><strong>Transaction Number:</strong>&nbsp;{{trx}}</p><p>&nbsp;</p><p>Our team is reviewing your request to ensure everything is in order. You will receive a follow-up email once the deposit has been approved and credited to your account. This process typically takes 2 days.<br>&nbsp;</p><p>In the meantime, you can track the status of your request by logging into your account.<br>&nbsp;</p><p>If you have any questions or need further assistance, please do not hesitate to contact our support team. We are here to help!<br>&nbsp;</p><p>Thank you for your patience and for choosing us.</p><p>Best regards,</p>', 'Your deposit request of {{amount}} {{site_currency}} is pending admin approval. We\'ll notify you once it\'s approved. Thank you!', '{\"trx\":\"Transaction number for the deposit\",\"amount\":\"Amount inserted by the user\",\"charge\":\"Gateway charge set by the admin\",\"rate\":\"Conversion rate between base currency and method currency\",\"method_name\":\"Name of the payment gateway\",\"method_currency\":\"Currency of the payment gateway\",\"method_amount\":\"Amount after conversion between base currency and gateway currency\"}', 1, 1, '2024-05-25 12:00:00', '2024-08-10 07:07:32'),
(31, 'DEPOSIT_APPROVE', 'Deposit - Manual - Approved', 'Deposit Request Approved - Funds Successfully Credited', '<p>We are delighted to inform you that your recent deposit request of {{amount}} {{site_currency}}&nbsp;has been approved by our admin team and the funds have been successfully credited to your account.<br>&nbsp;</p><p>Here are the details of your transaction:<br>&nbsp;</p><p><strong>Deposit Amount:</strong>&nbsp;{{amount}} {{site_currency}}</p><p><strong>Charge:</strong>&nbsp;{{charge}} {{site_currency}}</p><p><strong>Conversion Rate:</strong>&nbsp;1 {{site_currency}} = {{rate}} {{method_currency}}</p><p><strong>Received Amount:</strong>&nbsp;{{method_amount}} {{method_currency}}</p><p><strong>Paid&nbsp;via:</strong>&nbsp;{{method_name}}<br>&nbsp;</p><p><strong>Transaction Number:</strong>&nbsp;{{trx}}</p><p><strong>Post Balance:&nbsp;</strong>{{post_balance}}&nbsp;{{site_currency}}<br>&nbsp;</p><p>You can view the updated balance and transaction history by logging into your account.<br>&nbsp;</p><p>If you have any questions or need further assistance, please do not hesitate to contact our support team. We are here to help!<br>&nbsp;</p><p>Thank you for your patience and for choosing us.</p><p>Best regards,</p>', 'Good news! Your deposit request of {{amount}} {{site_currency}} has been approved and the funds are now credited to your account. Thank you!', '{\"trx\":\"Transaction number for the deposit\",\"amount\":\"Amount inserted by the user\",\"charge\":\"Gateway charge set by the admin\",\"rate\":\"Conversion rate between base currency and method currency\",\"method_name\":\"Name of the payment gateway\",\"method_currency\":\"Currency of the payment gateway\",\"method_amount\":\"Amount after conversion between base currency and gateway currency\",\"post_balance\":\"Balance of the user after this transaction\"}', 1, 1, '2024-05-25 12:00:00', '2024-08-10 07:05:15'),
(32, 'DEPOSIT_REJECT', 'Deposit - Manual - Rejected', 'Deposit Request Update - Request Rejected', '<p>We regret to inform you that your recent deposit request of {{amount}} {{site_currency}} has been reviewed and unfortunately, it has been rejected by our admin team.<br>&nbsp;</p><p>Here are the details of your transaction:<br>&nbsp;</p><p><strong>Requested Amount:</strong>&nbsp;{{amount}} {{site_currency}}<br>&nbsp;</p><p><strong>Charge:</strong>&nbsp;{{charge}} {{site_currency}}</p><p><strong>Conversion Rate:</strong>&nbsp;1 {{site_currency}} = {{rate}} {{method_currency}}</p><p><strong>Paid Amount:</strong>&nbsp;{{method_amount}} {{method_currency}}</p><p><strong>Paid&nbsp;via:</strong>&nbsp;{{method_name}}<br>&nbsp;</p><p><strong>Transaction Number:</strong>&nbsp;{{trx}}</p><p><strong>Rejection Message:</strong>&nbsp;</p><p>{{rejection_message}}</p><p>&nbsp;</p><p>We understand that this may be disappointing news. Please rest assured that no funds have been debited from your account for this request.<br>&nbsp;</p><p>If you believe this rejection was in error or if you have any questions, please do not hesitate to contact our support team. We are here to assist you in resolving any issues and to guide you through the process if you wish to submit a new deposit request.<br>&nbsp;</p><p>Thank you for your understanding and for choosing us.</p><p>Best regards,</p>', 'We\'re sorry to inform you that your deposit request of {{amount}} {{site_currency}} has been rejected. For details, please check your email or contact our support team. Thank you.', '{\"trx\":\"The transaction number for the plan subscription\",\"amount\":\"Price of the subscription plan\",\"charge\":\"Gateway charge set by the admin\",\"rate\":\"Conversion rate between base currency and method currency\",\"method_name\":\"Name of the payment gateway\",\"method_currency\":\"Currency of the payment gateway\",\"method_amount\":\"Amount after conversion between base currency and gateway currency\",\"rejection_message\":\"Rejection message by the admin\"}', 1, 1, '2021-11-03 12:00:00', '2024-08-10 07:07:07'),
(48, 'NEW_ADMIN_STAFF', 'Admin Created', 'Admin Account Created', '<p>We are pleased to inform you that your admin account has been successfully created. Below are your account details:</p><p>&nbsp;</p><ul><li><strong>Username: </strong>{{username}}</li><li><strong>Password: </strong>{{password}}</li></ul><p>&nbsp;</p><p>Please note that for security reasons, we recommend changing your password after your first login.</p><p>&nbsp;</p><p>You can access the admin portal using the following link:</p><p>{{url}}</p><p>&nbsp;</p><p>Steps to change your password:</p><ol><li>Log in with the credentials provided above.</li><li>Navigate to your profile.</li><li>Select \"Password\" tab.</li></ol><p>&nbsp;</p><p>If you encounter any issues or need assistance, feel free to contact our support team. We look forward to your contributions to the team!</p><p>&nbsp;</p><p>Best regards,</p>', NULL, '{\"username\":\"Username for the new admin staff\",\"password\":\"Assigned password when creating the admin staff\",\"url\":\"The login URL for the admin panel\"}', 1, 0, '2024-11-28 12:00:00', '2024-11-28 07:35:12'),
(49, 'NEW_BRANCH_STAFF', 'Branch Staff Created', 'Branch Staff Account Has Been Created', '<p>We are pleased to inform you that your account has been successfully created as part of our new branch team. You can now access your account and begin using the system with the following details:</p><p>&nbsp;</p><ul><li><strong>Username: </strong>{{username}}</li><li><strong>Password: </strong>{{password}}</li><li><strong>Login Link: </strong>{{url}}</li></ul><p>&nbsp;</p><p>Please make sure to change your password upon your first login for security reasons. If you encounter any issues or have questions regarding the system, feel free to reach out to our support team.</p><p>&nbsp;</p><p>Welcome aboard, and we look forward to your contributions to the team!</p><p>&nbsp;</p><p>Best regards,</p>', NULL, '{\"username\":\"Username for the new admin staff\",\"password\":\"Assigned password when creating the admin staff\",\"url\":\"The login URL for the admin panel\"}', 1, 0, '2024-11-30 12:00:00', '2024-11-30 11:46:31'),
(50, 'ACCOUNT_OPENED', 'New Account Has Opened', 'Welcome to {{site_name}}! Your Account Details and Next Steps', '<p>We’re excited to welcome you to {{site_name}}. Your new bank account has been successfully created! Below are your account details:</p><p>&nbsp;</p><ul><li><strong>Username:</strong> {{username}}</li><li><strong>Email Address:</strong> {{email_address}}</li><li><strong>Password:</strong> {{password}}</li><li><strong>Login URL:</strong> {{url}}</li></ul><p>&nbsp;</p><p>Please use these credentials to access your account and manage your banking needs online.</p><p>&nbsp;</p><p><strong>Next Step: Complete Your KYC Verification</strong></p><p>To ensure the security of your account and comply with regulatory requirements, we kindly request you to complete your KYC (Know Your Customer) details after logging in. This step is mandatory to verify your identity and activate all account features.</p><p>&nbsp;</p><p><strong>Important:</strong></p><ul><li>Log in using the provided credentials.</li><li>Navigate to the <strong>KYC Section</strong> in the dashboard page.</li><li>Fill out the required information and upload the necessary documents.</li></ul><p>&nbsp;</p><p><strong>Note:</strong> Your account will remain partially restricted until the KYC process is successfully completed.</p><p>&nbsp;</p><p><strong>Security Reminder:</strong></p><p>For your protection, please change your password after your first login. Always keep your login details confidential and avoid sharing them with others.</p><p>&nbsp;</p><p>If you have any questions or require assistance with the KYC process, feel free to contact our support team.</p><p>&nbsp;</p><p>Thank you for choosing {{site_name}}. We look forward to serving you.</p><p>&nbsp;</p><p>Best regards,</p>', NULL, '{\"username\":\"The username for the newly created bank account\",\"email_address\":\"The email address associated with the newly created bank account\",\"password\":\"The password for the newly created bank account\",\"url\":\"The login URL for the bank\'s user portal\"}', 1, 0, '2024-12-14 12:00:00', '2024-12-14 06:15:41'),
(51, 'DEPOSIT_FROM_BRANCH', 'Deposit From Branch', 'Deposit Confirmation for Your Account', '<p>We are pleased to inform you that a deposit has been successfully made to your bank account. Below are the details of the transaction for your reference:</p><p>&nbsp;</p><ul><li><strong>Account Number: </strong>{{account_number}}</li><li><strong>Deposit Amount: </strong>{{amount}} {{site_currency}}</li><li><strong>Branch Name: </strong>{{branch}}</li><li><strong>Account Officer: </strong>{{staff}}</li><li><strong>Transaction Number: </strong>{{trx}}</li><li><strong>Post Balance: </strong>{{post_balance}} {{site_currency}}</li></ul><p>&nbsp;</p><p>If you have any questions regarding this transaction or need further assistance, feel free to contact our support team.</p><p>&nbsp;</p><p>Thank you for banking with us.</p><p>&nbsp;</p><p>Best regards,</p>', 'A deposit of {{amount}} {{site_currency}} has been successfully made to your account {{ account_number }} from the {{ branch }} by Account Officer {{ staff }}.\r\n\r\nTransaction Number: {{ trx }}\r\nPost Balance: {{post_balance}} {{site_currency}}\r\n\r\nThank you for banking with us.', '{\"account_number\":\"The bank account number of the user\",\"amount\":\"The amount that has been deposited\",\"branch\":\"The name of the branch where the deposit was made\",\"staff\":\"The staff member who processed the deposit\",\"trx\":\"The unique transaction number for this deposit\",\"post_balance\":\"The user\'s account balance after the deposit\"}', 1, 1, '2024-12-18 12:00:00', '2024-12-18 05:59:04'),
(52, 'WITHDRAW_FROM_BRANCH', 'Withdraw From Branch', 'Withdraw Confirmation for Your Account', '<p>We are pleased to inform you that a withdraw has been successfully made from your bank account. Below are the details of the transaction for your reference:</p><p>&nbsp;</p><ul><li><strong>Account Number: </strong>{{account_number}}</li><li><strong>Withdraw Amount: </strong>{{amount}} {{site_currency}}</li><li><strong>Branch Name: </strong>{{branch}}</li><li><strong>Account Officer: </strong>{{staff}}</li><li><strong>Transaction Number: </strong>{{trx}}</li><li><strong>Post Balance: </strong>{{post_balance}} {{site_currency}}</li></ul><p>&nbsp;</p><p>If you have any questions regarding this transaction or need further assistance, feel free to contact our support team.</p><p>&nbsp;</p><p>Thank you for banking with us.</p><p>&nbsp;</p><p>Best regards,</p>', 'A withdraw of {{amount}} {{site_currency}} has been successfully made from your account {{ account_number }} from the {{ branch }} by Account Officer {{ staff }}.\r\n\r\nTransaction Number: {{ trx }}\r\nPost Balance: {{post_balance}} {{site_currency}}\r\n\r\nThank you for banking with us.', '{\"account_number\":\"The bank account number of the user\",\"amount\":\"The amount that has been withdrawn\",\"branch\":\"The name of the branch where the withdraw was made\",\"staff\":\"The staff member who processed the withdraw\",\"trx\":\"The unique transaction number for this withdraw\",\"post_balance\":\"The user\'s account balance after the withdraw\"}', 1, 1, '2024-12-18 12:00:00', '2024-12-18 08:46:22'),
(53, 'OTP_FOR_TRANSACTION', 'OTP for Transaction', 'Your One-Time Password (OTP) for Transaction', '<p>We have received a request to initiate a transaction. To ensure the security of your account, we have generated a One-Time Password (OTP) for you to complete the transaction.</p><p>&nbsp;</p><p><strong>Your OTP is: {{otp}}</strong></p><p>&nbsp;</p><p>Please enter this OTP on the transaction page to proceed. For your security, this OTP is valid for the next <strong>{{expiry_time}}</strong> only. If you did not initiate this request, please contact our support team immediately.</p><p>&nbsp;</p><p>Thank you for banking with us.</p><p>&nbsp;</p><p>Best regards,</p>', 'Your OTP for completing the transaction is {{otp}}.\r\nIt is valid for {{expiry_time}}. Please use it promptly. If you didn’t request this, contact support immediately.', '{\"otp\":\"The generated OTP to complete the bank transaction.\",\"expiry_time\":\"The expiration time of the generated OTP\"}', 1, 1, '2025-01-06 12:00:00', '2025-02-06 09:12:02'),
(54, 'INTERNAL_TRANSFER_SEND_MONEY', 'Internal Bank Transfer - Send Money', 'Confirmation of Your Internal Bank Transfer', '<p>We are pleased to inform you that your internal bank transfer has been successfully processed. Below are the details of your transaction:</p><p>&nbsp;</p><ul><li><strong>Transaction ID (TRX):</strong> {{trx}}</li><li><strong>Amount Transferred:</strong> {{amount}} {{site_currency}}</li><li><strong>Service Charge:</strong> {{charge}} {{site_currency}}</li><li><strong>Remaining Balance:</strong> {{post_balance}} {{site_currency}}</li><li><strong>Recipient:</strong> {{receiver}}</li></ul><p>&nbsp;</p><p>This transfer has been completed securely, and the recipient has been credited accordingly. If you have any questions regarding this transaction, feel free to reach out to our customer support team.</p><p>&nbsp;</p><p><strong>Note:</strong> Please ensure that you retain the transaction reference number ({trx}) for future inquiries or record-keeping purposes.</p><p>&nbsp;</p><p>Thank you for choosing <strong>{{site_name}}</strong> for your banking needs.</p><p>&nbsp;</p><p>Best regards,</p>', '{{site_name}}:\r\n\r\nYour transfer of {{amount}} {{site_currency}} has been successfully completed.\r\nRecipient: {{receiver}}\r\nTransaction ID (TRX): {{trx}}\r\nService Charge: {{charge}} {{site_currency}}\r\nRemaining Balance: {{post_balance}} {{site_currency}}\r\n\r\nThank you for banking with us!', '{\"receiver\":\"The account or user receiving the funds in the transaction\",\"amount\":\"The total monetary value involved in the transaction\",\"charge\":\"The fee applied to the transaction for processing services\",\"post_balance\":\"The remaining balance in the sender\'s account after the transaction is completed\",\"trx\":\"The unique transaction ID used to identify and track the transaction\"}', 1, 1, '2025-01-09 12:00:00', '2025-01-09 07:06:29'),
(55, 'INTERNAL_TRANSFER_RECEIVE_MONEY', 'Internal Bank Transfer - Receive Money', 'Confirmation of Money Received', '<p>We are delighted to inform you that a transfer has been successfully credited to your account. Below are the details of the transaction:</p><p>&nbsp;</p><ul><li><strong>Transaction ID (TRX):</strong> {{trx}}</li><li><strong>Amount Received:</strong> {{amount}} {{site_currency}}</li><li><strong>New Balance:</strong> {{post_balance}} {{site_currency}}</li><li><strong>Sender:</strong> {{sender}}</li></ul><p>&nbsp;</p><p>The funds are now available in your account for immediate use. Please retain the transaction ID ({{trx}}) for your records or any future inquiries regarding this transfer.</p><p>&nbsp;</p><p>If you have any questions or require further assistance, please don’t hesitate to contact our customer support team.</p><p>&nbsp;</p><p>Thank you for choosing <strong>{{site_name}}</strong>.</p><p>&nbsp;</p><p>Best regards,</p>', '{{site_name}}:\r\n\r\nYou have received {{amount}} {{site_currency}} from {{sender}}.\r\n\r\nTransaction ID (TRX): {{trx}}\r\nNew Balance: {{post_balance}} {{site_currency}}\r\n\r\nThank you for banking with us!', '{\"sender\":\"The account or user initiating the transaction\",\"amount\":\"The total monetary value involved in the transaction\",\"post_balance\":\"The new balance in the receiver\'s account after the transaction is completed\",\"trx\":\"The unique transaction ID used to identify and track the transaction\"}', 1, 1, '2025-01-09 12:00:00', '2025-01-09 09:27:38'),
(56, 'EXTERNAL_TRANSFER_REQUEST', 'External Bank Transfer - Request', 'External Bank Transfer Request Received', '<p>We have received your request to transfer funds to an external bank account. Please find the details of your transaction below:</p><p>&nbsp;</p><p><strong>Transaction Details:</strong></p><ul><li><strong>Sender Account Name: </strong>{{sender_account_name}}</li><li><strong>Sender Account Number:</strong> {{sender_account_number}}</li><li><strong>Recipient Account Name: </strong>{{recipient_account_name}}</li><li><strong>Recipient Account Number: </strong>{{recipient_account_number}}</li><li><strong>Bank Name:</strong> {{external_bank}}</li><li><strong>Transaction Amount: </strong>{{amount}} {{site_currency}}</li><li><strong>Transaction Fee:</strong> {{charge}} {{site_currency}}</li><li><strong>Transaction ID:</strong> {{trx}}</li></ul><p>&nbsp;</p><p>Your transaction is currently <strong>pending approval</strong> from our administration team. Once it has been reviewed and approved, the transfer will be processed promptly. You will receive a confirmation email once the transaction status is updated.</p><p>&nbsp;</p><p>If you have any questions or require further assistance, please do not hesitate to contact us.</p><p>&nbsp;</p><p>Thank you for choosing <strong>{{site_name}}</strong>.</p><p>&nbsp;</p><p>Warm regards,</p><p>{{site_name}} Team</p>', 'Your external bank transfer request of {{amount}} {{site_currency}} (ID: {{trx}}) to {{recipient_account_name}} ({{external_bank}}) has been received and is pending admin approval. You will be notified once it is processed. - {{site_name}}', '{\"sender_account_name\":\"The name of the sender\'s account\",\"sender_account_number\":\"The account number of the sender\",\"recipient_account_name\":\"The name of the recipient\'s account\",\"recipient_account_number\":\"The account number of the recipient\",\"external_bank\":\"The name of the external bank involved in the transfer\",\"amount\":\"The monetary value requested in the transaction\",\"charge\":\"The transaction fee charged for the transfer\",\"trx\":\"The unique transaction ID used to identify and track the transaction\"}', 1, 1, '2025-01-26 12:00:00', '2025-01-26 10:25:31'),
(57, 'EXTERNAL_TRANSFER_COMPLETE', 'External Bank Transfer - Complete', 'Your External Bank Transfer Has Been Successfully Processed', '<p>We are pleased to inform you that your request to transfer funds to an external bank account has been successfully processed. Below are the details of your transaction:</p><p>&nbsp;</p><p><strong>Transaction Details:</strong></p><ul><li><strong>Recipient Account Name: </strong>{{recipient_account_name}}</li><li><strong>Recipient Account Number: </strong>{{recipient_account_number}}</li><li><strong>Bank Name:</strong> {{external_bank}}</li><li><strong>Transaction Amount: </strong>{{amount}} {{site_currency}}</li><li><strong>Transaction Fee:</strong> {{charge}} {{site_currency}}</li><li><strong>Transaction ID:</strong> {{trx}}</li></ul><p>&nbsp;</p><p>The funds have been successfully transferred to the recipient\'s bank account. Please note that it may take some time for the recipient\'s bank to reflect the deposit, depending on their processing policies.</p><p>&nbsp;</p><p>If you have any questions or require further assistance, please do not hesitate to contact our support team.</p><p>&nbsp;</p><p>Thank you for choosing <strong>{{site_name}}</strong>.</p><p>&nbsp;</p><p>Warm regards,</p><p>{{site_name}} Team</p>', 'Your external bank transfer of {{amount}} {{site_currency}} (Txn ID: {{trx}}) to {{recipient_account_name}} at {{external_bank}} has been successfully processed. Thank you for using {{site_name}}.', '{\"recipient_account_name\":\"The name of the recipient\'s account\",\"recipient_account_number\":\"The account number of the recipient\",\"external_bank\":\"The name of the external bank involved in the transfer\",\"amount\":\"The monetary value requested in the transaction\",\"charge\":\"The transaction fee charged for the transfer\",\"trx\":\"The unique transaction ID used to identify and track the transaction\"}', 1, 1, '2025-01-30 12:00:00', '2025-01-30 05:15:25'),
(58, 'EXTERNAL_TRANSFER_FAIL', 'External Bank Transfer - Fail', 'Update on Your Fund Transfer Request', '<p>We regret to inform you that your recent request to transfer funds to an external bank account was not successful. After reviewing the transaction, we found that the transfer could not be completed due to unforeseen issues with the external bank.</p><p>&nbsp;</p><p><strong>Transaction Details:</strong></p><ul><li><strong>Recipient Account Name: </strong>{{recipient_account_name}}</li><li><strong>Recipient Account Number: </strong>{{recipient_account_number}}</li><li><strong>Bank Name:</strong> {{external_bank}}</li><li><strong>Transaction Amount: </strong>{{amount}} {{site_currency}}</li><li><strong>Transaction Fee:</strong> {{charge}} {{site_currency}}</li><li><strong>Transaction ID:</strong> {{trx}}</li></ul><p>&nbsp;</p><p><strong>Reason:</strong></p><p>{{reason}}</p><p>&nbsp;</p><p>As a result, we have refunded the full amount of <strong>{{amount}} {{site_currency}}</strong>, including the transaction fee, to your account. The refund should be visible in your account shortly.</p><p>&nbsp;</p><p>If you have any questions or need assistance with this matter, feel free to contact our support team. We are here to help!</p><p>&nbsp;</p><p>Thank you for your understanding and for choosing <strong>{{site_name}}</strong>.</p><p>&nbsp;</p><p>Warm regards,</p><p>{{site_name}} Team</p>', 'Your transfer request to {{recipient_account_name}} at {{external_bank}} has failed. A refund of {{amount}} {{site_currency}} (including fees) has been processed and should reflect in your account shortly. For assistance, contact our support team. – {{site_name}}', '{\"recipient_account_name\":\"The name of the recipient\'s account\",\"recipient_account_number\":\"The account number of the recipient\",\"external_bank\":\"The name of the external bank involved in the transfer\",\"amount\":\"The monetary value requested in the transaction\",\"charge\":\"The transaction fee charged for the transfer\",\"trx\":\"The unique transaction ID used to identify and track the transaction\",\"reason\":\"The rejection reason for failing the transfer\"}', 1, 1, '2025-01-30 12:00:00', '2025-01-30 09:30:23'),
(59, 'WIRE_TRANSFER_REQUEST', 'Wire Transfer - Request', 'Wire Transfer Request Received – Pending Approval', '<p>We have received your request for a <strong>wire transfer</strong> to a bank account. Below are the details of your transaction:</p><p>&nbsp;</p><p><strong>Transaction Details:</strong></p><ul><li><strong>Sender Account Name: </strong>{{sender_account_name}}</li><li><strong>Sender Account Number:</strong> {{sender_account_number}}</li><li><strong>Recipient Account Name: </strong>{{recipient_account_name}}</li><li><strong>Recipient Account Number: </strong>{{recipient_account_number}}</li><li><strong>Transaction Amount: </strong>{{amount}} {{site_currency}}</li><li><strong>Transaction Fee:</strong> {{charge}} {{site_currency}}</li><li><strong>Transaction ID:</strong> {{trx}}</li></ul><p>&nbsp;</p><p>Your wire transfer request is currently <strong>pending approval</strong> from our administration team. Once reviewed and approved, it will be processed promptly. You will receive a confirmation email once the transaction status is updated.</p><p>&nbsp;</p><p>If you have any questions or need further assistance, please feel free to contact our support team.</p><p>&nbsp;</p><p>Thank you for choosing <strong>{{site_name}}</strong>.</p><p>&nbsp;</p><p>Warm regards,</p><p>{{site_name}} Team</p>', 'Your wire transfer request of {{amount}} {{site_currency}} (ID: {{trx}}) to {{recipient_account_name}} is pending admin approval. You will be notified once processed. - {{site_name}}', '{\"sender_account_name\":\"The name of the sender\'s account\",\"sender_account_number\":\"The account number of the sender\",\"recipient_account_name\":\"The name of the recipient\'s account\",\"recipient_account_number\":\"The account number of the recipient\",\"amount\":\"The monetary value requested in the transaction\",\"charge\":\"The transaction fee charged for the transfer\",\"trx\":\"The unique transaction ID used to identify and track the transaction\"}', 1, 1, '2025-02-06 12:00:00', '2025-02-06 09:22:00'),
(60, 'WIRE_TRANSFER_COMPLETE', 'Wire Transfer - Complete', 'Your Wire Transfer Request Has Been Successfully Processed', '<p>We are pleased to inform you that your <strong>wire transfer request</strong> has been successfully processed. Below are the details of your transaction:</p><p>&nbsp;</p><p><strong>Transaction Details:</strong></p><ul><li><strong>Recipient Account Name: </strong>{{recipient_account_name}}</li><li><strong>Recipient Account Number: </strong>{{recipient_account_number}}</li><li><strong>Transaction Amount: </strong>{{amount}} {{site_currency}}</li><li><strong>Transaction Fee:</strong> {{charge}} {{site_currency}}</li><li><strong>Transaction ID:</strong> {{trx}}</li></ul><p>&nbsp;</p><p>The funds have been successfully transferred via wire transfer. Please note that wire transfers may take some time to be processed by the recipient’s bank, depending on their policies and processing time.</p><p>&nbsp;</p><p>If you have any questions or require further assistance, please do not hesitate to contact our support team.</p><p>&nbsp;</p><p>Thank you for choosing <strong>{{site_name}}</strong>.</p><p>&nbsp;</p><p>Warm regards,</p><p>{{site_name}} Team</p>', 'Your wire transfer to {{recipient_account_name}} (Account No: {{recipient_account_number}}) of {{amount}} {{site_currency}} has been successfully processed. Transaction Fee: {{charge}} {{site_currency}}. Transaction ID: {{trx}}. Please allow time for the recipient’s bank to reflect the deposit.\r\n\r\nFor queries, contact support.\r\n{{site_name}}', '{\"recipient_account_name\":\"The name of the recipient\'s account\",\"recipient_account_number\":\"The account number of the recipient\",\"amount\":\"The monetary value requested in the transaction\",\"charge\":\"The transaction fee charged for the transfer\",\"trx\":\"The unique transaction ID used to identify and track the transaction\"}', 1, 1, '2025-02-08 12:00:00', '2025-02-08 07:04:58'),
(61, 'WIRE_TRANSFER_FAIL', 'Wire Transfer - Fail', 'Wire Transfer Request Unsuccessful', '<p>We regret to inform you that your recent <strong>wire transfer request</strong> could not be completed successfully. After reviewing the transaction, we found that the transfer could not be processed due to unforeseen issues with the wire transfer provider.</p><p>&nbsp;</p><p><strong>Transaction Details:</strong></p><ul><li><strong>Recipient Account Name: </strong>{{recipient_account_name}}</li><li><strong>Recipient Account Number: </strong>{{recipient_account_number}}</li><li><strong>Transaction Amount: </strong>{{amount}} {{site_currency}}</li><li><strong>Transaction Fee:</strong> {{charge}} {{site_currency}}</li><li><strong>Transaction ID:</strong> {{trx}}</li></ul><p>&nbsp;</p><p><strong>Reason:</strong></p><p>{{reason}}</p><p>&nbsp;</p><p>As a result, we have refunded the full amount of <strong>{{amount}} {{site_currency}}</strong>, including the transaction fee, to your account. The refund should be visible in your account shortly.</p><p>&nbsp;</p><p>If you have any questions or need assistance with this matter, feel free to contact our support team. We are here to help!</p><p>&nbsp;</p><p>Thank you for your understanding and for choosing <strong>{{site_name}}</strong>.</p><p>&nbsp;</p><p>Warm regards,</p><p>{{site_name}} Team</p>', 'Your wire transfer to {{recipient_account_name}} (Account No: {{recipient_account_number}}) for {{amount}} {{site_currency}} could not be processed due to unforeseen issues. The full amount, including the fee, has been refunded to your account. For more details, contact support. – {{site_name}}', '{\"recipient_account_name\":\"The name of the recipient\'s account\",\"recipient_account_number\":\"The account number of the recipient\",\"amount\":\"The monetary value requested in the transaction\",\"charge\":\"The transaction fee charged for the transfer\",\"trx\":\"The unique transaction ID used to identify and track the transaction\",\"reason\":\"The rejection reason for failing the transfer\"}', 1, 1, '2025-02-08 12:00:00', '2025-02-08 09:41:19'),
(62, 'DPS_OPEN', 'New DPS Has Opened', 'Confirmation of Your New DPS Plan', '<p>We are pleased to inform you that you have successfully opened a new Deposit Pension Scheme (DPS).</p><p>&nbsp;</p><ul><li><strong>Plan Name: </strong>{{plan_name}}</li><li><strong>Scheme Code: </strong>{{scheme_code}}</li><li><strong>Per Installment Amount: </strong>{{per_installment}} {{site_currency}}</li><li><strong>Total Installments:</strong> {{total_installment}}</li><li><strong>Next Installment Date:</strong> {{next_installment_date}}</li></ul><p>&nbsp;</p><p>As per the plan, we have deducted your first installment amount of <strong>{{per_installment}} {{site_currency}}</strong> from your account. Kindly ensure timely payments for the remaining installments to avoid any penalties or late fees.</p><p>&nbsp;</p><p>If you have any questions or require further assistance, feel free to reach out to us.</p><p>&nbsp;</p><p>Thank you for choosing <strong>{{site_name}}</strong>.</p><p>&nbsp;</p><p>Best regards,</p><p>{{site_name}} Team</p>', 'You have successfully opened a new DPS: {{plan_name}}. Your first installment of {{per_installment}} {{site_currency}} has been deducted. The next installment is due on {{next_installment_date}}. Your plan includes a total of {{total_installment}} installments. Please ensure timely payments to avoid penalties. Thank you for choosing {{site_name}}.', '{\"plan_name\":\"The name of the DPS (Deposit Pension Scheme) plan\",\"scheme_code\":\"The unique scheme ID used to identify and track the DPS\",\"per_installment\":\"The amount to be paid in each installment\",\"next_installment_date\":\"The scheduled date for the next installment payment\",\"total_installment\":\"The total number of installments in the DPS plan\"}', 1, 1, '2025-02-11 12:00:00', '2025-02-11 05:41:36'),
(63, 'DPS_INSTALLMENT_DUE', 'DPS Installment is Overdue', 'Reminder: DPS Installment Due', '<p>This is a reminder that your <strong>Deposit Pension Scheme (DPS)</strong> installment is now due.</p><p>&nbsp;</p><p><strong>DPS No: </strong>{{scheme_code}}</p><p><strong>Installment Amount: </strong>{{installment_amount}} {{site_currency}}</p><p><strong>Installment Due Date: </strong>{{installment_date}}</p><p>&nbsp;</p><p>Please make sure to clear the payment promptly to avoid any penalties or late fees. If you have any questions or need assistance, feel free to contact us.</p><p>&nbsp;</p><p>Thank you for being a valued member of <strong>{{site_name}}</strong>.</p><p>&nbsp;</p><p>Best regards,</p><p>{{site_name}} Team</p>', 'Your DPS installment of {{installment_amount}} {{site_currency}} is due on {{installment_date}}. Please ensure timely payment to avoid penalties. - {{site_name}}', '{\"scheme_code\":\"The unique scheme ID used to identify and track the DPS\",\"installment_amount\":\"The amount for the installment\",\"installment_date\":\"The due date of the installment\"}', 1, 1, '2025-02-12 12:00:00', '2025-02-12 09:52:04'),
(64, 'DPS_MATURE', 'DPS Has Matured', 'Congratulations! Your DPS Has Matured', '<p>We are pleased to inform you that your <strong>Deposit Pension Scheme (DPS)</strong> has successfully matured.</p><p>&nbsp;</p><p>Here are the details of your matured DPS:</p><p>&nbsp;</p><p><strong>Plan Name: </strong>{{plan_name}}</p><p><strong>DPS No: </strong>{{scheme_code}}</p><p><strong>Total Installments Paid:</strong> {{total_installments}}</p><p><strong>Total Deposited Amount:</strong> {{total_deposit}} {{site_currency}}</p><p><strong>Interest Earned:</strong> {{interest_earned}} {{site_currency}}</p><p><strong>Total Maturity Amount:</strong> {{maturity_amount}} {{site_currency}}</p><p><strong>Maturity Date:</strong> {{maturity_date}}</p><p>&nbsp;</p><p>The total maturity amount of <strong>{{maturity_amount}} {{site_currency}}</strong> has been credited to your account.</p><p>&nbsp;</p><p>Thank you for your trust in <strong>{{site_name}}</strong>. If you have any questions or require further assistance, please feel free to contact us.</p><p>&nbsp;</p><p>Best regards,</p><p>{{site_name}} Team</p>', 'Congratulations! Your DPS {{plan_name}} (Code: {{scheme_code}}) has matured. You have received {{maturity_amount}} {{site_currency}} in your account. - {{site_name}}', '{\"plan_name\":\"Name of the DPS (Deposit Pension Scheme) plan\",\"scheme_code\":\"Unique scheme ID used to identify and track the DPS\",\"total_installments\":\"Total number of paid installments\",\"total_deposit\":\"Total deposited amount\",\"interest_earned\":\"Interest earned on the DPS\",\"maturity_amount\":\"Total amount at maturity (deposit + interest)\",\"maturity_date\":\"Date of maturity\"}', 1, 1, '2025-02-12 12:00:00', '2025-02-12 12:35:01');
INSERT INTO `notification_templates` (`id`, `act`, `name`, `subj`, `email_body`, `sms_body`, `shortcodes`, `email_status`, `sms_status`, `created_at`, `updated_at`) VALUES
(65, 'DPS_CLOSE', 'DPS Has Closed', 'DPS Maturity Amount Transfer Notification', '<p>We are pleased to inform you that the maturity amount of your Deposit Pension Scheme <strong>(Code: {{scheme_code}})</strong> has been successfully transferred to your account.</p><p>&nbsp;</p><p><strong>Total Maturity Amount:</strong> {{maturity_amount}} {{site_currency}}</p><p>&nbsp;</p><p>The amount has been credited to your balance. You can now view your updated balance in your account.</p><p>&nbsp;</p><p>If you have any questions or need further assistance, feel free to contact us.</p><p>&nbsp;</p><p>Best regards,</p><p>{{site_name}} Team</p>', 'Your DPS ({{scheme_code}}) has matured, and the maturity amount of {{maturity_amount}} {{site_currency}} has been successfully transferred to your account. Thank you for banking with {{site_name}}.', '{\"scheme_code\":\"Unique scheme ID used to identify and track the DPS\",\"maturity_amount\":\"The maturity amount that has been transferred\"}', 1, 1, '2025-02-17 12:00:00', '2025-02-17 09:50:50'),
(66, 'FDS_OPEN', 'New FDS Has Opened', 'Confirmation of Your Fixed Deposit Scheme (FDS) Details', '<p>We are pleased to confirm that your Fixed Deposit Scheme (FDS) has been successfully processed. Below are the details of your FDS:</p><p>&nbsp;</p><ul><li><strong>Plan Name:</strong> {{plan_name}}</li><li><strong>Scheme Code:</strong> {{scheme_code}}</li><li><strong>Interest Rate:</strong> {{interest_rate}}%</li><li><strong>Deposit Amount:</strong> {{deposit_amount}} {{site_currency}}</li><li><strong>Per Installment Profit:</strong> {{per_installment}} {{site_currency}}</li></ul><p>&nbsp;</p><p>Your deposit will generate returns based on the agreed-upon interest rate, and you will receive your next installment on <strong>{{next_installment_date}}</strong>. Please note that your funds will remain locked until <strong>{{locked_until}}</strong> as per the scheme terms.</p><p>&nbsp;</p><p>If you have any questions or require further assistance, please feel free to reach out to our support team.</p><p>&nbsp;</p><p>Thank you for choosing us for your financial needs. We appreciate your trust and look forward to serving you.</p><p>&nbsp;</p><p>Best regards,</p><p>{{site_name}} Team</p>', 'You have successfully opened a new Fixed Deposit (FDS): {{plan_name}}. An amount of {{deposit_amount}} {{site_currency}} has been deposited. Your deposit will earn interest at {{interest_rate}}% rate, and you will receive your next installment on {{next_installment_date}}. Please note that your funds will remain locked until {{locked_until}} as per the scheme terms. Thank you for choosing {{site_name}}!', '{\"plan_name\":\"The name of the Fixed Deposit Scheme (FDS) plan\",\"scheme_code\":\"The unique scheme ID used to identify and track the Fixed Deposit Scheme\",\"per_installment\":\"The profit amount received in each installment\",\"next_installment_date\":\"The scheduled date for the next installment payment\",\"deposit_amount\":\"The total amount deposited into the Fixed Deposit Scheme\",\"interest_rate\":\"The interest rate applied to the deposited amount\",\"locked_until\":\"The date until which the fixed deposit remains locked and cannot be withdrawn\"}', 1, 1, '2025-02-20 12:00:00', '2025-02-20 10:49:00'),
(67, 'FDS_CLOSE', 'FDS Has Closed', 'Fixed Deposit Scheme Closure & Profit Transfer Confirmation', '<p>We hope you are doing well.</p><p>&nbsp;</p><p>We would like to inform you that your Fixed Deposit Scheme has successfully matured and has been closed. The details of your deposit are as follows:</p><p>&nbsp;</p><ul><li><strong>Plan Name: </strong>{{plan_name}}</li><li><strong>Scheme Code: </strong>{{scheme_code}}</li><li><strong>Deposit Amount: </strong>{{deposit_amount}} {{site_currency}}</li><li><strong>Profit Amount: </strong>{{profit_amount}} {{site_currency}}</li></ul><p>&nbsp;</p><p>The total profit amount has been successfully transferred to your account. Please check your account statement for confirmation.</p><p>&nbsp;</p><p>If you have any questions or require further assistance, please feel free to contact us. We appreciate your trust in our services and look forward to serving you again in the future.</p><p>&nbsp;</p><p>Best regards,</p><p>{{site_name}} Team</p>', 'Your Fixed Deposit Scheme {{scheme_code}} has matured and is now closed. The profit amount of {{profit_amount}} {{site_currency}} has been successfully transferred to your account. Thank you for banking with us!', '{\"plan_name\":\"The name of the Fixed Deposit Scheme (FDS) plan\",\"scheme_code\":\"The unique scheme ID used to identify and track the Fixed Deposit Scheme\",\"deposit_amount\":\"The total amount deposited into the Fixed Deposit Scheme\",\"profit_amount\":\"The total profit earned from the Fixed Deposit Scheme\"}', 1, 1, '2025-02-24 12:00:00', '2025-02-24 05:23:14'),
(68, 'LOAN_APPROVE', 'Loan Application Has Approved', 'Congratulations! Your Loan Has Been Approved', '<p>We are pleased to inform you that your loan application has been successfully approved by our bank’s admin team. Below are the details of your approved loan:</p><p>&nbsp;</p><ul><li><strong>Plan Name</strong>: {{plan_name}}</li><li><strong>Scheme Code</strong>: {{scheme_code}}</li><li><strong>Approved Loan Amount</strong>: {{amount}} {{site_currency}}</li><li><strong>Per Installment</strong>: {{per_installment}} {{site_currency}}</li><li><strong>Installment Interval: </strong>{{installment_interval}}</li><li><strong>Next Installment Date</strong>: {{next_installment_date}}</li><li><strong>Total Installments</strong>: {{total_installment}}</li><li><strong>Delay Duration</strong>: {{delay_duration}}</li></ul><p>&nbsp;</p><p>Please take note that in case of a delayed installment, a late fee of <strong>{{per_installment_late_fee}} {{site_currency}}</strong> will be applied for each installment that is overdue. We encourage you to ensure timely payments to avoid any additional charges.</p><p>&nbsp;</p><p>If you have any questions or need assistance, please don’t hesitate to reach out to us. We are happy to assist you!</p><p>&nbsp;</p><p>Thank you for choosing {{site_name}}. We look forward to supporting you throughout your loan journey.</p><p>&nbsp;</p><p>Best regards,</p><p>{{site_name}} Team</p>', 'Congratulations! Your loan of {{amount}} {{site_currency}} has been approved. Your monthly installment is {{per_installment}} {{site_currency}}, and the next payment is due on {{next_installment_date}}. Please note that a late fee of {{per_installment_late_fee}} {{site_currency}} will be applied for each overdue installment. We encourage you to make timely payments to avoid any additional charges. If you have any questions, feel free to reach out to us. Thank you for choosing {{site_name}}.', '{\"plan_name\":\"The name of the loan plan\",\"scheme_code\":\"A unique scheme ID used to identify and track the loan\",\"amount\":\"The total loan amount requested\",\"per_installment\":\"The amount to be paid in each installment\",\"installment_interval\":\"The time interval between each installment\",\"next_installment_date\":\"The date when the next installment is due\",\"total_installment\":\"The total number of installments for the loan\",\"delay_duration\":\"The duration allowed for a delay in payment before penalties apply\",\"per_installment_late_fee\":\"The fee charged for each late installment\"}', 1, 1, '2025-03-08 12:00:00', '2025-03-08 07:03:00'),
(69, 'LOAN_REJECT', 'Loan Application Has Rejected', 'Loan Application Status – Rejected', '<p>We regret to inform you that your loan application for {{plan_name}} has been reviewed and unfortunately, it has not been approved at this time.</p><p>&nbsp;</p><p>Here are the details of your application:</p><ul><li><strong>Plan Name</strong>: {{plan_name}}</li><li><strong>Scheme Code</strong>: {{scheme_code}}</li><li><strong>Requested Loan Amount</strong>: {{amount}} {{site_currency}}</li></ul><p>&nbsp;</p><p><strong>Rejection Reason:</strong></p><p>{{reason}}</p><p>&nbsp;</p><p>We understand this may be disappointing, but we encourage you to review the reason provided and, if necessary, feel free to contact us for any clarification or guidance on how to improve your future applications.</p><p>&nbsp;</p><p>Thank you for your understanding, and we remain available to assist you with any further questions or concerns.</p><p>&nbsp;</p><p>Best regards,</p><p>{{site_name}} Team</p>', 'We regret to inform you that your loan application for {{plan_name}} has been rejected. The requested loan amount of {{amount}} {{site_currency}} could not be approved. If you have any questions or need further clarification, please don’t hesitate to contact us. We appreciate your understanding and remain available for any assistance you may require.\r\n\r\nThank you,\r\n{{site_name}} Team', '{\"plan_name\":\"The name of the loan plan\",\"scheme_code\":\"A unique scheme ID used to identify and track the loan\",\"amount\":\"The total loan amount requested\",\"reason\":\"The reason for the loan rejection\"}', 1, 1, '2025-03-08 12:00:00', '2025-03-08 08:49:48'),
(70, 'LOAN_INSTALLMENT_DUE', 'Loan Installment is Overdue', 'Reminder: Loan Installment Due', '<p>This is a reminder that your <strong>Loan</strong> installment is now due.</p><p>&nbsp;</p><p><strong>Loan No: </strong>{{scheme_code}}</p><p><strong>Installment Amount: </strong>{{installment_amount}} {{site_currency}}</p><p><strong>Installment Due Date: </strong>{{installment_date}}</p><p>&nbsp;</p><p>Please make sure to clear the payment promptly to avoid any penalties or late fees. If you have any questions or need assistance, feel free to contact us.</p><p>&nbsp;</p><p>Thank you for being a valued member of <strong>{{site_name}}</strong>.</p><p>&nbsp;</p><p>Best regards,</p><p>{{site_name}} Team</p>', 'Your Loan installment of {{installment_amount}} {{site_currency}} is due on {{installment_date}}. Please ensure timely payment to avoid penalties. - {{site_name}}', '{\"scheme_code\":\"The unique scheme ID used to identify and track the Loan\",\"installment_amount\":\"The amount for the installment\",\"installment_date\":\"The due date of the installment\"}', 1, 1, '2025-03-09 12:00:00', '2025-03-09 10:08:27'),
(71, 'LOAN_PAID', 'Loan Has Paid', 'Loan Payment Completion Notification', '<p>We are writing to confirm the successful completion of your loan payment under the {{plan_name}} scheme. Below are the details of your payment.</p><p>&nbsp;</p><p><strong>Loan Plan Details:</strong></p><ul><li><strong>Loan Plan Name: </strong>{{plan_name}}</li><li><strong>Scheme Code: </strong>{{scheme_code}}</li><li><strong>Total Installments: </strong>{{total_installments}}</li></ul><p>&nbsp;</p><p><strong>Payment Summary:</strong></p><ul><li><strong>Refunded Amount: </strong>{{amount}} {{site_currency}}</li><li><strong>Charges Applied:</strong> {{charge}} {{site_currency}} (if applicable)</li><li><strong>Payment Complete Date: </strong>{{date}}</li></ul><p>&nbsp;</p><p>If you have any questions regarding your loan payment or the applied charges, please feel free to reach out to us.</p><p>&nbsp;</p><p>Thank you for choosing {{site_name}}. We look forward to assisting you further in the future.</p><p>&nbsp;</p><p>Best regards,</p><p>{{site_name}} Team</p>', 'Your loan payment under the {{plan_name}} scheme has been successfully completed. The refunded amount is {{amount}} {{site_currency}}, and if applicable, a charge of {{charge}} {{site_currency}} was applied due to a delay in payment. The payment was completed on {{date}}. For any questions, feel free to contact us.\r\n\r\nThank you for choosing {{site_name}}.', '{\"plan_name\":\"Name of the Loan plan\",\"scheme_code\":\"Unique scheme ID used to identify and track the Loan\",\"total_installments\":\"Total number of paid installments\",\"amount\":\"The amount that has been paid by the user\",\"charge\":\"Penalty charge deducted from the user in case of payment delay\",\"date\":\"Date when the loan payment is completed\"}', 1, 1, '2025-03-10 12:00:00', '2025-03-10 05:57:27');

-- --------------------------------------------------------

--
-- Table structure for table `other_banks`
--

CREATE TABLE `other_banks` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `per_transaction_min_amount` decimal(28,8) NOT NULL,
  `per_transaction_max_amount` decimal(28,8) NOT NULL,
  `daily_transaction_max_amount` decimal(28,8) NOT NULL,
  `daily_transaction_limit` int UNSIGNED NOT NULL,
  `monthly_transaction_max_amount` decimal(28,8) NOT NULL,
  `monthly_transaction_limit` int UNSIGNED NOT NULL,
  `fixed_charge` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `percentage_charge` decimal(5,2) NOT NULL DEFAULT '0.00',
  `processing_time` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `instruction` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `form_id` bigint UNSIGNED DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0 -> bank is inactive, 1 -> bank is active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'admin',
  `module` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `route_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `module`, `route_name`, `created_at`, `updated_at`) VALUES
(1, 'access dashboard', 'admin', 'Admin', 'admin.dashboard', '2024-11-18 12:33:59', '2025-04-30 12:56:43'),
(2, 'view all notifications', 'admin', 'Admin', 'admin.system.notification.all', '2024-11-18 12:33:59', '2025-04-30 12:56:43'),
(3, 'mark single notification as read', 'admin', 'Admin', 'admin.system.notification.read', '2024-11-18 12:33:59', '2025-04-30 12:56:43'),
(4, 'mark all notifications as read', 'admin', 'Admin', 'admin.system.notification.read.all', '2024-11-18 12:33:59', '2025-04-30 12:56:43'),
(5, 'remove single notification', 'admin', 'Admin', 'admin.system.notification.remove', '2024-11-18 12:33:59', '2025-04-30 12:56:43'),
(6, 'remove all notifications', 'admin', 'Admin', 'admin.system.notification.remove.all', '2024-11-18 12:33:59', '2025-04-30 12:56:43'),
(7, 'view all transactions', 'admin', 'Admin', 'admin.transaction.index', '2024-11-18 12:33:59', '2025-04-30 12:56:43'),
(8, 'download user file', 'admin', 'Admin', 'admin.file.download', '2024-11-18 12:33:59', '2025-04-30 12:56:43'),
(9, 'view all automated gateways', 'admin', 'Automated Gateway', 'admin.gateway.automated.index', '2024-11-18 12:33:59', '2025-04-30 12:56:43'),
(10, 'edit automated gateway', 'admin', 'Automated Gateway', 'admin.gateway.automated.edit', '2024-11-18 12:33:59', '2025-04-30 12:56:43'),
(11, 'update automated gateway', 'admin', 'Automated Gateway', 'admin.gateway.automated.update', '2024-11-18 12:33:59', '2025-04-30 12:56:43'),
(12, 'remove automated gateway currency', 'admin', 'Automated Gateway', 'admin.gateway.automated.remove', '2024-11-18 12:33:59', '2025-04-30 12:56:43'),
(13, 'change automated gateway status', 'admin', 'Automated Gateway', 'admin.gateway.automated.status', '2024-11-18 12:33:59', '2025-04-30 12:56:43'),
(14, 'view all manual gateways', 'admin', 'Manual Gateway', 'admin.gateway.manual.index', '2024-11-18 12:33:59', '2025-04-30 12:56:43'),
(15, 'create manual gateway', 'admin', 'Manual Gateway', 'admin.gateway.manual.new', '2024-11-18 12:33:59', '2025-04-30 12:56:43'),
(16, 'store or update manual gateway', 'admin', 'Manual Gateway', 'admin.gateway.manual.store', '2024-11-18 12:33:59', '2025-04-30 12:56:43'),
(17, 'edit manual gateway', 'admin', 'Manual Gateway', 'admin.gateway.manual.edit', '2024-11-18 12:33:59', '2025-04-30 12:56:43'),
(18, 'change manual gateway status', 'admin', 'Manual Gateway', 'admin.gateway.manual.status', '2024-11-18 12:33:59', '2025-04-30 12:56:43'),
(19, 'view all roles', 'admin', 'Role', 'admin.roles.index', '2024-11-18 12:33:59', '2025-04-30 12:56:43'),
(20, 'create role', 'admin', 'Role', 'admin.roles.create', '2024-11-18 12:33:59', '2025-04-30 12:56:43'),
(21, 'store role', 'admin', 'Role', 'admin.roles.store', '2024-11-18 12:33:59', '2025-04-30 12:56:43'),
(22, 'edit role', 'admin', 'Role', 'admin.roles.edit', '2024-11-18 12:33:59', '2025-04-30 12:56:43'),
(23, 'update role', 'admin', 'Role', 'admin.roles.update', '2024-11-18 12:33:59', '2025-04-30 12:56:43'),
(24, 'view all admins', 'admin', 'Admin Staff', 'admin.staffs.index', '2024-11-18 12:33:59', '2025-04-30 12:56:43'),
(25, 'create admin', 'admin', 'Admin Staff', 'admin.staffs.store', '2024-11-18 12:33:59', '2025-04-30 12:56:43'),
(26, 'edit admin', 'admin', 'Admin Staff', 'admin.staffs.update', '2024-11-18 12:33:59', '2025-04-30 12:56:43'),
(27, 'change admin status', 'admin', 'Admin Staff', 'admin.staffs.status', '2024-11-18 12:33:59', '2025-04-30 12:56:43'),
(28, 'login as admin', 'admin', 'Admin Staff', 'admin.staffs.login', '2024-11-18 12:33:59', '2025-04-30 12:56:43'),
(29, 'view all users', 'admin', 'User', 'admin.user.index', '2024-11-18 12:33:59', '2025-04-30 12:56:43'),
(30, 'view active users', 'admin', 'User', 'admin.user.active', '2024-11-18 12:33:59', '2025-04-30 12:56:43'),
(31, 'view banned users', 'admin', 'User', 'admin.user.banned', '2024-11-18 12:33:59', '2025-04-30 12:56:43'),
(32, 'view kyc pending users', 'admin', 'User', 'admin.user.kyc.pending', '2024-11-18 12:33:59', '2025-04-30 12:56:43'),
(33, 'view kyc unconfirmed users', 'admin', 'User', 'admin.user.kyc.unconfirmed', '2024-11-18 12:33:59', '2025-04-30 12:56:43'),
(34, 'view email unconfirmed users', 'admin', 'User', 'admin.user.email.unconfirmed', '2024-11-18 12:33:59', '2025-04-30 12:56:43'),
(35, 'view mobile unconfirmed users', 'admin', 'User', 'admin.user.mobile.unconfirmed', '2024-11-18 12:33:59', '2025-04-30 12:56:43'),
(36, 'approve kyc application', 'admin', 'User', 'admin.user.kyc.approve', '2024-11-18 12:33:59', '2025-04-30 12:56:43'),
(37, 'reject kyc application', 'admin', 'User', 'admin.user.kyc.cancel', '2024-11-18 12:33:59', '2025-04-30 12:56:43'),
(38, 'view user details', 'admin', 'User', 'admin.user.details', '2024-11-18 12:33:59', '2025-04-30 12:56:43'),
(39, 'update user information', 'admin', 'User', 'admin.user.update', '2024-11-18 12:33:59', '2025-04-30 12:56:43'),
(40, 'login as user', 'admin', 'User', 'admin.user.login', '2024-11-18 12:34:00', '2025-04-30 12:56:43'),
(41, 'update user balance', 'admin', 'User', 'admin.user.add.sub.balance', '2024-11-18 12:34:00', '2025-04-30 12:56:43'),
(42, 'change user status', 'admin', 'User', 'admin.user.status', '2024-11-18 12:34:00', '2025-04-30 12:56:43'),
(43, 'view all dps plans', 'admin', 'Deposit Pension Scheme Plan', 'admin.dps.plans', '2024-11-18 12:34:00', '2025-04-30 12:56:43'),
(44, 'create dps plan', 'admin', 'Deposit Pension Scheme Plan', 'admin.dps.store', '2024-11-18 12:34:00', '2025-04-30 12:56:43'),
(45, 'edit dps plan', 'admin', 'Deposit Pension Scheme Plan', 'admin.dps.update', '2024-11-18 12:34:00', '2025-04-30 12:56:43'),
(46, 'change dps plan status', 'admin', 'Deposit Pension Scheme Plan', 'admin.dps.status', '2024-11-18 12:34:00', '2025-04-30 12:56:43'),
(47, 'view all fds plans', 'admin', 'Fixed Deposit Scheme Plan', 'admin.fds.plans', '2024-11-18 12:34:00', '2025-04-30 12:56:43'),
(48, 'create fds plan', 'admin', 'Fixed Deposit Scheme Plan', 'admin.fds.store', '2024-11-18 12:34:00', '2025-04-30 12:56:43'),
(49, 'edit fds plan', 'admin', 'Fixed Deposit Scheme Plan', 'admin.fds.update', '2024-11-18 12:34:00', '2025-04-30 12:56:43'),
(50, 'change fds plan status', 'admin', 'Fixed Deposit Scheme Plan', 'admin.fds.status', '2024-11-18 12:34:00', '2025-04-30 12:56:43'),
(51, 'view all loan plans', 'admin', 'Loan Plan', 'admin.loan.plans', '2024-11-18 12:34:00', '2025-04-30 12:56:43'),
(52, 'create loan plan', 'admin', 'Loan Plan', 'admin.loan.plans.create', '2024-11-18 12:34:00', '2025-04-30 12:56:43'),
(53, 'store loan plan', 'admin', 'Loan Plan', 'admin.loan.plans.store', '2024-11-18 12:34:00', '2025-04-30 12:56:43'),
(54, 'view all deposits', 'admin', 'Deposit', 'admin.deposits.index', '2024-11-18 12:34:00', '2025-04-30 12:56:43'),
(55, 'view pending deposits', 'admin', 'Deposit', 'admin.deposits.pending', '2024-11-18 12:34:00', '2025-04-30 12:56:43'),
(56, 'view done deposits', 'admin', 'Deposit', 'admin.deposits.done', '2024-11-18 12:34:00', '2025-04-30 12:56:43'),
(57, 'view cancelled deposits', 'admin', 'Deposit', 'admin.deposits.cancelled', '2024-11-18 12:34:00', '2025-04-30 12:56:43'),
(58, 'approve deposit', 'admin', 'Deposit', 'admin.deposits.approve', '2024-11-18 12:34:00', '2025-04-30 12:56:43'),
(59, 'reject deposit', 'admin', 'Deposit', 'admin.deposits.reject', '2024-11-18 12:34:00', '2025-04-30 12:56:43'),
(60, 'view all withdraw methods', 'admin', 'Withdraw Method', 'admin.withdraw.method.index', '2024-11-18 12:34:00', '2025-04-30 12:56:43'),
(61, 'create withdraw method', 'admin', 'Withdraw Method', 'admin.withdraw.method.new', '2024-11-18 12:34:00', '2025-04-30 12:56:43'),
(62, 'store or update withdraw method', 'admin', 'Withdraw Method', 'admin.withdraw.method.store', '2024-11-18 12:34:00', '2025-04-30 12:56:43'),
(63, 'edit withdraw method', 'admin', 'Withdraw Method', 'admin.withdraw.method.edit', '2024-11-18 12:34:00', '2025-04-30 12:56:43'),
(64, 'change withdraw method status', 'admin', 'Withdraw Method', 'admin.withdraw.method.status', '2024-11-18 12:34:00', '2025-04-30 12:56:43'),
(65, 'view all withdrawals', 'admin', 'Withdraw', 'admin.withdrawals.index', '2024-11-18 12:34:00', '2025-04-30 12:56:43'),
(66, 'view pending withdrawals', 'admin', 'Withdraw', 'admin.withdrawals.pending', '2024-11-18 12:34:00', '2025-04-30 12:56:43'),
(67, 'view done withdrawals', 'admin', 'Withdraw', 'admin.withdrawals.done', '2024-11-18 12:34:00', '2025-04-30 12:56:43'),
(68, 'view cancelled withdrawals', 'admin', 'Withdraw', 'admin.withdrawals.cancelled', '2024-11-18 12:34:00', '2025-04-30 12:56:43'),
(69, 'approve withdraw', 'admin', 'Withdraw', 'admin.withdrawals.approve', '2024-11-18 12:34:00', '2025-04-30 12:56:43'),
(70, 'reject withdraw', 'admin', 'Withdraw', 'admin.withdrawals.reject', '2024-11-18 12:34:00', '2025-04-30 12:56:43'),
(71, 'view all subscribers', 'admin', 'Contact', 'admin.subscriber.index', '2024-11-18 12:34:00', '2025-04-30 12:56:43'),
(72, 'remove subscriber', 'admin', 'Contact', 'admin.subscriber.remove', '2024-11-18 12:34:00', '2025-04-30 12:56:43'),
(73, 'send email to subscribers', 'admin', 'Contact', 'admin.subscriber.send.email', '2024-11-18 12:34:00', '2025-04-30 12:56:43'),
(74, 'view all contacts', 'admin', 'Contact', 'admin.contact.index', '2024-11-18 12:34:00', '2025-04-30 12:56:43'),
(75, 'remove contact', 'admin', 'Contact', 'admin.contact.remove', '2024-11-18 12:34:00', '2025-04-30 12:56:43'),
(76, 'change contact status', 'admin', 'Contact', 'admin.contact.status', '2024-11-18 12:34:00', '2025-04-30 12:56:43'),
(77, 'view basic and system settings', 'admin', 'Setting', 'admin.basic.setting', '2024-11-18 12:34:00', '2025-04-30 12:56:43'),
(78, 'update basic settings', 'admin', 'Setting', 'admin.basic.setting.update', '2024-11-18 12:34:00', '2025-04-30 12:56:43'),
(79, 'update system settings', 'admin', 'Setting', 'admin.basic.setting.system', '2024-11-18 12:34:00', '2025-04-30 12:56:43'),
(80, 'update logo and favicon', 'admin', 'Setting', 'admin.basic.setting.logo.favicon', '2024-11-18 12:34:00', '2025-04-30 12:56:43'),
(81, 'view all plugins', 'admin', 'Setting', 'admin.plugin.setting', '2024-11-18 12:34:00', '2025-04-30 12:56:43'),
(82, 'update plugin', 'admin', 'Setting', 'admin.plugin.update', '2024-11-18 12:34:00', '2025-04-30 12:56:43'),
(83, 'change plugin status', 'admin', 'Setting', 'admin.plugin.status', '2024-11-18 12:34:00', '2025-04-30 12:56:43'),
(84, 'view seo settings', 'admin', 'Setting', 'admin.seo.setting', '2024-11-18 12:34:00', '2025-04-30 12:56:43'),
(85, 'view kyc settings', 'admin', 'Setting', 'admin.kyc.setting', '2024-11-18 12:34:00', '2025-04-30 12:56:43'),
(86, 'update kyc settings', 'admin', 'Setting', 'admin.kyc.setting.update', '2024-11-18 12:34:00', '2025-04-30 12:56:43'),
(87, 'view cookie settings', 'admin', 'Setting', 'admin.cookie.setting', '2024-11-18 12:34:00', '2025-04-30 12:56:43'),
(88, 'update cookie settings', 'admin', 'Setting', 'admin.cookie.setting.update', '2024-11-18 12:34:00', '2025-04-30 12:56:43'),
(89, 'view maintenance settings', 'admin', 'Setting', 'admin.maintenance.setting', '2024-11-18 12:34:00', '2025-04-30 12:56:43'),
(90, 'update maintenance settings', 'admin', 'Setting', 'admin.maintenance.setting.update', '2024-11-18 12:34:00', '2025-04-30 12:56:43'),
(91, 'view referral settings', 'admin', 'Referral Settings', 'admin.referral.settings', '2024-11-18 12:34:00', '2025-04-30 12:56:43'),
(92, 'update referral settings', 'admin', 'Referral Settings', 'admin.referral.settings.store', '2024-11-18 12:34:00', '2025-04-30 12:56:43'),
(93, 'change referral settings status', 'admin', 'Referral Settings', 'admin.referral.settings.status', '2024-11-18 12:34:00', '2025-04-30 12:56:43'),
(94, 'view universal template', 'admin', 'Notification', 'admin.notification.universal', '2024-11-18 12:34:00', '2025-04-30 12:56:43'),
(95, 'update universal template', 'admin', 'Notification', 'admin.notification.universal.update', '2024-11-18 12:34:00', '2025-04-30 12:56:43'),
(96, 'view all templates', 'admin', 'Notification', 'admin.notification.templates', '2024-11-18 12:34:00', '2025-04-30 12:56:43'),
(97, 'edit template', 'admin', 'Notification', 'admin.notification.template.edit', '2024-11-18 12:34:00', '2025-04-30 12:56:43'),
(98, 'update template', 'admin', 'Notification', 'admin.notification.template.update', '2024-11-18 12:34:00', '2025-04-30 12:56:43'),
(99, 'view email configuration', 'admin', 'Notification', 'admin.notification.email', '2024-11-18 12:34:00', '2025-04-30 12:56:43'),
(100, 'update email configuration', 'admin', 'Notification', 'admin.notification.email.update', '2024-11-18 12:34:00', '2025-04-30 12:56:43'),
(101, 'send test email', 'admin', 'Notification', 'admin.notification.email.test', '2024-11-18 12:34:00', '2025-04-30 12:56:43'),
(102, 'view sms configuration', 'admin', 'Notification', 'admin.notification.sms', '2024-11-18 12:34:00', '2025-04-30 12:56:43'),
(103, 'update sms configuration', 'admin', 'Notification', 'admin.notification.sms.update', '2024-11-18 12:34:00', '2025-04-30 12:56:43'),
(104, 'send test sms', 'admin', 'Notification', 'admin.notification.sms.test', '2024-11-18 12:34:00', '2025-04-30 12:56:43'),
(105, 'view all languages', 'admin', 'Language', 'admin.language.index', '2024-11-18 12:34:00', '2025-04-30 12:56:43'),
(106, 'view all keywords', 'admin', 'Language', 'admin.language.keywords', '2024-11-18 12:34:00', '2025-04-30 12:56:43'),
(107, 'create or update language', 'admin', 'Language', 'admin.language.store', '2024-11-18 12:34:00', '2025-04-30 12:56:43'),
(108, 'change language status', 'admin', 'Language', 'admin.language.status', '2024-11-18 12:34:00', '2025-04-30 12:56:43'),
(109, 'delete language', 'admin', 'Language', 'admin.language.delete', '2024-11-18 12:34:00', '2025-04-30 12:56:43'),
(110, 'view translated keywords', 'admin', 'Language', 'admin.language.translate.keyword', '2024-11-18 12:34:00', '2025-04-30 12:56:43'),
(111, 'import keywords', 'admin', 'Language', 'admin.language.import.lang', '2024-11-18 12:34:00', '2025-04-30 12:56:43'),
(112, 'add keyword', 'admin', 'Language', 'admin.language.store.key', '2024-11-18 12:34:00', '2025-04-30 12:56:43'),
(113, 'update keyword', 'admin', 'Language', 'admin.language.update.key', '2024-11-18 12:34:00', '2025-04-30 12:56:43'),
(114, 'delete keyword', 'admin', 'Language', 'admin.language.delete.key', '2024-11-18 12:34:00', '2025-04-30 12:56:43'),
(115, 'view theme settings', 'admin', 'Site', 'admin.site.themes', '2024-11-18 12:34:00', '2025-04-30 12:56:43'),
(116, 'update theme settings', 'admin', 'Site', 'admin.site.', '2024-11-18 12:34:00', '2025-04-30 12:56:43'),
(117, 'view home page sections', 'admin', 'Site', 'admin.site.sections', '2024-11-18 12:34:00', '2025-04-30 12:56:43'),
(118, 'update home page sections', 'admin', 'Site', 'admin.site.sections.content', '2024-11-18 12:34:00', '2025-04-30 12:56:43'),
(119, 'view element content', 'admin', 'Site', 'admin.site.sections.element', '2024-11-18 12:34:00', '2025-04-30 12:56:43'),
(120, 'remove element content', 'admin', 'Site', 'admin.site.remove', '2024-11-18 12:34:00', '2025-04-30 12:56:43'),
(121, 'view all branches', 'admin', 'Branch', 'admin.branches.index', '2024-11-26 11:11:55', '2025-04-30 12:56:43'),
(122, 'create branch', 'admin', 'Branch', 'admin.branches.create', '2024-11-26 11:38:18', '2025-04-30 12:56:43'),
(123, 'store branch', 'admin', 'Branch', 'admin.branches.store', '2024-11-27 06:33:00', '2025-04-30 12:56:43'),
(124, 'edit branch', 'admin', 'Branch', 'admin.branches.edit', '2024-11-27 09:00:42', '2025-04-30 12:56:43'),
(125, 'update branch', 'admin', 'Branch', 'admin.branches.update', '2024-11-27 09:00:42', '2025-04-30 12:56:43'),
(126, 'change branch status', 'admin', 'Branch', 'admin.branches.status', '2024-11-27 09:12:06', '2025-04-30 12:56:43'),
(127, 'view all branch staffs', 'admin', 'Staff', 'admin.branch.staffs.index', '2024-11-28 10:34:45', '2025-04-30 12:56:43'),
(128, 'create branch staff', 'admin', 'Staff', 'admin.branch.staffs.create', '2024-11-28 10:55:18', '2025-04-30 12:56:43'),
(129, 'store branch staff', 'admin', 'Staff', 'admin.branch.staffs.store', '2024-11-30 07:19:16', '2025-04-30 12:56:43'),
(130, 'edit branch staff', 'admin', 'Staff', 'admin.branch.staffs.edit', '2024-12-01 06:17:31', '2025-04-30 12:56:43'),
(131, 'update branch staff', 'admin', 'Staff', 'admin.branch.staffs.update', '2024-12-01 06:20:43', '2025-04-30 12:56:43'),
(132, 'change staff status', 'admin', 'Staff', 'admin.branch.staffs.status', '2024-12-01 06:22:05', '2025-04-30 12:56:43'),
(133, 'login as staff', 'admin', 'Staff', 'admin.branch.staffs.login', '2024-12-01 06:27:46', '2025-04-30 12:56:43'),
(134, 'view other banks', 'admin', 'Other Bank', 'admin.other.banks.index', '2024-12-28 08:43:48', '2025-04-30 12:56:43'),
(135, 'add other bank', 'admin', 'Other Bank', 'admin.other.banks.create', '2024-12-28 08:50:00', '2025-04-30 12:56:43'),
(136, 'store other bank', 'admin', 'Other Bank', 'admin.other.banks.store', '2024-12-28 12:20:32', '2025-04-30 12:56:43'),
(137, 'edit other bank', 'admin', 'Other Bank', 'admin.other.banks.edit', '2024-12-29 09:56:31', '2025-04-30 12:56:43'),
(138, 'update other bank', 'admin', 'Other Bank', 'admin.other.banks.update', '2024-12-29 09:57:43', '2025-04-30 12:56:43'),
(139, 'change other bank status', 'admin', 'Other Bank', 'admin.other.banks.status', '2024-12-30 04:35:30', '2025-04-30 12:56:43'),
(140, 'update bank transaction settings', 'admin', 'Setting', 'admin.basic.setting.bank.transaction', '2025-01-02 10:10:17', '2025-04-30 12:56:43'),
(141, 'view all money transfers', 'admin', 'Money Transfer', 'admin.money.transfers.index', '2025-01-28 05:20:05', '2025-04-30 12:56:43'),
(142, 'view pending money transfers', 'admin', 'Money Transfer', 'admin.money.transfers.pending', '2025-01-28 05:21:11', '2025-04-30 12:56:43'),
(143, 'view completed money transfers', 'admin', 'Money Transfer', 'admin.money.transfers.completed', '2025-01-28 05:21:55', '2025-04-30 12:56:43'),
(144, 'view failed money transfers', 'admin', 'Money Transfer', 'admin.money.transfers.failed', '2025-01-28 05:22:38', '2025-04-30 12:56:43'),
(145, 'view internal money transfers', 'admin', 'Money Transfer', 'admin.money.transfers.internal', '2025-01-28 11:26:41', '2025-04-30 12:56:43'),
(146, 'view external money transfers', 'admin', 'Money Transfer', 'admin.money.transfers.external', '2025-01-28 11:27:41', '2025-04-30 12:56:43'),
(147, 'mark money transfer as complete', 'admin', 'Money Transfer', 'admin.money.transfers.complete', '2025-01-28 11:28:19', '2025-04-30 12:56:43'),
(148, 'mark money transfer as fail', 'admin', 'Money Transfer', 'admin.money.transfers.fail', '2025-01-28 11:29:01', '2025-04-30 12:56:43'),
(149, 'download file', 'admin', 'Money Transfer', 'admin.money.transfers.file', '2025-01-29 10:06:25', '2025-04-30 12:56:43'),
(150, 'view wire transfer settings', 'admin', 'Wire Transfer Settings', 'admin.wire.transfer.settings', '2025-02-01 09:45:34', '2025-04-30 12:56:43'),
(151, 'update wire transfer settings', 'admin', 'Wire Transfer Settings', 'admin.wire.transfer.settings.update', '2025-02-01 09:47:09', '2025-04-30 12:56:43'),
(152, 'view wire transfers', 'admin', 'Money Transfer', 'admin.money.transfers.wire', '2025-02-06 11:11:39', '2025-04-30 12:56:43'),
(153, 'view all dps', 'admin', 'Deposit Pension Scheme', 'admin.dps.index', '2025-02-13 06:03:06', '2025-04-30 12:56:43'),
(154, 'view running dps', 'admin', 'Deposit Pension Scheme', 'admin.dps.running', '2025-02-13 06:04:02', '2025-04-30 12:56:43'),
(155, 'view matured dps', 'admin', 'Deposit Pension Scheme', 'admin.dps.matured', '2025-02-13 06:04:46', '2025-04-30 12:56:43'),
(156, 'view closed dps', 'admin', 'Deposit Pension Scheme', 'admin.dps.closed', '2025-02-13 06:05:42', '2025-04-30 12:56:43'),
(157, 'view late installment dps', 'admin', 'Deposit Pension Scheme', 'admin.dps.late.installment', '2025-02-16 05:19:38', '2025-04-30 12:56:43'),
(158, 'view dps installments', 'admin', 'Deposit Pension Scheme', 'admin.dps.installments', '2025-02-16 06:04:48', '2025-04-30 12:56:43'),
(159, 'view all fds', 'admin', 'Fixed Deposit Scheme', 'admin.fds.index', '2025-02-22 09:15:07', '2025-04-30 12:56:43'),
(160, 'view running fds', 'admin', 'Fixed Deposit Scheme', 'admin.fds.running', '2025-02-22 09:15:52', '2025-04-30 12:56:43'),
(161, 'view closed fds', 'admin', 'Fixed Deposit Scheme', 'admin.fds.closed', '2025-02-22 09:16:28', '2025-04-30 12:56:43'),
(162, 'view fds installments', 'admin', 'Fixed Deposit Scheme', 'admin.fds.installments', '2025-02-22 09:17:01', '2025-04-30 12:56:43'),
(163, 'edit loan plan', 'admin', 'Loan Plan', 'admin.loan.plans.edit', '2025-02-26 08:50:04', '2025-04-30 12:56:43'),
(164, 'update loan plan', 'admin', 'Loan Plan', 'admin.loan.plans.update', '2025-02-26 08:51:14', '2025-04-30 12:56:43'),
(165, 'change loan plan status', 'admin', 'Loan Plan', 'admin.loan.plansstatus', '2025-02-26 08:52:07', '2025-02-26 08:54:02'),
(166, 'view all loans', 'admin', 'Loan', 'admin.loan.index', '2025-03-04 07:48:25', '2025-04-30 12:56:43'),
(167, 'view pending loans', 'admin', 'Loan', 'admin.loan.pending', '2025-03-04 07:49:08', '2025-04-30 12:56:43'),
(168, 'view running loans', 'admin', 'Loan', 'admin.loan.running', '2025-03-04 07:49:41', '2025-04-30 12:56:43'),
(169, 'view late installment loans', 'admin', 'Loan', 'admin.loan.late.installment', '2025-03-04 07:50:14', '2025-04-30 12:56:43'),
(170, 'view paid loans', 'admin', 'Loan', 'admin.loan.paid', '2025-03-04 07:50:43', '2025-04-30 12:56:43'),
(171, 'view rejected loans', 'admin', 'Loan', 'admin.loan.rejected', '2025-03-04 07:51:24', '2025-04-30 12:56:43'),
(172, 'approve loan', 'admin', 'Loan', 'admin.loan.approve', '2025-03-05 05:19:29', '2025-04-30 12:56:43'),
(173, 'reject loan', 'admin', 'Loan', 'admin.loan.reject', '2025-03-05 05:20:12', '2025-04-30 12:56:43'),
(174, 'view loan installments', 'admin', 'Loan', 'admin.loan.installments', '2025-03-05 05:21:00', '2025-04-30 12:56:43'),
(175, 'download applicant file', 'admin', 'Loan', 'admin.loan.file', '2025-03-05 06:42:49', '2025-04-30 12:56:43'),
(176, 'view cronjob settings', 'admin', 'Setting', 'admin.cronjob.index', '2025-04-30 12:54:32', '2025-04-30 12:56:43');

-- --------------------------------------------------------

--
-- Table structure for table `plugins`
--

CREATE TABLE `plugins` (
  `id` bigint UNSIGNED NOT NULL,
  `act` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `script` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `shortcode` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'object',
  `status` tinyint UNSIGNED NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `plugins`
--

INSERT INTO `plugins` (`id`, `act`, `name`, `image`, `script`, `shortcode`, `status`, `created_at`, `updated_at`) VALUES
(1, 'google-analytics', 'Google Analytics', 'analytics.png', '<script async src=\"https://www.googletagmanager.com/gtag/js?id={{app_key}}\"></script>\n                <script>\n                  window.dataLayer = window.dataLayer || [];\n                  function gtag(){dataLayer.push(arguments);}\n                  gtag(\"js\", new Date());\n                \n                  gtag(\"config\", \"{{app_key}}\");\n                </script>', '{\"app_key\":{\"title\":\"App Key\",\"value\":\"-----------------\"}}', 0, NULL, '2025-05-05 14:05:44'),
(2, 'google-recaptcha2', 'Google Recaptcha 2', 'captcha.png', '\n<script src=\"https://www.google.com/recaptcha/api.js\"></script>\n<div class=\"g-recaptcha\" data-sitekey=\"{{site_key}}\" data-callback=\"verifyCaptcha\"></div>\n<div id=\"g-recaptcha-error\"></div>', '{\"site_key\":{\"title\":\"Site Key\",\"value\":\"-----------------\"},\"secret_key\":{\"title\":\"Secret Key\",\"value\":\"-----------------\"}}', 0, NULL, '2025-05-05 14:05:54'),
(3, 'facebook-messenger', 'Facebook Messenger', 'messenger.png', '<div id=\"fb-root\"></div>\n<div id=\"fb-customer-chat\" class=\"fb-customerchat\"></div>\n\n<script>\n    var chatbox = document.getElementById(\'fb-customer-chat\');\n    chatbox.setAttribute(\"page_id\", {{page_id}});\n    chatbox.setAttribute(\"attribution\", \"biz_inbox\");\n</script>\n\n<!-- Your SDK code -->\n<script>\n    window.fbAsyncInit = function() {\n    FB.init({\n        xfbml            : true,\n        version          : \'v17.0\'\n    });\n    };\n\n    (function(d, s, id) {\n    var js, fjs = d.getElementsByTagName(s)[0];\n    if (d.getElementById(id)) return;\n    js = d.createElement(s); js.id = id;\n    js.src = \'https://connect.facebook.net/en_US/sdk/xfbml.customerchat.js\';\n    fjs.parentNode.insertBefore(js, fjs);\n    }(document, \'script\', \'facebook-jssdk\'));\n</script>', '{\"page_id\":{\"title\":\"Page Id\",\"value\":\"-----------------\"}}', 0, NULL, '2025-05-05 14:05:37'),
(4, 'tawk-chat', 'Tawk.to', 'tawk.png', '<script>\n                        var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();\n                        (function(){\n                        var s1=document.createElement(\"script\"),s0=document.getElementsByTagName(\"script\")[0];\n                        s1.async=true;\n                        s1.src=\"https://embed.tawk.to/{{app_key}}\";\n                        s1.charset=\"UTF-8\";\n                        s1.setAttribute(\"crossorigin\",\"*\");\n                        s0.parentNode.insertBefore(s1,s0);\n                        })();\n                    </script>', '{\"app_key\":{\"title\":\"App Key\",\"value\":\"-----------------\"}}', 0, NULL, '2025-05-05 14:06:00');

-- --------------------------------------------------------

--
-- Table structure for table `referral_settings`
--

CREATE TABLE `referral_settings` (
  `id` bigint UNSIGNED NOT NULL,
  `level` int UNSIGNED NOT NULL,
  `percentage` decimal(5,2) NOT NULL COMMENT 'commission will be in percentage'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'admin',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin', 'admin', '2024-11-18 12:39:24', '2024-11-18 12:39:24');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint UNSIGNED NOT NULL,
  `site_name` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `site_cur` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'site currency text',
  `cur_sym` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'site currency symbol',
  `per_page_item` int UNSIGNED NOT NULL DEFAULT '20',
  `fraction_digit` tinyint UNSIGNED NOT NULL DEFAULT '2',
  `date_format` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'MDY',
  `account_number_prefix` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_number_length` int UNSIGNED DEFAULT NULL,
  `referral_tree_level` int UNSIGNED DEFAULT NULL,
  `referral_commission_count` int UNSIGNED DEFAULT NULL COMMENT 'The number of times a referrer will receive a referral commission.',
  `referral_system` tinyint UNSIGNED NOT NULL DEFAULT '1' COMMENT '0 -> referral system is disabled, 1 -> referral system is enabled',
  `otp_expiry` int UNSIGNED NOT NULL DEFAULT '0',
  `idle_timeout` smallint UNSIGNED NOT NULL COMMENT 'value in seconds',
  `statement_download_fee` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `email_from` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_template` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `sms_body` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sms_from` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mail_config` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `sms_config` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `universal_shortcodes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `primary_color` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `secondary_color` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `signup` tinyint UNSIGNED NOT NULL DEFAULT '0' COMMENT 'user registration',
  `enforce_ssl` tinyint UNSIGNED NOT NULL DEFAULT '0' COMMENT 'enforce ssl',
  `agree_policy` tinyint UNSIGNED NOT NULL DEFAULT '0' COMMENT 'accept terms and policy',
  `strong_pass` tinyint UNSIGNED NOT NULL DEFAULT '0' COMMENT 'enforce strong password',
  `kc` tinyint UNSIGNED NOT NULL DEFAULT '0' COMMENT 'kyc confirmation',
  `ec` tinyint UNSIGNED NOT NULL DEFAULT '0' COMMENT 'email confirmation',
  `ea` tinyint UNSIGNED NOT NULL DEFAULT '0' COMMENT 'email alert',
  `sc` tinyint UNSIGNED NOT NULL DEFAULT '0' COMMENT 'sms confirmation',
  `sa` tinyint UNSIGNED NOT NULL DEFAULT '0' COMMENT 'sms alert',
  `site_maintenance` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `language` tinyint UNSIGNED NOT NULL DEFAULT '1',
  `active_theme` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'primary',
  `clean_cron` timestamp NULL DEFAULT NULL,
  `dps_cron` timestamp NULL DEFAULT NULL,
  `fds_cron` timestamp NULL DEFAULT NULL,
  `loan_cron` timestamp NULL DEFAULT NULL,
  `open_account` tinyint(1) NOT NULL DEFAULT '1',
  `per_transaction_min_amount` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `per_transaction_max_amount` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `daily_transaction_max_amount` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `monthly_transaction_max_amount` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `fixed_charge` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `percentage_charge` decimal(5,2) NOT NULL DEFAULT '0.00',
  `deposit` tinyint(1) NOT NULL DEFAULT '1',
  `withdraw` tinyint(1) NOT NULL DEFAULT '1',
  `dps` tinyint(1) NOT NULL DEFAULT '1',
  `fds` tinyint(1) NOT NULL DEFAULT '1',
  `loan` tinyint(1) NOT NULL DEFAULT '1',
  `internal_bank_transfer` tinyint(1) NOT NULL DEFAULT '1',
  `external_bank_transfer` tinyint(1) NOT NULL DEFAULT '1',
  `wire_transfer` tinyint(1) NOT NULL DEFAULT '1',
  `sms_based_otp` tinyint(1) NOT NULL DEFAULT '1',
  `email_based_otp` tinyint(1) NOT NULL DEFAULT '1',
  `auto_logout` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `site_name`, `site_cur`, `cur_sym`, `per_page_item`, `fraction_digit`, `date_format`, `account_number_prefix`, `account_number_length`, `referral_tree_level`, `referral_commission_count`, `referral_system`, `otp_expiry`, `idle_timeout`, `statement_download_fee`, `email_from`, `email_template`, `sms_body`, `sms_from`, `mail_config`, `sms_config`, `universal_shortcodes`, `primary_color`, `secondary_color`, `signup`, `enforce_ssl`, `agree_policy`, `strong_pass`, `kc`, `ec`, `ea`, `sc`, `sa`, `site_maintenance`, `language`, `active_theme`, `clean_cron`, `dps_cron`, `fds_cron`, `loan_cron`, `open_account`, `per_transaction_min_amount`, `per_transaction_max_amount`, `daily_transaction_max_amount`, `monthly_transaction_max_amount`, `fixed_charge`, `percentage_charge`, `deposit`, `withdraw`, `dps`, `fds`, `loan`, `internal_bank_transfer`, `external_bank_transfer`, `wire_transfer`, `sms_based_otp`, `email_based_otp`, `auto_logout`, `created_at`, `updated_at`) VALUES
(1, 'TonaBank', 'USD', '$', 20, 2, 'd-m-Y', 'TONABANK', 12, 5, 10, 0, 120, 900, '3.00000000', 'info@tonatheme.com', '<title></title>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">\r\n<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">\r\n<meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\">\r\n<style type=\"text/css\">\r\n    @media screen {\r\n		@font-face {\r\n		  font-family: \'Lato\';\r\n		  font-style: normal;\r\n		  font-weight: 400;\r\n		  src: local(\'Lato Regular\'), local(\'Lato-Regular\'), url(https://fonts.gstatic.com/s/lato/v11/qIIYRU-oROkIk8vfvxw6QvesZW2xOQ-xsNqO47m55DA.woff) format(\'woff\');\r\n		}\r\n		\r\n		@font-face {\r\n		  font-family: \'Lato\';\r\n		  font-style: normal;\r\n		  font-weight: 700;\r\n		  src: local(\'Lato Bold\'), local(\'Lato-Bold\'), url(https://fonts.gstatic.com/s/lato/v11/qdgUG4U09HnJwhYI-uK18wLUuEpTyoUstqEm5AMlJo4.woff) format(\'woff\');\r\n		}\r\n		\r\n		@font-face {\r\n		  font-family: \'Lato\';\r\n		  font-style: italic;\r\n		  font-weight: 400;\r\n		  src: local(\'Lato Italic\'), local(\'Lato-Italic\'), url(https://fonts.gstatic.com/s/lato/v11/RYyZNoeFgb0l7W3Vu1aSWOvvDin1pK8aKteLpeZ5c0A.woff) format(\'woff\');\r\n		}\r\n		\r\n		@font-face {\r\n		  font-family: \'Lato\';\r\n		  font-style: italic;\r\n		  font-weight: 700;\r\n		  src: local(\'Lato Bold Italic\'), local(\'Lato-BoldItalic\'), url(https://fonts.gstatic.com/s/lato/v11/HkF_qI1x_noxlxhrhMQYELO3LdcAZYWl9Si6vvxL-qU.woff) format(\'woff\');\r\n		}\r\n    }\r\n    \r\n\r\n    body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }\r\n    table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }\r\n    img { -ms-interpolation-mode: bicubic; }\r\n\r\n    img { border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; }\r\n    table { border-collapse: collapse !important; }\r\n    body { height: 100% !important; margin: 0 !important; padding: 0 !important; width: 100% !important; }\r\n\r\n    a[x-apple-data-detectors] {\r\n        color: inherit !important;\r\n        text-decoration: none !important;\r\n        font-size: inherit !important;\r\n        font-family: inherit !important;\r\n        font-weight: inherit !important;\r\n        line-height: inherit !important;\r\n    }\r\n\r\n    div[style*=\"margin: 16px 0;\"] { margin: 0 !important; }\r\n</style>\r\n\r\n\r\n\r\n<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\r\n\r\n    <tbody><tr>\r\n        <td bgcolor=\"black\" align=\"center\">\r\n            <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"700\">\r\n                <tbody><tr>\r\n                    <td align=\"center\" valign=\"top\" style=\"padding: 40px 10px 40px 10px;\">\r\n                        <a href=\"#0\" target=\"_blank\">\r\n                            <img alt=\"Logo\" src=\"https://script.tonatheme.com/tonabank/demo/assets/universal/images/logoFavicon/logo_light.png\" width=\"180\" height=\"180\" style=\"display: block;  font-family: \'Lato\', Helvetica, Arial, sans-serif; color: #ffffff; font-size: 18px;\" border=\"0\">\r\n                        </a>\r\n                    </td>\r\n                </tr>\r\n            </tbody></table>\r\n        </td>\r\n    </tr>\r\n\r\n    <tr>\r\n        <td bgcolor=\"black\" align=\"center\" style=\"padding: 0px 10px 0px 10px;\">\r\n            <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"700\">\r\n                <tbody><tr>\r\n                    <td bgcolor=\"#ffffff\" align=\"center\" valign=\"top\" style=\"padding: 40px 20px 20px 20px; border-radius: 4px 4px 0px 0px; color: #111111; font-family: \'Lato\', Helvetica, Arial, sans-serif; font-size: 48px; font-weight: 400; letter-spacing: 4px; line-height: 48px;\">\r\n                      <h1 style=\"font-size: 22px; font-weight: 400; margin: 0; border-bottom: 1px solid #727272; width: max-content;\">Hello {{fullname}} ({{username}})</h1>\r\n                    </td>\r\n                </tr>\r\n            </tbody></table>\r\n        </td>\r\n    </tr>\r\n\r\n    <tr>\r\n        <td bgcolor=\"#f4f4f4\" align=\"center\" style=\"padding: 0px 10px 0px 10px;\">\r\n            <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"700\">\r\n\r\n              <tbody><tr>\r\n                <td bgcolor=\"#ffffff\" align=\"left\" style=\"padding: 20px 30px 40px 30px; color: #666666; font-family: \'Lato\', Helvetica, Arial, sans-serif; font-size: 18px; font-weight: 400; line-height: 25px; text-align: center;\">\r\n                  <p style=\"margin: 0;\">{{message}}</p>\r\n                </td>\r\n              </tr>\r\n            </tbody></table>\r\n        </td>\r\n    </tr>\r\n\r\n    <tr>\r\n        <td bgcolor=\"#f4f4f4\" align=\"center\" style=\"padding: 30px 10px 0px 10px;\">\r\n            <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"700\">\r\n                <tbody><tr>\r\n                  <td bgcolor=\"black\" align=\"center\" style=\"padding: 30px 30px 30px 30px; border-radius: 4px 4px 4px 4px; color: #666666; font-family: \'Lato\', Helvetica, Arial, sans-serif; font-size: 18px; font-weight: 400; line-height: 25px;\">\r\n                    <h2 style=\"font-size: 20px; font-weight: 400; color: white; margin: 0;\">©{{site_name}} All Rights Reserved.</h2>\r\n                  </td>\r\n                </tr>\r\n            </tbody></table>\r\n        </td>\r\n    </tr>\r\n</tbody></table>', 'Hi {{fullname}} ({{username}}), {{message}}', 'TonaBank', '{\"name\":\"php\"}', '{\"name\":\"custom\",\"nexmo\":{\"api_key\":\"-----\",\"api_secret\":\"-----\"},\"twilio\":{\"account_sid\":\"-----\",\"auth_token\":\"-----\",\"from\":\"-----\"},\"custom\":{\"method\":\"get\",\"url\":\"https:\\/\\/hostname\\/demo-api-v1\",\"headers\":{\"name\":[\"api_key\",\"Demo API\"],\"value\":[\"test_api_key\",\"Demo API\"]},\"body\":{\"name\":[\"from_number\",\"Demo body API\"],\"value\":[\"123456\",\"Demo body API\"]}}}', '{\r\n    \"site_name\":\"Name of your site\",\r\n    \"site_currency\":\"Currency of your site\",\r\n    \"currency_symbol\":\"Symbol of currency\"\r\n}', '0A3D62', 'EAB543', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 'primary', '2025-04-30 13:30:15', '2025-04-30 13:30:49', '2025-04-30 13:30:51', '2025-04-30 13:30:55', 1, '20.00000000', '30000.00000000', '100000.00000000', '500000.00000000', '3.00000000', '1.50', 1, 1, 1, 1, 1, 1, 1, 1, 0, 0, 0, NULL, '2025-05-11 07:31:31');

-- --------------------------------------------------------

--
-- Table structure for table `site_data`
--

CREATE TABLE `site_data` (
  `id` bigint UNSIGNED NOT NULL,
  `data_key` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `data_info` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `site_data`
--

INSERT INTO `site_data` (`id`, `data_key`, `data_info`, `created_at`, `updated_at`) VALUES
(1, 'seo.data', '{\"seo_image\":\"1\",\"keywords\":[\"Online Banking\",\"Digital Banking\",\"Personal Loans\",\"Savings Account\",\"Fixed Deposit\",\"Investment Plans\",\"Money Transfer\",\"Secure Transactions\",\"Encrypted Banking\",\"Two-Factor Authentication\",\"loan\",\"deposit\",\"fdr\",\"dps\",\"bank\",\"e-banking\",\"banking management system\",\"ebank\",\"fintech\",\"ibanking\",\"internet banking solution\",\"virtual card\"],\"description\":\"Bank - Secure and reliable online banking solutions. Manage your accounts, transfer funds, apply for loans, and invest smartly with our trusted financial services. Fast, secure, and easy banking at your fingertips!\",\"social_title\":\"TonaBank\",\"social_description\":\"Bank - Secure and reliable online banking solutions. Manage your accounts, transfer funds, apply for loans, and invest smartly with our trusted financial services. Fast, secure, and easy banking at your fingertips!\",\"image\":\"681336da31fd41746089690.png\"}', '2023-08-15 08:11:35', '2025-05-01 08:55:59'),
(8, 'cookie.data', '{\"short_details\":\"We may use cookies or any other tracking technologies when you visit our website, including any other media form, mobile website, or mobile application related or connected to help customize the Site and improve your experience.\",\"details\":\"<h4>Types of information we gather&nbsp;<\\/h4><p>&nbsp;<\\/p><p><strong>Personal Information:<\\/strong> When users register on TonaBank, we collect basic personal details such as name, email address, and optionally, profile pictures.<\\/p><p><strong>Bank Information:<\\/strong> We collect information provided by campaign creators, including campaign descriptions, goals, and supporting media content.<\\/p><p><strong>Donation Information:<\\/strong> For donation processing, we collect payment details such as credit card information or payment gateway credentials.<\\/p><p><strong>Usage Data:<\\/strong> We may collect non-personal information related to user interactions with the platform, such as IP addresses, browser type, and device identifiers.<\\/p><p>&nbsp;<\\/p><p>Ensuring the security of your information<\\/p><p><strong>User Accounts:<\\/strong>&nbsp;We retain user account information for as long as the account remains active or until the user requests its deletion.<\\/p><p><strong>Campaign Data:<\\/strong>&nbsp;Campaign information is retained for a reasonable period after the campaign\'s conclusion to facilitate auditing, reporting, and dispute resolution.<\\/p><p><strong>Donation Records:<\\/strong>&nbsp;Donation records are retained for compliance purposes and may be stored for a period required by applicable laws or regulations.<\\/p><p>Is any information shared with external parties?<br>&nbsp;<\\/p><p><strong>No, we do not sell,<\\/strong> trade, or otherwise transfer users\' personally identifiable information to outside parties without explicit consent.<\\/p><p><strong>Exceptional Circumstances:<\\/strong> We may disclose user information in response to legal requirements, enforcement of policies, or protection of rights, property, or safety.<\\/p><p>Duration of information retention<\\/p><p><strong>User Accounts:<\\/strong> We retain user account information for as long as the account remains active or until the user requests its deletion.<\\/p><p><strong>Bank Data:<\\/strong> Campaign information is retained for a reasonable period after the campaign\'s conclusion to facilitate auditing, reporting, and dispute resolution.<\\/p>\",\"status\":1}', NULL, '2025-05-01 07:35:36'),
(9, 'maintenance.data', '{\"heading\":\"Website Under Maintenance\",\"details\":\"<p>We\'re currently sprucing things up to provide you with an even better browsing experience. Our website is temporarily undergoing maintenance, but we\'ll be back online shortly.<\\/p><p>&nbsp;<\\/p><p>In the meantime, if you have any urgent inquiries or need assistance, feel free to reach out to us at <strong>example@example.com<\\/strong>. We apologize for any inconvenience caused and appreciate your patience.<\\/p><p>&nbsp;<\\/p><p>Thank you for your understanding!<\\/p><p><strong>TonaBank<\\/strong><\\/p>\"}', NULL, '2025-05-01 09:05:04'),
(11, 'policy_pages.element', '{\"title\":\"Privacy Policy\",\"details\":\"<h4>Types of information we gather\\u00a0<\\/h4><p>\\u00a0<\\/p><p><strong>Personal Information:<\\/strong> When users register on TonaBank, we collect basic personal details such as name, email address, and optionally, profile pictures.<\\/p><p><strong>Bank Information:<\\/strong> We collect information provided by campaign creators, including campaign descriptions, goals, and supporting media content.<\\/p><p><strong>Donation Information:<\\/strong> For donation processing, we collect payment details such as credit card information or payment gateway credentials.<\\/p><p><strong>Usage Data:<\\/strong> We may collect non-personal information related to user interactions with the platform, such as IP addresses, browser type, and device identifiers.<\\/p><p>\\u00a0<\\/p><p>Ensuring the security of your information<\\/p><p><strong>User Accounts:<\\/strong>\\u00a0We retain user account information for as long as the account remains active or until the user requests its deletion.<\\/p><p><strong>Campaign Data:<\\/strong>\\u00a0Campaign information is retained for a reasonable period after the campaign\'s conclusion to facilitate auditing, reporting, and dispute resolution.<\\/p><p><strong>Donation Records:<\\/strong>\\u00a0Donation records are retained for compliance purposes and may be stored for a period required by applicable laws or regulations.<\\/p><p>Is any information shared with external parties?<br \\/>\\u00a0<\\/p><p><strong>No, we do not sell,<\\/strong> trade, or otherwise transfer users\' personally identifiable information to outside parties without explicit consent.<\\/p><p><strong>Exceptional Circumstances:<\\/strong> We may disclose user information in response to legal requirements, enforcement of policies, or protection of rights, property, or safety.<\\/p><p>Duration of information retention<\\/p><p><strong>User Accounts:<\\/strong> We retain user account information for as long as the account remains active or until the user requests its deletion.<\\/p><p><strong>Bank Data:<\\/strong> Campaign information is retained for a reasonable period after the campaign\'s conclusion to facilitate auditing, reporting, and dispute resolution.<\\/p>\"}', '2023-11-09 04:17:26', '2025-05-01 07:29:18'),
(12, 'policy_pages.element', '{\"title\":\"Terms of Service\",\"details\":\"<h4>Types of information we gather\\u00a0<\\/h4><p>\\u00a0<\\/p><p><strong>Personal Information:<\\/strong> When users register on TonaBank, we collect basic personal details such as name, email address, and optionally, profile pictures.<\\/p><p><strong>Bank Information:<\\/strong> We collect information provided by campaign creators, including campaign descriptions, goals, and supporting media content.<\\/p><p><strong>Donation Information:<\\/strong> For donation processing, we collect payment details such as credit card information or payment gateway credentials.<\\/p><p><strong>Usage Data:<\\/strong> We may collect non-personal information related to user interactions with the platform, such as IP addresses, browser type, and device identifiers.<\\/p><p>\\u00a0<\\/p><p>Ensuring the security of your information<\\/p><p><strong>User Accounts:<\\/strong>\\u00a0We retain user account information for as long as the account remains active or until the user requests its deletion.<\\/p><p><strong>Campaign Data:<\\/strong>\\u00a0Campaign information is retained for a reasonable period after the campaign\'s conclusion to facilitate auditing, reporting, and dispute resolution.<\\/p><p><strong>Donation Records:<\\/strong>\\u00a0Donation records are retained for compliance purposes and may be stored for a period required by applicable laws or regulations.<\\/p><p>Is any information shared with external parties?<br \\/>\\u00a0<\\/p><p><strong>No, we do not sell,<\\/strong> trade, or otherwise transfer users\' personally identifiable information to outside parties without explicit consent.<\\/p><p><strong>Exceptional Circumstances:<\\/strong> We may disclose user information in response to legal requirements, enforcement of policies, or protection of rights, property, or safety.<\\/p><p>Duration of information retention<\\/p><p><strong>User Accounts:<\\/strong> We retain user account information for as long as the account remains active or until the user requests its deletion.<\\/p><p><strong>Bank Data:<\\/strong> Campaign information is retained for a reasonable period after the campaign\'s conclusion to facilitate auditing, reporting, and dispute resolution.<\\/p>\"}', '2023-11-09 04:17:51', '2025-05-01 07:29:02'),
(51, 'footer.content', '{\"footer_text\":\"Banking Solutions \\u2013 Your trusted partner for financial services. Experience exceptional banking with personalized solutions designed for your needs. Secure your future with us today!\",\"copyright_text\":\"\\u00a9 Copyright 2025. All rights reserved.\",\"background_image\":\"6804def729c611745149687.png\"}', '2024-01-28 04:51:36', '2025-04-20 11:48:07'),
(52, 'footer.element', '{\"social_icon\":\"<i class=\\\"ti ti-brand-facebook\\\"><\\/i>\",\"url\":\"https:\\/\\/www.facebook.com\\/\"}', '2024-01-28 04:52:44', '2024-06-03 03:39:20'),
(53, 'footer.element', '{\"social_icon\":\"<i class=\\\"ti ti-brand-x\\\"><\\/i>\",\"url\":\"https:\\/\\/twitter.com\\/\"}', '2024-01-28 04:56:10', '2024-06-03 03:39:42'),
(54, 'footer.element', '{\"social_icon\":\"<i class=\\\"ti ti-brand-linkedin\\\"><\\/i>\",\"url\":\"https:\\/\\/www.linkedin.com\\/\"}', '2024-01-28 05:01:24', '2024-06-03 03:40:22'),
(55, 'footer.element', '{\"social_icon\":\"<i class=\\\"ti ti-brand-instagram\\\"><\\/i>\",\"url\":\"https:\\/\\/www.instagram.com\\/\"}', '2024-01-28 05:02:20', '2024-06-03 03:40:40'),
(56, 'login.content', '{\"form_heading\":\"Log In\",\"submit_button_text\":\"Log In\",\"first_background_image\":\"680799408695f1745328448.png\",\"second_background_image\":\"680a2fc38d50a1745498051.png\",\"image\":\"6808af1ca02ed1745399580.png\"}', '2024-01-29 00:36:20', '2025-04-24 12:34:11'),
(57, 'register.content', '{\"form_heading\":\"Sign Up\",\"submit_button_text\":\"Sign Up\",\"first_background_image\":\"6808adeb2c98e1745399275.png\",\"second_background_image\":\"680a3807f02001745500167.png\",\"image\":\"6808ae7f070351745399423.png\"}', '2024-01-29 01:10:09', '2025-04-24 13:09:28'),
(58, 'kyc.content', '{\"verification_required_heading\":\"Verification Needed\",\"verification_required_details\":\"Ensure your account security and access exclusive features by providing the necessary verification details.\",\"verification_pending_heading\":\"Verification Pending\",\"verification_pending_details\":\"Your request for verification is in progress. We appreciate your patience as we ensure the security of your account.\"}', '2024-01-29 04:35:38', '2024-03-17 03:13:03'),
(59, 'forgot_password.content', '{\"form_heading\":\"Recover your account\",\"submit_button_text\":\"Next\",\"first_background_image\":\"6808b23d6e24f1745400381.png\",\"second_background_image\":\"680a36e43ddb71745499876.png\",\"image\":\"6808b4c5a641a1745401029.png\"}', '2024-01-29 23:31:38', '2025-04-24 13:04:36'),
(60, 'code_verification.content', '{\"form_heading\":\"Code Verification\",\"submit_button_text\":\"Submit\",\"first_background_image\":\"6808c6419c07a1745405505.png\",\"second_background_image\":\"680a34bdc3b731745499325.png\",\"image\":\"6808c76504ad91745405797.png\"}', '2024-01-30 00:17:10', '2025-04-24 12:55:26'),
(61, 'password_reset.content', '{\"form_heading\":\"Reset Password\",\"submit_button_text\":\"Submit\",\"first_background_image\":\"6808d3db21d321745408987.png\",\"second_background_image\":\"680a37bfefadd1745500095.png\",\"image\":\"680a0b93b9e411745488787.png\"}', '2024-01-30 01:10:22', '2025-04-24 13:08:16'),
(62, 'email_confirm.content', '{\"form_heading\":\"Verify Email Address\",\"submit_button_text\":\"Submit\",\"first_background_image\":\"6808b8520125a1745401938.png\",\"second_background_image\":\"680a35b5c4e0a1745499573.png\",\"image\":\"6808c3f7a79301745404919.png\"}', '2024-01-30 03:44:37', '2025-04-24 12:59:34'),
(63, 'mobile_confirm.content', '{\"form_heading\":\"Verify Phone Number\",\"submit_button_text\":\"Submit\",\"first_background_image\":\"6808c92a3a95e1745406250.png\",\"second_background_image\":\"680a3742851e01745499970.png\",\"image\":\"6808c92a6c71c1745406250.png\"}', '2024-01-30 04:09:27', '2025-04-24 13:06:10'),
(64, 'user_ban.content', '{\"form_heading\":\"You Are Banned\",\"first_background_image\":\"680a1993b68f21745492371.png\",\"second_background_image\":\"680a389d0be131745500317.png\",\"image\":\"680a19de7ab991745492446.png\"}', '2024-01-30 04:37:56', '2025-04-24 13:11:57'),
(65, '2fa_confirm.content', '{\"form_heading\":\"Two Factor Authentication\",\"form_text\":\"To confirm your identity, please enter the verification code generated by Google Authenticator below.\",\"submit_button_text\":\"Submit\",\"first_background_image\":\"6808cd4fc28751745407311.png\",\"second_background_image\":\"680a33cf32ca31745499087.png\",\"image\":\"680a33c4cbd4c1745499076.png\"}', '2024-01-30 06:18:23', '2025-04-24 12:51:27'),
(80, 'banner.content', '{\"subtitle\":\"Welcome To The E-Banking System\",\"title\":\"Smart Banking for a\",\"highlighted_part\":\"Digital World\",\"description\":\"Experience the future of finance with a digital banking platform built for speed, security, and simplicity. From daily expenses to long-term goals, our smart tools give you full control of your finances \\u2014 anytime, anywhere.\",\"youtube_video_id\":\"X8FkegKcfjo\",\"background_image\":\"67d7e91e61e741742203166.png\",\"video_button_background_image\":\"6718c3b6a21921729676214.png\",\"image\":\"67e0f09a1b6d61742794906.png\"}', '2024-03-18 03:23:22', '2025-05-10 07:48:43'),
(100, 'faq.content', '{\"section_first_subtitle\":\"Frequently Asked Questions\",\"section_second_subtitle\":\"FAQ\",\"section_title\":\"Unveiling Insights and\",\"highlighted_part\":\"Providing Clarity\",\"image\":\"67f50ca9d11ce1744112809.png\"}', '2024-03-19 04:42:04', '2025-04-09 05:07:04'),
(127, 'breadcrumb.content', '{\"background_image\":\"68060ac3e3b971745226435.png\"}', '2024-03-20 23:14:55', '2025-04-21 09:07:16'),
(128, 'contact_us.content', '{\"latitude\":\"39.925533\",\"longitude\":\"32.866287\",\"image\":\"680618b8e04711745230008.png\",\"background_image\":\"68073fa697f5f1745305510.png\"}', '2024-03-22 23:09:17', '2025-04-22 07:05:10'),
(129, 'contact_us.element', '{\"heading\":\"Address\",\"data\":\"Birmingham, Alabama, United States, PO Box : 1230\",\"image\":\"680627467abf41745233734.png\"}', '2024-03-22 23:10:21', '2025-05-01 07:33:05'),
(130, 'contact_us.element', '{\"heading\":\"Email Address\",\"data\":\"tonathemebank@example.com\",\"image\":\"680637ee6878a1745237998.png\"}', '2024-03-22 23:11:05', '2025-05-01 07:33:37'),
(131, 'contact_us.element', '{\"heading\":\"Phone\",\"data\":\"0123-456-789-10111213\",\"image\":\"6806422007a141745240608.png\"}', '2024-03-22 23:16:21', '2025-05-01 07:34:04'),
(156, 'subscribe.content', '{\"section_first_subtitle\":\"Join Our Newsletter\",\"section_second_subtitle\":\"SUBSCRIBE\",\"section_title\":\"Stay Informed with Our\",\"highlighted_part\":\"Latest Updates\",\"background_image\":\"6719eb9e4c0991729751966.png\"}', '2024-06-02 03:27:04', '2025-04-20 11:56:07'),
(173, 'partner.element', '{\"image\":\"6719ee9c66ceb1729752732.png\"}', '2024-06-03 02:36:36', '2024-10-24 06:52:12'),
(174, 'partner.element', '{\"image\":\"6719ee91e1c471729752721.png\"}', '2024-06-03 02:40:30', '2024-10-24 06:52:01'),
(175, 'partner.element', '{\"image\":\"6719ee859bfe31729752709.png\"}', '2024-06-03 02:41:08', '2024-10-24 06:51:49'),
(176, 'partner.element', '{\"image\":\"6719ee72a31af1729752690.png\"}', '2024-06-03 02:42:27', '2024-10-24 06:51:30'),
(177, 'partner.element', '{\"image\":\"6719ee6547acc1729752677.png\"}', '2024-06-03 02:43:05', '2024-10-24 06:51:17'),
(178, 'partner.element', '{\"image\":\"6719ee5b66cae1729752667.png\"}', '2024-06-03 02:43:22', '2024-10-24 06:51:07'),
(185, 'why_choose_us.content', '{\"section_first_subtitle\":\"Why Choose Us\",\"section_second_subtitle\":\"OUTMATCH\",\"section_title\":\"Compelling Reasons\",\"highlighted_part\":\"to Choose E-Bank\"}', '2024-08-15 05:39:31', '2025-03-25 06:22:08'),
(186, 'why_choose_us.element', '{\"icon\":\"<i class=\\\"ti ti-cash-register\\\"><\\/i>\",\"title\":\"Earn Extra Income\",\"short_description\":\"Maximize your earnings with secure financial opportunities, backed by robust encryption and authentication for your peace of mind.\"}', '2024-08-15 05:41:09', '2025-03-25 05:24:29'),
(187, 'why_choose_us.element', '{\"icon\":\"<i class=\\\"ti ti-user-shield\\\"><\\/i>\",\"title\":\"High Reliability\",\"short_description\":\"Explore customized financial solutions, from flexible accounts to personalized loans, designed to meet your unique needs.\"}', '2024-08-15 05:42:22', '2025-03-25 05:26:51'),
(188, 'why_choose_us.element', '{\"icon\":\"<i class=\\\"ti ti-cpu\\\"><\\/i>\",\"title\":\"Innovative Technology\",\"short_description\":\"Access quick, secure, and intuitive financial services with our advanced digital solutions, designed for a seamless experience.\"}', '2024-08-15 05:43:47', '2025-03-25 05:21:12'),
(190, 'testimonials.content', '{\"section_first_subtitle\":\"Voices of Satisfaction\",\"section_second_subtitle\":\"FEEDBACK\",\"section_title\":\"Experience Shared by\",\"highlighted_part\":\"Our Clients\",\"first_vector_image\":\"67f605db0f62e1744176603.png\",\"second_vector_image\":\"67f605db1415c1744176603.png\",\"background_image\":\"67f61f3025d701744183088.png\"}', '2024-08-15 06:03:45', '2025-04-09 08:30:53'),
(191, 'testimonials.element', '{\"client_name\":\"John Doe\",\"client_designation\":\"Home Loan Customer\",\"client_review\":\"Thanks to E-Bank, I was able to purchase my first home with a very competitive interest rate on my mortgage. The loan officers were incredibly helpful, walking me through every step. I felt supported and informed throughout the entire process.\",\"client_image\":\"6719e480ebb901729750144.jpg\"}', '2024-08-15 06:06:03', '2024-10-24 06:09:04'),
(192, 'testimonials.element', '{\"client_name\":\"Mark Smith\",\"client_designation\":\"Personal Savings Account Holder\",\"client_review\":\"I\\u2019ve been using E-Bank for my savings account for over five years now, and I couldn\\u2019t be happier. Their Fixed Deposit options offer some of the best rates, and the process is always hassle-free. The mobile app makes managing my account super easy!\",\"client_image\":\"6719e42a54f831729750058.jpg\"}', '2024-08-15 06:07:29', '2024-10-24 06:07:38'),
(193, 'testimonials.element', '{\"client_name\":\"Shelton Shannon\",\"client_designation\":\"Small Business Owner\",\"client_review\":\"Opening an account with E-Bank has been one of the best decisions for my business. The online banking platform is intuitive, and their customer support is always there when I need them. I was able to secure a business loan quickly, which helped me grow my operations. Highly recommend!\",\"client_image\":\"6719e3dbe8c4d1729749979.jpg\"}', '2024-08-15 06:09:53', '2024-10-24 06:06:19'),
(194, 'counters.element', '{\"title\":\"Happy Clients\",\"counter_number\":\"5678\",\"icon\":\"67f63a750cea21744190069.png\"}', '2024-08-15 06:48:25', '2025-04-09 09:14:29'),
(195, 'counters.element', '{\"title\":\"ATM Booths\",\"counter_number\":\"1234\",\"icon\":\"67f63a6b9b7bf1744190059.png\"}', '2024-08-15 06:49:14', '2025-04-09 09:14:19'),
(196, 'counters.element', '{\"title\":\"Years Experience\",\"counter_number\":\"20\",\"icon\":\"67f63a64e66281744190052.png\"}', '2024-08-15 06:50:17', '2025-04-09 09:14:12'),
(198, 'blog.element', '{\"title\":\"Strategies for Thriving in a Competitive Landscape\",\"description\":\"<p>In today\'s fast-paced world, freelancing has emerged as a popular choice for many professionals seeking flexibility, autonomy, and unlimited earning potential. However, succeeding in the gig economy requires more than just skills in your chosen field \\u2013 it requires mastering the art of freelancing. Whether you\'re a seasoned freelancer or just starting your journey, here are some tips to help you thrive in the competitive landscape of freelancing:<\\/p><p>\\u00a0<\\/p><p><strong>Define Your Niche:<\\/strong> Identify your strengths, passions, and areas of expertise. By specializing in a niche, you can position yourself as an expert in your field and attract high-paying clients who value your unique skills.<\\/p><p>\\u00a0<\\/p><p><strong>Build Your Brand:<\\/strong> Invest in creating a strong personal brand that reflects your values, personality, and professional identity. This includes designing a professional website, crafting a compelling portfolio, and establishing a strong presence on social media platforms relevant to your industry.<\\/p><p><br \\/><strong>Network Strategically:<\\/strong> Networking is key to success in freelancing. Attend industry events, join online communities, and connect with fellow freelancers and potential clients. Building genuine relationships and nurturing your network can lead to valuable collaborations and opportunities.<\\/p><p>\\u00a0<\\/p><p><strong>Hone Your Skills:<\\/strong> Continuous learning and improvement are essential in freelancing. Stay updated on industry trends, technologies, and best practices relevant to your field. Invest in online courses, workshops, and certifications to enhance your skills and stay ahead of the competition.<\\/p><p>\\u00a0<\\/p><p><strong>Set Clear Goals:<\\/strong> Define clear and achievable goals for your freelance career, whether it\'s increasing your income, expanding your client base, or launching a new service. Break down your goals into actionable steps and create a roadmap to track your progress.<\\/p><p>\\u00a0<\\/p><p><strong>Manage Your Finances:<\\/strong> Freelancing comes with financial uncertainties, so it\'s crucial to manage your finances wisely. Keep track of your income and expenses, set aside savings for taxes and emergencies, and consider investing in retirement accounts and insurance plans to secure your financial future.<\\/p><p>\\u00a0<\\/p><p><strong>Prioritize Self-Care:<\\/strong> Freelancing can be demanding, both mentally and physically. Prioritize self-care by establishing boundaries, maintaining a healthy work-life balance, and taking regular breaks to rest and recharge. Remember that your well-being is essential for sustained success.<\\/p><p>\\u00a0<\\/p><p><strong>Provide Exceptional Service:<\\/strong> Delivering exceptional service and exceeding client expectations is key to building a successful freelance business. Communicate clearly, meet deadlines, and always strive for excellence in your work. Happy clients are more likely to recommend you to others and become repeat customers.<\\/p><p>\\u00a0<\\/p><p>By implementing these tips and mastering the art of freelancing, you can unlock your full potential, build a thriving freelance business, and achieve long-term success in the gig economy. Embrace the opportunities, stay resilient in the face of challenges, and continue to evolve and grow as a freelancer. Your journey to success starts now!<\\/p>\",\"image\":\"66bdbe4ae6da21723711050.png\"}', '2024-08-15 08:37:30', '2024-08-15 08:37:31'),
(199, 'blog.element', '{\"title\":\"Essential Resources and Tools for Maximizing Productivity and Efficiency\",\"description\":\"<p>In today\'s fast-paced world, freelancing has emerged as a popular choice for many professionals seeking flexibility, autonomy, and unlimited earning potential. However, succeeding in the gig economy requires more than just skills in your chosen field \\u2013 it requires mastering the art of freelancing. Whether you\'re a seasoned freelancer or just starting your journey, here are some tips to help you thrive in the competitive landscape of freelancing:<\\/p><p>\\u00a0<\\/p><p><strong>Define Your Niche:<\\/strong> Identify your strengths, passions, and areas of expertise. By specializing in a niche, you can position yourself as an expert in your field and attract high-paying clients who value your unique skills.<\\/p><p>\\u00a0<\\/p><p><strong>Build Your Brand:<\\/strong> Invest in creating a strong personal brand that reflects your values, personality, and professional identity. This includes designing a professional website, crafting a compelling portfolio, and establishing a strong presence on social media platforms relevant to your industry.<\\/p><p><br \\/><strong>Network Strategically:<\\/strong> Networking is key to success in freelancing. Attend industry events, join online communities, and connect with fellow freelancers and potential clients. Building genuine relationships and nurturing your network can lead to valuable collaborations and opportunities.<\\/p><p>\\u00a0<\\/p><p><strong>Hone Your Skills:<\\/strong> Continuous learning and improvement are essential in freelancing. Stay updated on industry trends, technologies, and best practices relevant to your field. Invest in online courses, workshops, and certifications to enhance your skills and stay ahead of the competition.<\\/p><p>\\u00a0<\\/p><p><strong>Set Clear Goals:<\\/strong> Define clear and achievable goals for your freelance career, whether it\'s increasing your income, expanding your client base, or launching a new service. Break down your goals into actionable steps and create a roadmap to track your progress.<\\/p><p>\\u00a0<\\/p><p><strong>Manage Your Finances:<\\/strong> Freelancing comes with financial uncertainties, so it\'s crucial to manage your finances wisely. Keep track of your income and expenses, set aside savings for taxes and emergencies, and consider investing in retirement accounts and insurance plans to secure your financial future.<\\/p><p>\\u00a0<\\/p><p><strong>Prioritize Self-Care:<\\/strong> Freelancing can be demanding, both mentally and physically. Prioritize self-care by establishing boundaries, maintaining a healthy work-life balance, and taking regular breaks to rest and recharge. Remember that your well-being is essential for sustained success.<\\/p><p>\\u00a0<\\/p><p><strong>Provide Exceptional Service:<\\/strong> Delivering exceptional service and exceeding client expectations is key to building a successful freelance business. Communicate clearly, meet deadlines, and always strive for excellence in your work. Happy clients are more likely to recommend you to others and become repeat customers.<\\/p><p>\\u00a0<\\/p><p>By implementing these tips and mastering the art of freelancing, you can unlock your full potential, build a thriving freelance business, and achieve long-term success in the gig economy. Embrace the opportunities, stay resilient in the face of challenges, and continue to evolve and grow as a freelancer. Your journey to success starts now!<\\/p>\",\"image\":\"66bdbe6b20e4d1723711083.png\"}', '2024-08-15 08:38:03', '2024-08-18 06:21:08'),
(202, 'faq.element', '{\"question\":\"How can I protect my account from fraud?\",\"answer\":\"<p>To protect your account:<\\/p><ul><li>Do not share your passwords or PIN with anyone.<\\/li><li>Enable two-factor authentication (2FA) for added security.<\\/li><li>Regularly monitor your account for any unauthorized transactions.<\\/li><li>Report any suspicious activity immediately to our customer support.<\\/li><\\/ul>\"}', '2024-08-17 10:57:35', '2024-10-24 05:34:25'),
(203, 'faq.element', '{\"question\":\"How do I register for online banking?\",\"answer\":\"<p>You can register for online banking by visiting our website and clicking on the \\\"Register\\\" button. You\\u2019ll need your account details and personal identification for verification.<\\/p>\"}', '2024-08-17 10:58:20', '2024-10-24 05:33:05'),
(204, 'faq.element', '{\"question\":\"How can I apply for a loan?\",\"answer\":\"<p>You can apply for a loan by visiting any of our branches or applying online through the \\\"Loans\\\" section of our website. You will need to provide personal identification, proof of income, and other relevant documents depending on the type of loan.<\\/p>\"}', '2024-08-17 10:59:17', '2024-10-24 05:31:49'),
(205, 'faq.element', '{\"question\":\"What is Required to open a savings or current account?\",\"answer\":\"<ul><li>A valid government-issued ID (passport, driver\\u2019s license, etc.)<\\/li><li>Proof of address (utility bill, lease agreement, etc.)<\\/li><li>Passport-sized photographs<\\/li><li>Completed application form<\\/li><\\/ul>\"}', '2024-08-17 11:06:20', '2025-04-09 04:24:58'),
(206, 'faq.element', '{\"question\":\"How do I open a new bank account?\",\"answer\":\"<p>To open a new account, you can visit any of our branches or apply online through our website. You\'ll need to provide valid identification, proof of address, and complete the necessary application forms.<\\/p>\"}', '2024-08-17 11:07:19', '2024-10-24 05:29:15'),
(207, 'about_us.content', '{\"section_first_subtitle\":\"About Us\",\"section_second_subtitle\":\"ABOUT\",\"section_title\":\"Driving Progress in\",\"highlighted_part\":\"Modern Banking\",\"description\":\"<p>Welcome to E-Banking system, where innovation and financial excellence converge to redefine your banking experience. At E-Banking system, we embark on a journey beyond traditional banking, crafting a seamless digital landscape that empowers you to navigate the financial future with confidence.<\\/p><p>\\u00a0<\\/p><p>Our story is one of commitment to transparency, trust, and technological prowess. We stand at the forefront of the digital frontier, integrating cutting-edge solutions to provide you with a banking experience that transcends expectations.<\\/p>\",\"button_text\":\"Read More\",\"button_icon\":\"<i class=\\\"ti ti-arrow-up-right\\\"><\\/i>\",\"image\":\"67e0ef23de03b1742794531.png\"}', '2024-10-23 10:08:52', '2025-04-28 10:43:51'),
(208, 'services.content', '{\"section_first_subtitle\":\"Services\",\"section_second_subtitle\":\"SERVICE\",\"section_title\":\"Modern Solutions\",\"highlighted_part\":\"Tailored for You\",\"background_image\":\"67e112988fc351742803608.png\"}', '2024-10-23 10:34:53', '2025-03-24 08:09:07'),
(209, 'services.element', '{\"title\":\"Effortless Deposit Flow\",\"description\":\"Transform the way you manage your finances with our Effortless Deposit. Seamlessly deposit funds into your account without the hassle. Our user-friendly process ensures a smooth and stress-free experience, allowing you to focus on what matters most. Discover the ease of effortless deposits, making your financial journey as fluid as possible.\",\"image\":\"67e139dc0e1541742813660.png\"}', '2024-10-23 10:39:06', '2025-03-24 10:54:20'),
(210, 'services.element', '{\"title\":\"Lightning Fast Withdraw\",\"description\":\"Experience the thrill of immediate access to your funds with our Lightning Fast Withdraw service. Say goodbye to waiting \\u2013 whether it\'s for an unexpected expense or a spontaneous opportunity, our rapid withdrawal ensures your money is in your hands when you need it most. Effortless, swift, and reliable, it\'s the speed you deserve.\",\"image\":\"67e15f6929f541742823273.png\"}', '2024-10-23 10:40:11', '2025-03-24 13:34:33'),
(211, 'services.element', '{\"title\":\"Rocket Speed Transfer\",\"description\":\"Propel your transactions into the future with our Rocket Speed Transfer service. Whether you\'re sending money to family or completing a business transaction, our swift and secure transfer process ensures that your funds reach their destination at unparalleled speed. Trust in the velocity of our service for a seamless and efficient transfer experience.\",\"image\":\"67e168a4b40661742825636.png\"}', '2024-10-23 10:40:58', '2025-03-24 14:13:56'),
(212, 'process.content', '{\"section_first_subtitle\":\"How It Works\",\"section_second_subtitle\":\"PROCESS\",\"section_title\":\"A Deep Dive into Our\",\"highlighted_part\":\"eBanking System\",\"background_image\":\"67e266e38347e1742890723.png\"}', '2024-10-23 11:32:03', '2025-03-25 08:18:43'),
(213, 'process.element', '{\"title\":\"Create an Account\",\"short_description\":\"Start your financial journey hassle-free. Choose from our range of account options and enjoy a quick and easy account opening process designed with you in mind.\"}', '2024-10-23 11:33:08', '2024-10-23 11:33:08'),
(214, 'process.element', '{\"title\":\"Identity Verification\",\"short_description\":\"Your security matters. Our swift verification process ensures your identity is protected, offering you a safe and secure banking experience.\"}', '2024-10-23 11:34:01', '2024-10-23 11:34:01'),
(215, 'process.element', '{\"title\":\"Fund Your Account\",\"short_description\":\"Boost your balance effortlessly. Our user-friendly deposit process supports various methods, giving you flexibility and control over your finances.\"}', '2024-10-23 11:34:58', '2024-10-23 11:34:58'),
(216, 'process.element', '{\"title\":\"Access Your Benefits\",\"short_description\":\"Unlock a world of convenience as a registered account-holder. Now, you have seamless access to all our services tailored to meet your financial needs.\"}', '2024-10-23 11:36:08', '2024-10-23 11:36:08'),
(217, 'features.content', '{\"section_first_subtitle\":\"Key Features\",\"section_second_subtitle\":\"FEATURE\",\"section_title\":\"Empowering Your\",\"highlighted_part\":\"Financial Experience\",\"image\":\"67e2baeb2f2301742912235.png\",\"feature_background_image\":\"67e27a6cda3cf1742895724.png\"}', '2024-10-23 11:56:52', '2025-03-25 14:17:15'),
(218, 'features.element', '{\"icon\":\"<i class=\\\"ti ti-arrows-right-left\\\"><\\/i>\",\"title\":\"Transfer Money\",\"short_description\":\"You are able to transfer your funds within the E-Bank or other banks we support by adding your beneficiaries.\"}', '2024-10-23 11:59:45', '2024-10-23 11:59:45'),
(219, 'features.element', '{\"icon\":\"<i class=\\\"ti ti-wallet\\\"><\\/i>\",\"title\":\"Deposit Schemes\",\"short_description\":\"We have two deposit schemes for you, one is Deposit Pension Scheme and another one is the Fixed Deposit Receipt.\"}', '2024-10-23 12:01:27', '2024-10-23 12:01:27'),
(220, 'features.element', '{\"icon\":\"<i class=\\\"ti ti-moneybag\\\"><\\/i>\",\"title\":\"Take Loan\",\"short_description\":\"We have several plans to apply for a loan. You may apply to our loan plans by submitting some of your valid information.\"}', '2024-10-23 12:02:45', '2024-10-23 12:02:45'),
(221, 'dps.content', '{\"section_first_subtitle\":\"Deposit Pension Scheme\",\"section_second_subtitle\":\"DPS\",\"section_title\":\"Watch Your Deposits\",\"highlighted_part\":\"Blossom with E-Bank\",\"background_image\":\"67e3c28d5089e1742979725.png\",\"card_background\":\"67e3bed2419e31742978770.png\"}', '2024-10-24 04:32:10', '2025-03-26 09:02:05'),
(222, 'fds.content', '{\"section_first_subtitle\":\"Fixed Deposit Scheme\",\"section_second_subtitle\":\"FDS\",\"section_title\":\"Our Dedication,\",\"highlighted_part\":\"Your Benefits\"}', '2024-10-24 04:41:52', '2025-03-26 10:39:58'),
(223, 'ls.content', '{\"section_first_subtitle\":\"Loan Scheme\",\"section_second_subtitle\":\"LOAN\",\"section_title\":\"Optimal Loan Solutions are\",\"highlighted_part\":\"Waiting for You\",\"background_image\":\"67f507c26bbea1744111554.png\"}', '2024-10-24 05:01:44', '2025-04-08 11:25:54'),
(224, 'counters.content', '{\"background_image\":\"67dba7f5c4eb61742448629.png\"}', '2024-10-24 06:20:12', '2025-03-20 05:30:30'),
(225, 'counters.element', '{\"title\":\"Awards Recived\",\"counter_number\":\"159\",\"icon\":\"67f63a5e340fc1744190046.png\"}', '2024-10-24 06:25:07', '2025-04-28 10:24:18'),
(226, 'partner.content', '{\"section_first_subtitle\":\"Our Reliable Allies\",\"section_second_subtitle\":\"PARTNER\",\"section_title\":\"Our Network of\",\"highlighted_part\":\"Trusted Connections\"}', '2024-10-24 06:46:07', '2025-04-21 06:26:02'),
(227, 'user_dashboard.content', '{\"vector_image\":\"68077bd144e321745320913.png\",\"first_background_image\":\"68077b4b28ef51745320779.png\",\"second_background_image\":\"68077b4b334971745320779.png\"}', '2024-10-29 10:50:18', '2025-04-22 11:21:53'),
(228, 'user_panel.content', '{\"background_image\":\"6807728f948571745318543.png\"}', '2024-10-29 11:58:06', '2025-04-22 10:42:24'),
(229, 'why_choose_us.element', '{\"icon\":\"<i class=\\\"ti ti-shield-check\\\"><\\/i>\",\"title\":\"Trusted Security\",\"short_description\":\"With advanced encryption and multi-layered authentication, your financial information is always safe with us.\"}', '2025-03-25 05:52:57', '2025-03-25 05:52:57'),
(230, 'why_choose_us.element', '{\"icon\":\"<i class=\\\"ti ti-headset\\\"><\\/i>\",\"title\":\"Personalized Support\",\"short_description\":\"Get personalized expert advice and support whenever you need it, with a focus on your unique financial goals.\"}', '2025-03-25 05:54:03', '2025-03-25 05:56:53'),
(231, 'why_choose_us.element', '{\"icon\":\"<i class=\\\"ti ti-chart-bar\\\"><\\/i>\",\"title\":\"Financial Growth\",\"short_description\":\"We provide the tools and resources to help you grow your wealth and achieve your financial goals.\"}', '2025-03-25 05:57:54', '2025-03-25 05:57:54'),
(232, 'policy_pages.element', '{\"title\":\"Support Policy\",\"details\":\"<h4>Types of information we gather\\u00a0<\\/h4><p>\\u00a0<\\/p><p><strong>Personal Information:<\\/strong> When users register on TonaBank, we collect basic personal details such as name, email address, and optionally, profile pictures.<\\/p><p><strong>Bank Information:<\\/strong> We collect information provided by campaign creators, including campaign descriptions, goals, and supporting media content.<\\/p><p><strong>Donation Information:<\\/strong> For donation processing, we collect payment details such as credit card information or payment gateway credentials.<\\/p><p><strong>Usage Data:<\\/strong> We may collect non-personal information related to user interactions with the platform, such as IP addresses, browser type, and device identifiers.<\\/p><p>\\u00a0<\\/p><p>Ensuring the security of your information<\\/p><p><strong>User Accounts:<\\/strong>\\u00a0We retain user account information for as long as the account remains active or until the user requests its deletion.<\\/p><p><strong>Campaign Data:<\\/strong>\\u00a0Campaign information is retained for a reasonable period after the campaign\'s conclusion to facilitate auditing, reporting, and dispute resolution.<\\/p><p><strong>Donation Records:<\\/strong>\\u00a0Donation records are retained for compliance purposes and may be stored for a period required by applicable laws or regulations.<\\/p><p>Is any information shared with external parties?<br \\/>\\u00a0<\\/p><p><strong>No, we do not sell,<\\/strong> trade, or otherwise transfer users\' personally identifiable information to outside parties without explicit consent.<\\/p><p><strong>Exceptional Circumstances:<\\/strong> We may disclose user information in response to legal requirements, enforcement of policies, or protection of rights, property, or safety.<\\/p><p>Duration of information retention<\\/p><p><strong>User Accounts:<\\/strong> We retain user account information for as long as the account remains active or until the user requests its deletion.<\\/p><p><strong>Bank Data:<\\/strong> Campaign information is retained for a reasonable period after the campaign\'s conclusion to facilitate auditing, reporting, and dispute resolution.<\\/p>\"}', '2025-05-01 07:29:41', '2025-05-01 07:29:41');

-- --------------------------------------------------------

--
-- Table structure for table `staffs`
--

CREATE TABLE `staffs` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `username` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_address` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact_number` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `designation` tinyint UNSIGNED NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0: Inactive, 1: Active',
  `remember_token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `staff_password_reset_tokens`
--

CREATE TABLE `staff_password_reset_tokens` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subscribers`
--

CREATE TABLE `subscribers` (
  `id` bigint UNSIGNED NOT NULL,
  `email` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL DEFAULT '0',
  `branch_id` bigint UNSIGNED DEFAULT NULL,
  `staff_id` bigint UNSIGNED DEFAULT NULL,
  `amount` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `charge` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `post_balance` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `trx_type` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `trx` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `details` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remark` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `branch_id` bigint UNSIGNED DEFAULT NULL,
  `staff_id` bigint UNSIGNED DEFAULT NULL,
  `account_number` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `firstname` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lastname` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `username` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `country_code` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country_name` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mobile` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ref_by` int UNSIGNED NOT NULL DEFAULT '0',
  `referral_action_limit` int UNSIGNED NOT NULL DEFAULT '0',
  `balance` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` tinyint UNSIGNED NOT NULL DEFAULT '1' COMMENT '0: banned, 1: active',
  `kyc_data` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `kc` tinyint UNSIGNED NOT NULL DEFAULT '0' COMMENT '0: KYC unconfirmed, 2: KYC pending, 1: KYC confirmed',
  `ec` tinyint UNSIGNED NOT NULL DEFAULT '0' COMMENT '0: email unconfirmed, 1: email confirmed',
  `sc` tinyint UNSIGNED NOT NULL DEFAULT '0' COMMENT '0: mobile unconfirmed, 1: mobile confirmed',
  `ver_code` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'stores verification code',
  `ver_code_send_at` datetime DEFAULT NULL COMMENT 'verification send time',
  `ts` tinyint UNSIGNED NOT NULL DEFAULT '0' COMMENT '0: 2fa off, 1: 2fa on',
  `tc` tinyint UNSIGNED NOT NULL DEFAULT '1' COMMENT '0: 2fa unconfirmed, 1: 2fa confirmed',
  `tsc` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ban_reason` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wire_transfer_settings`
--

CREATE TABLE `wire_transfer_settings` (
  `id` bigint UNSIGNED NOT NULL,
  `per_transaction_min_amount` decimal(28,8) NOT NULL,
  `per_transaction_max_amount` decimal(28,8) NOT NULL,
  `daily_transaction_max_amount` decimal(28,8) NOT NULL,
  `daily_transaction_limit` int UNSIGNED NOT NULL,
  `monthly_transaction_max_amount` decimal(28,8) NOT NULL,
  `monthly_transaction_limit` int UNSIGNED NOT NULL,
  `fixed_charge` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `percentage_charge` decimal(5,2) NOT NULL DEFAULT '0.00',
  `instruction` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `form_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `wire_transfer_settings`
--

INSERT INTO `wire_transfer_settings` (`id`, `per_transaction_min_amount`, `per_transaction_max_amount`, `daily_transaction_max_amount`, `daily_transaction_limit`, `monthly_transaction_max_amount`, `monthly_transaction_limit`, `fixed_charge`, `percentage_charge`, `instruction`, `form_id`, `created_at`, `updated_at`) VALUES
(1, '0.00000000', '0.00000000', '0.00000000', 0, '0.00000000', 0, '0.00000000', '0.00', NULL, 0, '2025-05-11 07:50:08', '2025-05-11 07:50:08');

-- --------------------------------------------------------

--
-- Table structure for table `withdrawals`
--

CREATE TABLE `withdrawals` (
  `id` bigint UNSIGNED NOT NULL,
  `method_id` int UNSIGNED NOT NULL DEFAULT '0',
  `user_id` int UNSIGNED NOT NULL DEFAULT '0',
  `branch_id` bigint UNSIGNED DEFAULT NULL,
  `staff_id` bigint UNSIGNED DEFAULT NULL,
  `amount` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `currency` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rate` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `charge` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `trx` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `final_amount` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `after_charge` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `withdraw_information` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1 => success, 2 => pending, 3 => cancel  ',
  `admin_feedback` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `withdraw_methods`
--

CREATE TABLE `withdraw_methods` (
  `id` bigint UNSIGNED NOT NULL,
  `form_id` int UNSIGNED NOT NULL DEFAULT '0',
  `name` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `min_amount` decimal(28,8) DEFAULT '0.00000000',
  `max_amount` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `fixed_charge` decimal(28,8) DEFAULT '0.00000000',
  `rate` decimal(28,8) DEFAULT '0.00000000',
  `percent_charge` decimal(5,2) DEFAULT NULL,
  `currency` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `guideline` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` tinyint UNSIGNED NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `admin_notifications`
--
ALTER TABLE `admin_notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admin_password_resets`
--
ALTER TABLE `admin_password_resets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `beneficiaries`
--
ALTER TABLE `beneficiaries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `branches`
--
ALTER TABLE `branches`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `branches_name_unique` (`name`),
  ADD UNIQUE KEY `branches_code_unique` (`code`),
  ADD UNIQUE KEY `branches_routing_number_unique` (`routing_number`),
  ADD UNIQUE KEY `branches_swift_code_unique` (`swift_code`),
  ADD UNIQUE KEY `branches_email_unique` (`email`);

--
-- Indexes for table `branch_staff`
--
ALTER TABLE `branch_staff`
  ADD PRIMARY KEY (`branch_id`,`staff_id`);

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `deposits`
--
ALTER TABLE `deposits`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `deposit_pension_schemes`
--
ALTER TABLE `deposit_pension_schemes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `deposit_pension_scheme_plans`
--
ALTER TABLE `deposit_pension_scheme_plans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fixed_deposit_schemes`
--
ALTER TABLE `fixed_deposit_schemes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fixed_deposit_scheme_plans`
--
ALTER TABLE `fixed_deposit_scheme_plans`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `fixed_deposit_scheme_plans_name_unique` (`name`);

--
-- Indexes for table `forms`
--
ALTER TABLE `forms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gateways`
--
ALTER TABLE `gateways`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `gateway_currencies`
--
ALTER TABLE `gateway_currencies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `installments`
--
ALTER TABLE `installments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `languages`
--
ALTER TABLE `languages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `loans`
--
ALTER TABLE `loans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `loan_plans`
--
ALTER TABLE `loan_plans`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `loan_plans_name_unique` (`name`);

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `money_transfers`
--
ALTER TABLE `money_transfers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notification_templates`
--
ALTER TABLE `notification_templates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `other_banks`
--
ALTER TABLE `other_banks`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `other_banks_name_unique` (`name`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `plugins`
--
ALTER TABLE `plugins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `referral_settings`
--
ALTER TABLE `referral_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `site_data`
--
ALTER TABLE `site_data`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `staffs`
--
ALTER TABLE `staffs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `staffs_username_unique` (`username`),
  ADD UNIQUE KEY `staffs_email_address_unique` (`email_address`),
  ADD UNIQUE KEY `staffs_contact_number_unique` (`contact_number`);

--
-- Indexes for table `staff_password_reset_tokens`
--
ALTER TABLE `staff_password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `subscribers`
--
ALTER TABLE `subscribers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`,`email`),
  ADD UNIQUE KEY `account_number` (`account_number`);

--
-- Indexes for table `wire_transfer_settings`
--
ALTER TABLE `wire_transfer_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `withdrawals`
--
ALTER TABLE `withdrawals`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `withdraw_methods`
--
ALTER TABLE `withdraw_methods`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `admin_notifications`
--
ALTER TABLE `admin_notifications`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `admin_password_resets`
--
ALTER TABLE `admin_password_resets`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `beneficiaries`
--
ALTER TABLE `beneficiaries`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `branches`
--
ALTER TABLE `branches`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `deposits`
--
ALTER TABLE `deposits`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `deposit_pension_schemes`
--
ALTER TABLE `deposit_pension_schemes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `deposit_pension_scheme_plans`
--
ALTER TABLE `deposit_pension_scheme_plans`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fixed_deposit_schemes`
--
ALTER TABLE `fixed_deposit_schemes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fixed_deposit_scheme_plans`
--
ALTER TABLE `fixed_deposit_scheme_plans`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `forms`
--
ALTER TABLE `forms`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gateways`
--
ALTER TABLE `gateways`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `gateway_currencies`
--
ALTER TABLE `gateway_currencies`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `installments`
--
ALTER TABLE `installments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `languages`
--
ALTER TABLE `languages`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `loans`
--
ALTER TABLE `loans`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `loan_plans`
--
ALTER TABLE `loan_plans`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `money_transfers`
--
ALTER TABLE `money_transfers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notification_templates`
--
ALTER TABLE `notification_templates`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT for table `other_banks`
--
ALTER TABLE `other_banks`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=177;

--
-- AUTO_INCREMENT for table `plugins`
--
ALTER TABLE `plugins`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `referral_settings`
--
ALTER TABLE `referral_settings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `site_data`
--
ALTER TABLE `site_data`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=233;

--
-- AUTO_INCREMENT for table `staffs`
--
ALTER TABLE `staffs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subscribers`
--
ALTER TABLE `subscribers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wire_transfer_settings`
--
ALTER TABLE `wire_transfer_settings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `withdrawals`
--
ALTER TABLE `withdrawals`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `withdraw_methods`
--
ALTER TABLE `withdraw_methods`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
