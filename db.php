<?php
$host = "MySql-8.0";
$db   = "solomono-test";
$user = "root";
$pass = "";

// Подключение через PDO
try {
    $dsn = "mysql:host=$host;dbname=$db;charset=utf8";
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Ошибка подключения: " . $e->getMessage());
}

class DatabaseSetup
{

    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }
    // Проверка на наличие таблиц и записей в базе

    private function categoriesExist(){
        $query = "SELECT COUNT(*) FROM `categories`";
        $stmt = $this->pdo->query($query);
        return $stmt->fetchColumn() > 0;
    }

    private function productsExist(){
        $query = "SELECT COUNT(*) FROM `products`";
        $stmt = $this->pdo->query($query);
        return $stmt->fetchColumn() > 0;
    }

    public function createTable()
    {
        $categoryTableQuery = "CREATE TABLE IF NOT EXISTS `categories` (
            `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
            `category_name` VARCHAR(255) NOT NULL
        )";

        $productTableQuery = "CREATE TABLE IF NOT EXISTS `products` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `product_name` VARCHAR(255) NOT NULL,
            `product_price` INT(11) NOT NULL, 
            `product_image` VARCHAR(255) NOT NULL,           
            `product_date` DATETIME NOT NULL,
            `product_test` INT(11) NOT NULL,
            `product_length` INT(11) NOT NULL,
            `category_id` INT(11) NOT NULL,
            PRIMARY KEY (`id`),
            FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`)
        )";
    try{
        $this->pdo->exec($categoryTableQuery);
        $this->pdo->exec($productTableQuery);

        // Вставка записей в таблицу categories
        if (!$this->categoriesExist()) {
            $insertCategoriesQuery = "INSERT INTO `categories` (`id`, `category_name`) VALUES
            ('1','Фідерні вудилища'),
            ('2','Болонські вудки'),
            ('3','Махові вудилища'),
            ('4','Спінінгові вудилища');";
            $this->pdo->exec($insertCategoriesQuery);
        }

        // Вставка записей в таблицу products
        if (!$this->productsExist()) {
            $insertProductsQuery = "INSERT INTO `products` (`id`, `product_name`, `product_price`, `product_image`,`product_date`,`product_test`,`product_length`, `category_id`) VALUES
            ('1','Фідерне вудилище Fishing ROI Titan Key Seven 360 Feeder 100gr', '1979', 'udilische-key-seven_2.600x340.jpg', '2025-08-21 21:13:20', '100', '360', '1'),
            ('2','Фідерне вудилище Fishing ROI Titan Key Seven 360 Feeder 120gr', '2054', 'udilische-key-seven_1.600x340.jpg', '2025-08-21 21:13:21', '120', '360', '1'),
            ('3','Фідерне вудилище Fishing ROI REWIN 360 M Method-Feeder 100gr', '1774', '225-76-360.600x340.jpg', '2025-08-21 21:13:23', '100', '360', '1'),
            ('4','Фідерне вудилище Fishing ROI Titan Key Seven 390 Feeder 120gr', '1799', 'udilische-key-seven.600x340.jpg', '2025-08-21 21:13:23', '120', '390', '1'),
            ('5','Болонське вудилище Fishing ROI Cyclone bolo 600 с/к', '2070', 'udilische_fishing_roi_bolognese_cyclone_2v_1.600x340.jpg', '2025-08-21 21:13:24', '30', '600', '2'),
            ('6','Болонське вудилище Fishing ROI Cyclone bolo 500 с/к', '1710', 'udilische_fishing_roi_bolognese_cyclone_2v_3.600x340.jpg', '2025-08-21 21:13:25', '30', '500', '2'),
            ('7','Махове вудилище Fishing ROI Telepole Cyclone 500 б/к', '1178', 'udilische_fishing_roi_telepole_cyclone_2v_4.600x340.jpg', '2025-08-21 21:13:27', '25', '500', '3'),
            ('8','Махове вудилище Fishing ROI Telepole Cyclone 600 б/к', '1482', 'udilische_fishing_roi_telepole_cyclone_2v_3.600x340.jpg', '2025-08-21 21:13:30', '25', '600', '3'),
            ('9','Спінінг FR ANACONDA 2,10м (702M) 5-25gr', '1117', 'spinning-fr-anaconda.600x340.jpg', '2025-08-21 21:13:35', '25', '210', '4'),
            ('10','Спінінг Fishing ROI X-Viper 2.10m MT 5-25g', '1050', 'kupit-spinning-fishing-roi-x-viper_1.600x340.jpg', '2025-08-21 21:13:40', '25', '210', '4'),
            ('11','Спінінг Fishing ROI XT-ONE 7-32g 2.40m', '895', 'spinning-fishing-roi-xt-one.600x340.jpg', '2025-08-21 21:13:41', '32', '240', '4'),
            ('12','Спінінг Fishing ROI XT-ONE 5-25g 2.10m', '803', 'spinning-fishing-roi-xt-one.600x340.jpg', '2025-08-21 21:13:42', '25', '210', '4');";
            $this->pdo->exec($insertProductsQuery);
        }
    
        return true;
    } catch(\PDOException $e){
            return false;
        }
    }    

}

// Использование
$dbSetup = new DatabaseSetup($pdo);
$dbSetup->createTable();