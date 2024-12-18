/*
 Navicat Premium Data Transfer

 Source Server         : LOCAL-LAPTOP
 Source Server Type    : MySQL
 Source Server Version : 100424
 Source Host           : localhost:3306
 Source Schema         : db_system_inventory

 Target Server Type    : MySQL
 Target Server Version : 100424
 File Encoding         : 65001

 Date: 18/12/2024 16:39:57
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for failed_jobs
-- ----------------------------
DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `failed_jobs_uuid_unique`(`uuid`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of failed_jobs
-- ----------------------------

-- ----------------------------
-- Table structure for migrations
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 110 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of migrations
-- ----------------------------
INSERT INTO `migrations` VALUES (102, '2014_10_12_000000_create_users_table', 1);
INSERT INTO `migrations` VALUES (103, '2014_10_12_100000_create_password_reset_tokens_table', 1);
INSERT INTO `migrations` VALUES (104, '2019_08_19_000000_create_failed_jobs_table', 1);
INSERT INTO `migrations` VALUES (105, '2019_12_14_000001_create_personal_access_tokens_table', 1);
INSERT INTO `migrations` VALUES (106, '2024_12_16_114038_create_warehouses_table', 1);
INSERT INTO `migrations` VALUES (107, '2024_12_16_114140_create_products_table', 1);
INSERT INTO `migrations` VALUES (108, '2024_12_16_114206_create_stocks_table', 1);
INSERT INTO `migrations` VALUES (109, '2024_12_16_114242_create_transactions_table', 1);

-- ----------------------------
-- Table structure for password_reset_tokens
-- ----------------------------
DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens`  (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of password_reset_tokens
-- ----------------------------

-- ----------------------------
-- Table structure for personal_access_tokens
-- ----------------------------
DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE `personal_access_tokens`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `personal_access_tokens_token_unique`(`token`) USING BTREE,
  INDEX `personal_access_tokens_tokenable_type_tokenable_id_index`(`tokenable_type`, `tokenable_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of personal_access_tokens
-- ----------------------------
INSERT INTO `personal_access_tokens` VALUES (2, 'App\\Models\\User', 2, 'sanctum', 'b91aba62f917b3bf8390f29891b4b88e216b97c88f8a8aba23e90ad515eb1821', '[\"*\"]', NULL, NULL, '2024-12-18 09:20:10', '2024-12-18 09:20:10');

-- ----------------------------
-- Table structure for products
-- ----------------------------
DROP TABLE IF EXISTS `products`;
CREATE TABLE `products`  (
  `product_id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`product_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 9 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of products
-- ----------------------------
INSERT INTO `products` VALUES (1, 'Laptop Asus', '2024-12-18 09:03:37', '2024-12-18 09:03:37');
INSERT INTO `products` VALUES (2, 'Laptop HP', '2024-12-18 09:03:44', '2024-12-18 09:03:44');
INSERT INTO `products` VALUES (3, 'Tablet', '2024-12-18 09:06:18', '2024-12-18 09:06:18');
INSERT INTO `products` VALUES (4, 'Keyboard', '2024-12-18 09:06:30', '2024-12-18 09:06:30');
INSERT INTO `products` VALUES (7, 'Kabel terminal', '2024-12-18 09:06:46', '2024-12-18 09:08:04');
INSERT INTO `products` VALUES (8, 'Iphone', '2024-12-18 09:22:50', '2024-12-18 09:23:50');

-- ----------------------------
-- Table structure for stocks
-- ----------------------------
DROP TABLE IF EXISTS `stocks`;
CREATE TABLE `stocks`  (
  `stock_id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `warehouse_id` int UNSIGNED NOT NULL,
  `product_id` int UNSIGNED NOT NULL,
  `qty` bigint NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`stock_id`) USING BTREE,
  INDEX `stocks_warehouse_id_foreign`(`warehouse_id`) USING BTREE,
  INDEX `stocks_product_id_foreign`(`product_id`) USING BTREE,
  CONSTRAINT `stocks_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `stocks_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`warehouse_id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 8 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of stocks
-- ----------------------------
INSERT INTO `stocks` VALUES (1, 1, 1, 150, '2024-12-18 09:17:01', '2024-12-18 09:18:19');
INSERT INTO `stocks` VALUES (2, 2, 1, 200, '2024-12-18 09:17:07', '2024-12-18 09:18:41');
INSERT INTO `stocks` VALUES (3, 1, 2, 200, '2024-12-18 09:17:12', '2024-12-18 09:17:12');
INSERT INTO `stocks` VALUES (4, 1, 4, 300, '2024-12-18 09:17:17', '2024-12-18 09:17:17');
INSERT INTO `stocks` VALUES (5, 4, 3, 700, '2024-12-18 09:17:23', '2024-12-18 09:36:05');
INSERT INTO `stocks` VALUES (6, 4, 1, 10, '2024-12-18 09:17:29', '2024-12-18 09:17:29');
INSERT INTO `stocks` VALUES (7, 2, 4, 100, '2024-12-18 09:33:26', '2024-12-18 09:33:26');

-- ----------------------------
-- Table structure for transactions
-- ----------------------------
DROP TABLE IF EXISTS `transactions`;
CREATE TABLE `transactions`  (
  `transaction_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `transaction_type` enum('in','out') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `warehouse_id` int UNSIGNED NOT NULL,
  `product_id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `qty_transactions` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`transaction_id`) USING BTREE,
  INDEX `transactions_warehouse_id_foreign`(`warehouse_id`) USING BTREE,
  INDEX `transactions_product_id_foreign`(`product_id`) USING BTREE,
  INDEX `transactions_user_id_foreign`(`user_id`) USING BTREE,
  CONSTRAINT `transactions_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `transactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `transactions_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`warehouse_id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of transactions
-- ----------------------------
INSERT INTO `transactions` VALUES ('IT-001', 'in', 1, 1, 1, 100, '2024-12-18 09:18:05', '2024-12-18 09:18:05');
INSERT INTO `transactions` VALUES ('IT-002', 'out', 1, 1, 1, 50, '2024-12-18 09:18:19', '2024-12-18 09:18:19');
INSERT INTO `transactions` VALUES ('IT-003', 'in', 2, 1, 1, 100, '2024-12-18 09:18:41', '2024-12-18 09:18:41');
INSERT INTO `transactions` VALUES ('IT-004', 'in', 4, 3, 1, 200, '2024-12-18 09:36:05', '2024-12-18 09:36:05');

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`  (
  `user_id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`user_id`) USING BTREE,
  UNIQUE INDEX `users_email_unique`(`email`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES (1, 'admin@gmail.com', 'Admin', '$2y$12$hcTLrG7nS.JhlBB4pff6X.tbE8T00vStQV4nA0DeAPiCmCed9sgDC', NULL, NULL);
INSERT INTO `users` VALUES (2, 'anggi@gmail.com', 'Anggi', '$2y$12$ENmoswOZV8rq22ISLUuGJO5qnMV4d.9v2hNdVPIuw6Mqf5cjjw7Xq', '2024-12-18 09:03:15', '2024-12-18 09:03:15');
INSERT INTO `users` VALUES (3, 'Ahmad@gmail.com', 'Ahmad', '$2y$12$V5ts.qzzSUADhF4IccD2GuxAaKqhupFixspCeR.QSUWCAtg/IhPfa', '2024-12-18 09:20:58', '2024-12-18 09:20:58');

-- ----------------------------
-- Table structure for warehouses
-- ----------------------------
DROP TABLE IF EXISTS `warehouses`;
CREATE TABLE `warehouses`  (
  `warehouse_id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`warehouse_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 14 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of warehouses
-- ----------------------------
INSERT INTO `warehouses` VALUES (1, 'Warehouse A', '2024-12-18 09:09:58', '2024-12-18 09:09:58');
INSERT INTO `warehouses` VALUES (2, 'Warehouse Prada Sukma Jaya', '2024-12-18 09:10:01', '2024-12-18 09:14:10');
INSERT INTO `warehouses` VALUES (4, 'Warehouse Melawai', '2024-12-18 09:10:08', '2024-12-18 09:10:08');
INSERT INTO `warehouses` VALUES (5, 'Warehouse Melati', '2024-12-18 09:10:11', '2024-12-18 09:10:11');
INSERT INTO `warehouses` VALUES (6, 'Warehouse Singaparna', '2024-12-18 09:14:24', '2024-12-18 09:14:24');
INSERT INTO `warehouses` VALUES (7, 'Warehouse Ambarawa', '2024-12-18 09:14:28', '2024-12-18 09:14:28');
INSERT INTO `warehouses` VALUES (8, 'Warehouse Pejuang', '2024-12-18 09:14:33', '2024-12-18 09:14:33');
INSERT INTO `warehouses` VALUES (9, 'Warehouse Budi Pekerti', '2024-12-18 09:14:39', '2024-12-18 09:14:39');
INSERT INTO `warehouses` VALUES (10, 'Warehouse Binaan', '2024-12-18 09:14:44', '2024-12-18 09:14:44');
INSERT INTO `warehouses` VALUES (11, 'Warehouse Bersaudara', '2024-12-18 09:14:48', '2024-12-18 09:26:18');
INSERT INTO `warehouses` VALUES (12, 'Warehouse Flora', '2024-12-18 09:14:59', '2024-12-18 09:14:59');

SET FOREIGN_KEY_CHECKS = 1;
