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
            `date` DATE NOT NULL,
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
            ('1','Фидерные удилища'),
            ('2','Болонские удилища'),
            ('3','Маховые удилища'),
            ('4','Спиннинговые удилища');";
            $this->pdo->exec($insertCategoriesQuery);
        }

        // Вставка записей в таблицу products
        if (!$this->productsExist()) {
            $insertProductsQuery = "INSERT INTO `products` (`id`, `product_name`, `product_price`, `product_image`,`date`,`category_id`) VALUES
            ('1','Фидерное удилище Fishing ROI Titan Key Seven 360 Feeder 100gr', '1979', 'udilische-key-seven_2.600x340.jpg', '2025-08-21 21:13:54', '1'),
            ('2','Фидерное удилище Fishing ROI Titan Key Seven 360 Feeder 120gr', '2054', 'udilische-key-seven_1.600x340.jpg', '2025-08-21 21:13:54', '1'),
            ('3','Фидерное удилище Fishing ROI REWIN 360 M Method-Feeder 100gr', '1774', '225-76-360.600x340.jpg', '2025-08-21 21:13:54', '1'),
            ('4','Болонское удилище Fishing ROI Cyclone bolo 600 с/к', '2070', 'udilische_fishing_roi_bolognese_cyclone_2v_1.600x340.jpg', '2025-08-21 21:13:54', '2'),
            ('5','Болонское удилище Fishing ROI Cyclone bolo 500 с/к', '1710', 'udilische_fishing_roi_bolognese_cyclone_2v_3.600x340.jpg', '2025-08-21 21:13:54', '2'),
            ('6','Маховое удилище Fishing ROI Telepole Cyclone 500 б/к', '1178', 'udilische_fishing_roi_telepole_cyclone_2v_4.600x340.jpg', '2025-08-21 21:13:54', '3'),
            ('7','Маховое удилище Fishing ROI Telepole Cyclone 600 б/к', '1482', 'udilische_fishing_roi_telepole_cyclone_2v_3.600x340.jpg', '2025-08-21 21:13:54', '3'),
            ('8','Спиннинг FR ANACONDA 2,10м (702M) 5-25gr', '1117', 'spinning-fr-anaconda.600x340.jpg', '2025-08-21 21:13:54', '4'),
            ('9','Спиннинг Fishing ROI X-Viper 2.10m MT 5-25g', '1050', 'spinning-fishing-roi-x-viper-2.10m-mt-5-25g', '2025-08-21 21:13:54', '4'),
            ('10','Спиннинг Fishing ROI XT-ONE 5-25g 2.10m', '803', 'spinning-fishing-roi-xt-one.600x340.jpg', '2025-08-21 21:13:54', '4');";
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