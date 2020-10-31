START TRANSACTION;

--
-- Database: Shop
--

DROP DATABASE IF EXISTS shop;
CREATE DATABASE shop;
USE shop;

-- --------------------------------------------------------

--
-- Table: Users
--

DROP TABLE IF EXISTS users;
CREATE TABLE `users` (
    id SERIAL PRIMARY KEY,
    firstname VARCHAR(64) NOT NULL,
    lastname VARCHAR(64) DEFAULT '',
    password varchar(128) DEFAULT '',
    email VARCHAR(128) UNIQUE NOT NULL,
    admin BIT DEFAULT 0,

    created_at DATETIME DEFAULT NOW(),
    updated_at DATETIME DEFAULT NOW() ON UPDATE NOW(),

    INDEX users_firstname_lastname_idx (firstname(8), lastname(8)),
    INDEX users_lastname_firstname_idx (lastname(8), firstname(8))
) COMMENT 'Пользователи';

--
-- Table: Goods
--

DROP TABLE IF EXISTS goods;
CREATE TABLE goods (
    id SERIAL PRIMARY KEY,
    name VARCHAR(192) NOT NULL UNIQUE,
    title VARCHAR(128) NOT NULL,
    description TEXT,
    image BIGINT UNSIGNED NULL,
    price INT DEFAULT 0,

    created_at DATETIME DEFAULT NOW(),
    updated_at DATETIME DEFAULT NOW() ON UPDATE NOW(),

    INDEX goods_price_idx (price)
) COMMENT 'Товары';

--
-- Table: Images
--

CREATE TABLE images (
    id SERIAL PRIMARY KEY,
    link VARCHAR(256) NOT NULL,
    id_product BIGINT UNSIGNED NOT NULL,

    created_at DATETIME DEFAULT NOW(),
    updated_at DATETIME DEFAULT NOW() ON UPDATE NOW(),

    INDEX images_product_idx (id_product)
) COMMENT 'Изображения товаров';

--
-- Table: Feedbacks
--

CREATE TABLE feedbacks (
    id_user BIGINT UNSIGNED,
    id_product BIGINT UNSIGNED,
    body TEXT NOT NULL,

    created_at DATETIME DEFAULT NOW(),
    updated_at DATETIME DEFAULT NOW() ON UPDATE NOW(),

    PRIMARY KEY (id_user, id_product),
    INDEX feedbacks_product_user_idx (id_product, id_user)
) COMMENT 'Отзывы о товарах';

--
-- Table: Orders
--

CREATE TABLE orders (
    id SERIAL PRIMARY KEY,
    id_user BIGINT UNSIGNED NOT NULL,
    status SET('Передан на обработку',
                'Формируется к отправке',
                'Подготовлен счет на оплату',
                'Ждите звонка от оператора',
                'Едет в пункт выдачи',
                'Ожидаем поставку товара',
                'Отменен',
                'Готов к получению',
                'Передан в отдел доставки',
                'Передан курьеру',
                'Передан в транспортную компанию',
                'Нам не удалось с Вами связаться',
                'Выполнен') NOT NULL,

    created_at DATETIME DEFAULT NOW(),
    updated_at DATETIME DEFAULT NOW() ON UPDATE NOW(),

    INDEX orders_id_user_idx (id_user)
) COMMENT 'Заказы пользователей';

--
-- Table: Order/Product
--

CREATE TABLE order_product (
    id_order BIGINT UNSIGNED,
    id_product BIGINT UNSIGNED,
    price DECIMAL(10,2) NOT NULL,
    quantity INT UNSIGNED NOT NULL,

    PRIMARY KEY (id_product, id_order)
) COMMENT 'Товары в заказах';

--
-- Dump database
--

INSERT INTO users VALUES
    (1, 'Анатолий', 'Андреев', '$2y$10$Q3nbTrKBLB/IoCm4b4E7UeYzNV1zEnsjHWz2.m9cWx/edVCaPfoEu',
        'andreev.msk@ya.ru', 0, DEFAULT, DEFAULT),
    (2, 'Алексей', 'Бобриков', '$2y$10$JYrZe5Wmis2iHrnFylXKcOHLfckr/wrSwYaylYFANwz1DpzaBtaGi',
        'bobrikov.spb@ya.ru', 1, DEFAULT, DEFAULT),
    (3, 'Марина', 'Астрякова', '$2y$10$fn6Ogh8J638WSlUPtSixLekLkfN7X0zOjXZFK2WXr/BJB1RSaGB7i',
        'astryakova.chr@ya.ru', 0, DEFAULT, DEFAULT);

INSERT INTO images VALUES
    (1, 'https://avatars.mds.yandex.net/get-mpic/1360852/img_id2508126924791923402.jpeg/orig', 1, DEFAULT, DEFAULT),
    (2, 'https://avatars.mds.yandex.net/get-mpic/1363071/img_id4618435699647098574.jpeg/orig', 1, DEFAULT, DEFAULT),
    (3, 'https://avatars.mds.yandex.net/get-mpic/932277/img_id8895455767670829773.jpeg/orig', 1, DEFAULT, DEFAULT),
    (4, 'https://avatars.mds.yandex.net/get-mpic/1056698/img_id928265425244961193.jpeg/orig', 1, DEFAULT, DEFAULT),
    (5, 'https://avatars.mds.yandex.net/get-mpic/1644362/img_id7639771822857696854.png/orig', 2, DEFAULT, DEFAULT),
    (6, 'https://avatars.mds.yandex.net/get-mpic/1644362/img_id3874083265819140661.jpeg/orig', 2, DEFAULT, DEFAULT),
    (7, 'https://avatars.mds.yandex.net/get-mpic/1862611/img_id3875664305237307428.jpeg/orig', 2, DEFAULT, DEFAULT),
    (8, 'https://avatars.mds.yandex.net/get-mpic/1525215/img_id2134311285036447010.jpeg/orig', 2, DEFAULT, DEFAULT),
    (9, 'https://avatars.mds.yandex.net/get-mpic/1901647/img_id6102094781164549117.jpeg/orig', 3, DEFAULT, DEFAULT),
    (10, 'https://avatars.mds.yandex.net/get-mpic/1767083/img_id2551798935058468322.jpeg/orig', 3, DEFAULT, DEFAULT),
    (11, 'https://avatars.mds.yandex.net/get-mpic/1707869/img_id3182441690383315907.jpeg/orig', 3, DEFAULT, DEFAULT),
    (12, 'https://avatars.mds.yandex.net/get-mpic/1924580/img_id6208666797214031108.jpeg/orig', 3, DEFAULT, DEFAULT),
    (13, 'https://avatars.mds.yandex.net/get-mpic/1859063/img_id7318842660252674730.jpeg/orig', 4, DEFAULT, DEFAULT),
    (14, 'https://avatars.mds.yandex.net/get-mpic/1860966/img_id573911146118293618.png/orig', 4, DEFAULT, DEFAULT),
    (15, 'https://avatars.mds.yandex.net/get-mpic/1888674/img_id902705695145552678.jpeg/orig', 4, DEFAULT, DEFAULT),
    (16, 'https://avatars.mds.yandex.net/get-mpic/1525999/img_id4800216579421406330.jpeg/orig', 4, DEFAULT, DEFAULT),
    (17, 'https://avatars.mds.yandex.net/get-mpic/1602935/img_id2703560629284691572.jpeg/orig', 5, DEFAULT, DEFAULT),
    (18, 'https://avatars.mds.yandex.net/get-mpic/1626700/img_id3955340713105595325.jpeg/orig', 5, DEFAULT, DEFAULT),
    (19, 'https://avatars.mds.yandex.net/get-mpic/1680954/img_id9022509729845770990.jpeg/orig', 5, DEFAULT, DEFAULT),
    (20, 'https://avatars.mds.yandex.net/get-mpic/1614201/img_id678035074593505463.jpeg/orig', 5, DEFAULT, DEFAULT),
    (21, 'https://avatars.mds.yandex.net/get-mpic/1750207/img_id6196316363880704562.jpeg/orig', 5, DEFAULT, DEFAULT),
    (22, 'https://avatars.mds.yandex.net/get-mpic/1571888/img_id392541451731750298.jpeg/orig', 5, DEFAULT, DEFAULT),
    (23, 'https://avatars.mds.yandex.net/get-mpic/1602935/img_id4355780092450000253.jpeg/orig', 5, DEFAULT, DEFAULT),
    (24, 'https://avatars.mds.yandex.net/get-mpic/1680954/img_id5104251515953080652.jpeg/orig', 5, DEFAULT, DEFAULT),
    (25, 'https://avatars.mds.yandex.net/get-mpic/1526692/img_id3234332064997764140.jpeg/orig', 5, DEFAULT, DEFAULT),
    (26, 'https://avatars.mds.yandex.net/get-mpic/1571888/img_id7627887873820581882.jpeg/orig', 5, DEFAULT, DEFAULT),
    (27, 'https://avatars.mds.yandex.net/get-marketpic/1889466/market_p_s7ewEbKuGoz0glAy2mfQ/orig', 6, DEFAULT, DEFAULT),
    (28, 'https://avatars.mds.yandex.net/get-mpic/1673800/img_id4964892947089529380.jpeg/orig', 7, DEFAULT, DEFAULT),
    (29, 'https://avatars.mds.yandex.net/get-mpic/1928572/img_id5596996292477658643.jpeg/orig', 8, DEFAULT, DEFAULT),
    (30, 'https://avatars.mds.yandex.net/get-mpic/1600461/img_id8279945597083630939.png/orig', 8, DEFAULT, DEFAULT),
    (31, 'https://avatars.mds.yandex.net/get-mpic/1860966/img_id4902835705429036766.png/orig', 8, DEFAULT, DEFAULT),
    (32, 'https://avatars.mds.yandex.net/get-mpic/1568604/img_id2093558588649100865.png/orig', 8, DEFAULT, DEFAULT),
    (33, 'https://avatars.mds.yandex.net/get-mpic/1912105/img_id8797165219946347638.jpeg/orig', 9, DEFAULT, DEFAULT),
    (34, 'https://avatars.mds.yandex.net/get-mpic/1592349/img_id3549506975302442589.jpeg/orig', 9, DEFAULT, DEFAULT),
    (35, 'https://avatars.mds.yandex.net/get-mpic/1886039/img_id4204168739547981246.jpeg/orig', 9, DEFAULT, DEFAULT),
    (36, 'https://avatars.mds.yandex.net/get-mpic/1591646/img_id1779011427587443357.jpeg/orig', 9, DEFAULT, DEFAULT),
    (37, 'https://avatars.mds.yandex.net/get-mpic/1680954/img_id2867390851530924787.jpeg/orig', 9, DEFAULT, DEFAULT),
    (38, 'https://avatars.mds.yandex.net/get-mpic/1912105/img_id5020027682169209072.jpeg/orig', 9, DEFAULT, DEFAULT),
    (39, 'https://avatars.mds.yandex.net/get-mpic/1574389/img_id7221206224327369720.jpeg/orig', 10, DEFAULT, DEFAULT),
    (40, 'https://avatars.mds.yandex.net/get-mpic/2014136/img_id8113023170412147877.jpeg/orig', 10, DEFAULT, DEFAULT),
    (41, 'https://avatars.mds.yandex.net/get-mpic/1644362/img_id1237417284337445177.jpeg/orig', 10, DEFAULT, DEFAULT),
    (42, 'https://avatars.mds.yandex.net/get-mpic/1574389/img_id8669033687526147880.jpeg/orig', 10, DEFAULT, DEFAULT),
    (43, 'https://avatars.mds.yandex.net/get-mpic/1862701/img_id1146250919835510757.jpeg/orig', 10, DEFAULT, DEFAULT),
    (44, 'https://avatars.mds.yandex.net/get-mpic/1603927/img_id1280382501587481916.jpeg/orig', 10, DEFAULT, DEFAULT),
    (45, 'https://avatars.mds.yandex.net/get-mpic/1909520/img_id5122175376736201781.jpeg/orig', 10, DEFAULT, DEFAULT),
    (46, 'https://avatars.mds.yandex.net/get-mpic/1943683/img_id8578529576381264134.jpeg/orig', 10, DEFAULT, DEFAULT),
    (47, 'https://avatars.mds.yandex.net/get-mpic/1909520/img_id5259673942808462098.jpeg/orig', 10, DEFAULT, DEFAULT),
    (48, 'https://avatars.mds.yandex.net/get-mpic/1750349/img_id1831212060581582267.jpeg/orig', 10, DEFAULT, DEFAULT),
    (49, 'https://avatars.mds.yandex.net/get-mpic/1574389/img_id3968606725648835038.jpeg/orig', 10, DEFAULT, DEFAULT),
    (50, 'https://avatars.mds.yandex.net/get-mpic/1767151/img_id376121241719262991.jpeg/orig', 11, DEFAULT, DEFAULT),
    (51, 'https://avatars.mds.yandex.net/get-mpic/1865723/img_id1174991054009578490.jpeg/orig', 11, DEFAULT, DEFAULT),
    (52, 'https://avatars.mds.yandex.net/get-mpic/1865652/img_id8765768265701742687.jpeg/orig', 11, DEFAULT, DEFAULT),
    (53, 'https://avatars.mds.yandex.net/get-mpic/1886039/img_id3011222253414400848.jpeg/orig', 11, DEFAULT, DEFAULT),
    (54, 'https://avatars.mds.yandex.net/get-mpic/1865543/img_id7165577844189977000.jpeg/orig', 11, DEFAULT, DEFAULT),
    (55, 'https://avatars.mds.yandex.net/get-mpic/1597983/img_id6267913866842491771.jpeg/orig', 11, DEFAULT, DEFAULT);

INSERT INTO goods VALUES
    (1, 'Apple iPhone Xr 128gb', 'Смартфон Apple iPhone Xr 128GB',
        'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium',
        1, 50360, DEFAULT, DEFAULT),
    (2, 'Apple iPhone 11 128gb', 'Смартфон Apple iPhone 11 128GB',
        'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium',
        5, 54900, DEFAULT, DEFAULT),
    (3, 'Apple iPphone 11 Pro Max 256gb', 'Смартфон Apple iPhone 11 Pro Max 256GB',
        'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium',
        9, 93800, DEFAULT, DEFAULT),
    (4, 'Apple iPhone 12 128GB', 'Смартфон Apple iPhone 12 128GB',
        'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium',
        13, 84990, DEFAULT, DEFAULT),
    (5, 'Huawei P30 Pro 8/256gb', 'Смартфон HUAWEI P30 Pro 8/256GB',
        'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium',
        17, 49990, DEFAULT, DEFAULT),
    (6, 'HUAWEI P40 Pro 8/256gb', 'Смартфон HUAWEI P40 Pro 8 ГБ + 256 ГБ Насыщенный синий',
        'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium',
        27, 64990, DEFAULT, DEFAULT),
    (7, 'OnePlus 7T Pro 8/256gb', 'Смартфон OnePlus 7T Pro 8/256GB',
        'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium',
        28, 48950, DEFAULT, DEFAULT),
    (8, 'OnePlus 8 8/128gb', 'Смартфон OnePlus 8 8/128GB',
        'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium',
        29, 41480, DEFAULT, DEFAULT),
    (9, 'Samsung Galaxy A71 6/128gb', 'Смартфон Samsung Galaxy A71 6/128GB',
        'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium',
        33, 22700, DEFAULT, DEFAULT),
    (10, 'Samsung Galaxy Note 10+ 12/256gb', 'Смартфон Samsung Galaxy Note 10+ 12/256GB',
        'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium',
        39, 63490, DEFAULT, DEFAULT),
    (11, 'Samsung Galaxy S20 Ultra 5G 12/256gb', 'Смартфон Samsung Galaxy S20 Ultra 5G 12/256GB',
        'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium',
        50, 80990, DEFAULT, DEFAULT);

INSERT INTO feedbacks VALUES
    (1, 1, 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.', DEFAULT, DEFAULT),
    (1, 2, 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.', DEFAULT, DEFAULT),
    (1, 3, 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.', DEFAULT, DEFAULT),
    (1, 4, 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.', DEFAULT, DEFAULT),
    (1, 5, 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.', DEFAULT, DEFAULT),
    (1, 6, 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.', DEFAULT, DEFAULT),
    (1, 7, 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.', DEFAULT, DEFAULT),
    (1, 8, 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.', DEFAULT, DEFAULT),
    (2, 1, 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.', DEFAULT, DEFAULT),
    (2, 2, 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.', DEFAULT, DEFAULT),
    (2, 3, 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.', DEFAULT, DEFAULT),
    (2, 4, 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.', DEFAULT, DEFAULT),
    (2, 5, 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.', DEFAULT, DEFAULT),
    (3, 4, 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.', DEFAULT, DEFAULT),
    (3, 5, 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.', DEFAULT, DEFAULT),
    (3, 6, 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.', DEFAULT, DEFAULT),
    (3, 7, 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.', DEFAULT, DEFAULT),
    (3, 8, 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.', DEFAULT, DEFAULT),
    (3, 9, 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.', DEFAULT, DEFAULT);

INSERT INTO orders VALUES
    (1, 1, 'Ждите звонка от оператора', DEFAULT, DEFAULT),
    (2, 1, 'Ждите звонка от оператора', DEFAULT, DEFAULT),
    (3, 1, 'Ждите звонка от оператора', DEFAULT, DEFAULT),
    (4, 1, 'Ждите звонка от оператора', DEFAULT, DEFAULT),
    (5, 1, 'Ждите звонка от оператора', DEFAULT, DEFAULT),
    (6, 1, 'Ждите звонка от оператора', DEFAULT, DEFAULT),
    (7, 1, 'Ждите звонка от оператора', DEFAULT, DEFAULT),
    (8, 2, 'Отменен', DEFAULT, DEFAULT),
    (9, 2, 'Отменен', DEFAULT, DEFAULT),
    (10, 2, 'Отменен', DEFAULT, DEFAULT),
    (11, 2, 'Отменен', DEFAULT, DEFAULT),
    (12, 2, 'Отменен', DEFAULT, DEFAULT),
    (13, 3, 'Отменен', DEFAULT, DEFAULT),
    (14, 3, 'Отменен', DEFAULT, DEFAULT),
    (15, 3, 'Отменен', DEFAULT, DEFAULT),
    (16, 3, 'Отменен', DEFAULT, DEFAULT),
    (17, 3, 'Отменен', DEFAULT, DEFAULT),
    (18, 3, 'Отменен', DEFAULT, DEFAULT),
    (19, 3, 'Отменен', DEFAULT, DEFAULT),
    (20, 3, 'Отменен', DEFAULT, DEFAULT);

INSERT INTO `order_product` VALUES
    (1, 1, '49890.00', 1),
    (1, 4, '47779.00', 2),
    (2, 7, '108980.00', 3),
    (2, 10, '49890.00', 4),
    (2, 2, '52990.00', 5),
    (3, 5, '47779.00', 1),
    (4, 8, '47779.00', 2),
    (4, 11, '47779.00', 3),
    (4, 3, '47779.00', 4),
    (5, 6, '47779.00', 5),
    (6, 9, '47779.00', 1),
    (7, 1, '47779.00', 2),
    (7, 4, '47779.00', 3),
    (8, 7, '47779.00', 4),
    (8, 10, '47779.00', 5),
    (8, 2, '47779.00', 1),
    (9, 5, '47779.00', 2),
    (10, 8, '47779.00', 3),
    (11, 11, '47779.00', 4),
    (11, 3, '47779.00', 5),
    (12, 6, '47779.00', 1),
    (13, 9, '47779.00', 2),
    (14, 7, '47779.00', 3),
    (14, 10, '47779.00', 4),
    (15, 2, '47779.00', 5),
    (15, 5, '47779.00', 1),
    (16, 8, '47779.00', 2),
    (17, 11, '47779.00', 3),
    (18, 3, '47779.00', 4),
    (19, 6, '47779.00', 5),
    (20, 9, '43650.00', 1);

--
-- Foreign keys
--

ALTER TABLE images
    ADD CONSTRAINT images_goods_fk FOREIGN KEY (id_product) REFERENCES goods (id);

ALTER TABLE feedbacks
    ADD CONSTRAINT feedbacks_users_fk FOREIGN KEY (id_user) REFERENCES users (id),
    ADD CONSTRAINT feedbacks_goods_fk FOREIGN KEY (id_product) REFERENCES goods (id);

ALTER TABLE orders
    ADD CONSTRAINT orders_users_id FOREIGN KEY (id_user) REFERENCES users (id);

ALTER TABLE order_product
    ADD CONSTRAINT order_product_orders_fk FOREIGN KEY (id_order) REFERENCES orders (id),
    ADD CONSTRAINT order_product_goods_fk FOREIGN KEY (id_product) REFERENCES goods (id);


COMMIT;
