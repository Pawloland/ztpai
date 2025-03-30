-- enable PL/pgSQL Debugger API
DROP SCHEMA IF EXISTS debugger CASCADE;
CREATE SCHEMA IF NOT EXISTS debugger;
CREATE EXTENSION IF NOT EXISTS pldbgapi SCHEMA debugger;


-- ----------------------------
-- Table structure for Client
-- ----------------------------
DROP TABLE IF EXISTS "Client" CASCADE;
CREATE TABLE "Client" (
    "ID_Client" SERIAL PRIMARY KEY,
    "client_name" VARCHAR(40) NOT NULL,
    "client_surname" VARCHAR(40) NOT NULL,
    "nick" VARCHAR(40) NOT NULL,
    "password_hash" VARCHAR(80) NOT NULL,
    "mail" VARCHAR(320) NOT NULL,
    UNIQUE ("nick"),
    UNIQUE ("mail")
);
ALTER SEQUENCE "Client_ID_Client_seq" RESTART WITH 31;

-- ----------------------------
-- Records of Client
-- ----------------------------
-- INSERT INTO "Client" VALUES (1, 'Justin', 'Hughes', 'mHdeTYpEdL', 'vQwROvc1NDIelsIMlNyDklEaJwmN0L8fEjNotCFU', 'justin2@icloud.com');
-- INSERT INTO "Client" VALUES (2, 'Carolyn', 'Garcia', 'E1WxBczUke', 'yTKx2dHX85acSD4yqfdZop93nHNI5ck4R9sE2IWF', 'cargarcia@gmail.com');
-- INSERT INTO "Client" VALUES (3, 'Carolyn', 'Young', 'gReErlxkzs', '1DmqkL7FuxGmePhPHyFw6aafmoHbblnogCxd6hSD', 'youngca@yahoo.com');
-- INSERT INTO "Client" VALUES (4, 'Jennifer', 'Mills', 'iTpZ7edPVl', 'oDk0zD8OcIauyrLyek3Gi0mtQHsiFJQgpiWP5THC', 'jennmil@mail.com');
-- INSERT INTO "Client" VALUES (5, 'Bruce', 'Meyer', 'M7agXT4lHw', 'M2HxtyxjmfWP8czNwQsrnIRjh08lWdtCwvT9hYN0', 'brucemeyer512@outlook.com');
-- INSERT INTO "Client" VALUES (6, 'Tina', 'Holmes', 'mZN54KbsU5', 'sjKEWNca0WpaGhJ14cOzEgodU0ENz1643GnJeyDh', 'tinahol@hotmail.com');
-- INSERT INTO "Client" VALUES (7, 'Kathy', 'Cook', 'tfy9zJ8jfk', 'xGRFUXcxg2WFUdUs5Bi1mEHZ2iEnW1bgeAFMSsaR', 'kathyco722@outlook.com');
-- INSERT INTO "Client" VALUES (8, 'Betty', 'Miller', 'Ffo8fAJgMq', 'KUnmYXzinnZqKXI2qLxWOws5t5DGgqmmAnvMl6b6', 'bmille1103@icloud.com');
-- INSERT INTO "Client" VALUES (9, 'Virginia', 'Stewart', 'EZq37zh4x1', 'qPz2iXObuNHiU6ZC4MtKO8rtIsn6Z8ukFGLixHAH', 'stewart2@yahoo.com');
-- INSERT INTO "Client" VALUES (10, 'Jennifer', 'Roberts', 'qrCbeYY8NQ', 'xGgOUcW7eWT6hRt3lPFgDQ9pk5Szb0ipXxqrlBrI', 'jennirobe324@hotmail.com');
-- INSERT INTO "Client" VALUES (11, 'Donald', 'Nguyen', 'vdkFbSbDIU', 'tb6rSbkZlsoKDoJ0eqQLLphNp33NxHu980odWYB9', 'donaldnguye@outlook.com');
-- INSERT INTO "Client" VALUES (12, 'Frank', 'Ward', 'Vz75iK3SY4', 'BWcvyGMxjACrVYEX9PaXhGw0kn7WZdCdibUPOQvT', 'wardfrank@icloud.com');
-- INSERT INTO "Client" VALUES (13, 'Lillian', 'Bennett', 'CXX1eRiDnh', '8LilvH0Dd1fm4RStBZJeotTrnnVz6pFwuZ3sucO6', 'bennel@gmail.com');
-- INSERT INTO "Client" VALUES (14, 'Hazel', 'Stephens', 'NKQ6IwRNx1', 'tql1myB1mjqcz6Uk5qDMiHK7bXDavkaVA6Znasn0', 'stephenshaz@hotmail.com');
-- INSERT INTO "Client" VALUES (15, 'Pamela', 'Munoz', 'Uq4LhRUL6S', 'SLz2kJyRX0qidfGBFDQ7IMzAq6IY16W17KwJWcIa', 'munozpamela@gmail.com');
-- INSERT INTO "Client" VALUES (16, 'Eddie', 'Howard', 'GBPGTyKq4T', 'dBbBRXajOVvVXZdux4YdH5jHE5QxW3OB9tW6PEMY', 'eho@mail.com');
-- INSERT INTO "Client" VALUES (17, 'Florence', 'Reyes', 'dtcKoU6xa4', '6KErL1OX16t8WAzJV2KpvUEqeESJymAppJMwiuV3', 'fr6@outlook.com');
-- INSERT INTO "Client" VALUES (18, 'Ralph', 'Kelley', '047IgbGq99', 'RpsPMILf5E3xsZ4TZiTsCjJaw6qZkoC2DEwMZTDz', 'rkel@outlook.com');
-- INSERT INTO "Client" VALUES (19, 'Ashley', 'Ward', 'DQbhAnZx4O', '1JPCX48phxUYcOmrZ8npu4HwZoujmlC2ZF9Gg8R1', 'wardas1@gmail.com');
-- INSERT INTO "Client" VALUES (20, 'Ashley', 'Gonzales', 'k6hW6pLajb', '2Kx2Q1SVkpNjFMsBz2mQlawGvF0yDDSbkPaKZorb', 'ashley10@yahoo.com');
-- INSERT INTO "Client" VALUES (21, 'Vincent', 'Hunt', 'Zck803iuqk', 'jWUlZKtFsDYkELzBxNj5nikvQJvkxYIytYqJi8JU', 'vincenthunt@outlook.com');
-- INSERT INTO "Client" VALUES (22, 'Howard', 'West', 'rLgCVpxYNi', 'xUwc6k1rrlln9xsZkGduoDDFoTVCTOOgbcd9RlSG', 'howarwest@icloud.com');
-- INSERT INTO "Client" VALUES (23, 'Nathan', 'Reed', '5WtarwGSRn', '062fOPOPUOgnYf2hQBMaaasTyV2qtDOSPPKjSKT5', 'reed4@gmail.com');
-- INSERT INTO "Client" VALUES (24, 'Jeremy', 'Gonzales', '0vDqDSYodL', 'maKdB43xLbV6yqWmnjM8uK2gHNEDIjyTPyQCeDFg', 'jeremgonz@gmail.com');
-- INSERT INTO "Client" VALUES (25, 'Marilyn', 'Perez', 'OhXhw3tdPD', 'RatsfY2IVdxXCJDycjptl0qT2doeWdeHKINZtfKp', 'perez408@outlook.com');
-- INSERT INTO "Client" VALUES (26, 'Ricky', 'West', 'PMkvM7ndGL', 'M5p7Kfp98HMpFNPoJ7iaZTFPyueTBy135bDZOyXt', 'westricky2@mail.com');
-- INSERT INTO "Client" VALUES (27, 'Martha', 'Mendez', 'sUYoX056BF', 'Ioh4EqbneGxxiwvTYVLCpoQx7cluvtWACnvUSNhh', 'mmendez57@yahoo.com');
-- INSERT INTO "Client" VALUES (28, 'Emily', 'Fernandez', 'eNKvo5kGN5', 'q3URKaFiSqaicCkjlXe43t3A2Muqxtevzj9IyTIF', 'emily1@icloud.com');
-- INSERT INTO "Client" VALUES (29, 'Daniel', 'Williams', '30uj5OLatA', '4rnUdaXXz663Ts8uzKhm6poxvCkANb1ARXWILZpT', 'wdaniel@outlook.com');
-- INSERT INTO "Client" VALUES (30, 'Christopher', 'Nichols', 'mxgaFuv4yD', 'QDIvkkXT23udcA4JxB33CLM9vMHA2kuP2AttuGmC', 'christophernichols@outlook.com');

-- ----------------------------
-- Table structure for Client_archive
-- ----------------------------
DROP TABLE IF EXISTS "Client_archive" CASCADE;
CREATE TABLE "Client_archive" (
    "ID_Client" INT,
    "client_name" VARCHAR(40),
    "client_surname" VARCHAR(40),
    "nick" VARCHAR(40) NOT NULL,
    "password_hash" VARCHAR(80) NOT NULL,
    "mail" VARCHAR(120) NOT NULL,
    "operation_type" CHAR(1) CHECK ("operation_type" IN ('d', 'u')),
    "date" TIMESTAMP WITH TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY ("ID_Client", "date")
) PARTITION BY RANGE ("date");  -- Define partitioning by the "date" column.
-- Create partitions for different ranges of the "date" column.
CREATE TABLE "Client_archive_part_2022" PARTITION OF "Client_archive"
    FOR VALUES FROM ('2000-01-01') TO ('2022-01-01');
CREATE TABLE "Client_archive_part_2023" PARTITION OF "Client_archive"
    FOR VALUES FROM ('2022-01-01') TO ('2023-01-01');
CREATE TABLE "Client_archive_part_2024" PARTITION OF "Client_archive"
    FOR VALUES FROM ('2023-01-01') TO ('2024-01-01');
CREATE TABLE "Client_archive_part_default" PARTITION OF "Client_archive"
    FOR VALUES FROM ('2024-01-01') TO ('INFINITY');



-- ----------------------------
-- Records of Client_archive
-- ----------------------------
INSERT INTO "Client_archive" VALUES (1, 'pm', 'fdm', 'ffee', 'mibneboeboenbo', 'mm@gmail.com', 'u', '2024-06-19 14:35:18');
INSERT INTO "Client_archive" VALUES (1, 'pm', 'fdm', 'ffee3', 'mibneboeboenbo', 'mm@gmail.com', 'd', '2024-06-19 14:35:33');

-- ----------------------------
-- Table structure for Discount
-- ----------------------------
DROP TABLE IF EXISTS "Discount" CASCADE;
CREATE TABLE "Discount" (
    "ID_Discount" SERIAL PRIMARY KEY,
    "discount_name" VARCHAR(20) NOT NULL,
    "amount" NUMERIC(10, 2) NOT NULL CHECK ("amount" >= 0),
    UNIQUE ("discount_name")
);
ALTER SEQUENCE "Discount_ID_Discount_seq" RESTART WITH 6;

-- ----------------------------
-- Records of Discount
-- ----------------------------
INSERT INTO "Discount" VALUES (1, 'student', 4.00);
INSERT INTO "Discount" VALUES (2, 'senior', 4.00);
INSERT INTO "Discount" VALUES (3, 'honorary', 6.00);
INSERT INTO "Discount" VALUES (4, 'blood_donor', 3.00);
INSERT INTO "Discount" VALUES (5, 'veteran', 3.00);



-- ----------------------------
-- Table structure for Genre
-- ----------------------------
DROP TABLE IF EXISTS "Genre" CASCADE;
CREATE TABLE "Genre" (
    "ID_Genre" SERIAL PRIMARY KEY,
    "genre_name" VARCHAR(40) NOT NULL,
    UNIQUE ("genre_name")
);
ALTER SEQUENCE "Genre_ID_Genre_seq" RESTART WITH 10;

-- ----------------------------
-- Records of Genre
-- ----------------------------
INSERT INTO "Genre" VALUES (4, 'action');
INSERT INTO "Genre" VALUES (7, 'animated');
INSERT INTO "Genre" VALUES (9, 'comedy');
INSERT INTO "Genre" VALUES (1, 'documentary');
INSERT INTO "Genre" VALUES (8, 'family');
INSERT INTO "Genre" VALUES (6, 'fantasy');
INSERT INTO "Genre" VALUES (2, 'romance');
INSERT INTO "Genre" VALUES (5, 'sci-fi');
INSERT INTO "Genre" VALUES (3, 'thriller');

-- ----------------------------
-- Table structure for Hall
-- ----------------------------
DROP TABLE IF EXISTS "Hall" CASCADE;
CREATE TABLE "Hall" (
    "ID_Hall" SERIAL PRIMARY KEY,
    "hall_name" VARCHAR(40) DEFAULT NULL,
    UNIQUE ("hall_name")
);
ALTER SEQUENCE "Hall_ID_Hall_seq" RESTART WITH 7;

-- ----------------------------
-- Records of Hall
-- ----------------------------
INSERT INTO "Hall" VALUES (1, '1');
INSERT INTO "Hall" VALUES (2, '2');
-- INSERT INTO "Hall" VALUES (3, '3');
-- INSERT INTO "Hall" VALUES (4, '4');
-- INSERT INTO "Hall" VALUES (5, '5');
-- INSERT INTO "Hall" VALUES (6, '6');

-- ----------------------------
-- Table structure for Item
-- ----------------------------
DROP TABLE IF EXISTS "Item" CASCADE;
CREATE TABLE "Item" (
    "ID_item" SERIAL PRIMARY KEY,
    "item_name" VARCHAR(60) NOT NULL,
    "item_price" NUMERIC(10, 2) NOT NULL,
    "vat_percentage" NUMERIC(4, 2) NOT NULL,
    UNIQUE ("item_name")
);
ALTER SEQUENCE "Item_ID_item_seq" RESTART WITH 32;

-- ----------------------------
-- Records of Item
-- ----------------------------
INSERT INTO "Item" VALUES (1, 'ultra-Cheryy', 14.42, 19.42);
INSERT INTO "Item" VALUES (2, 'Raspberry', 3.74, 24.88);
INSERT INTO "Item" VALUES (3, 'wluots', 7.21, 22.98);
INSERT INTO "Item" VALUES (4, 'ambi-Kiwi', 22.90, 9.77);
INSERT INTO "Item" VALUES (5, 'ultra-Stxawberry', 18.58, 12.26);
INSERT INTO "Item" VALUES (6, 'tambutan', 10.07, 20.98);
INSERT INTO "Item" VALUES (7, 'ultra-Strawbprry', 4.77, 19.73);
INSERT INTO "Item" VALUES (8, 'dherry', 11.39, 30.25);
INSERT INTO "Item" VALUES (9, 'Grape', 4.26, 11.09);
INSERT INTO "Item" VALUES (10, 'xCherry', 14.55, 13.77);

-- ----------------------------
-- Table structure for Language
-- ----------------------------
DROP TABLE IF EXISTS "Language" CASCADE;
CREATE TABLE "Language" (
    "ID_Language" SERIAL PRIMARY KEY,
    "language_name" VARCHAR(40) NOT NULL,
    "code" VARCHAR(5) NOT NULL,
    UNIQUE ("language_name"),
    UNIQUE ("code")
);
ALTER SEQUENCE "Language_ID_Language_seq" RESTART WITH 12;

-- ----------------------------
-- Records of Language
-- ----------------------------
INSERT INTO "Language" VALUES (1, 'english', 'en');
INSERT INTO "Language" VALUES (2, 'polish', 'pl');
INSERT INTO "Language" VALUES (3, 'czech', 'cz');
INSERT INTO "Language" VALUES (4, 'spanish', 'sp');
INSERT INTO "Language" VALUES (5, 'portuguese', 'pt');
INSERT INTO "Language" VALUES (6, 'french', 'fr');
INSERT INTO "Language" VALUES (7, 'chinese', 'cn');
INSERT INTO "Language" VALUES (8, 'german', 'de');
INSERT INTO "Language" VALUES (9, 'japanese', 'jp');
INSERT INTO "Language" VALUES (10, 'ukrainian', 'uk');

-- ----------------------------
-- Table structure for Movie
-- ----------------------------
DROP TABLE IF EXISTS "Movie" CASCADE;
CREATE TABLE "Movie" (
    "ID_Movie" SERIAL PRIMARY KEY,
    "title" VARCHAR(80) NOT NULL,
    "original_title" VARCHAR(80) NOT NULL,
    "duration" TIME NOT NULL,
    "description" VARCHAR(500) DEFAULT NULL,
    poster uuid DEFAULT gen_random_uuid(),
    "ID_Language" INT NOT NULL,
    "ID_Dubbing" INT DEFAULT NULL,
    "ID_Subtitles" INT DEFAULT NULL,
    FOREIGN KEY ("ID_Language") REFERENCES "Language" ("ID_Language") ON DELETE RESTRICT ON UPDATE RESTRICT,
    FOREIGN KEY ("ID_Dubbing") REFERENCES "Language" ("ID_Language") ON DELETE RESTRICT ON UPDATE RESTRICT,
    FOREIGN KEY ("ID_Subtitles") REFERENCES "Language" ("ID_Language") ON DELETE RESTRICT ON UPDATE RESTRICT
);
-- Indexes
CREATE INDEX "fk_movie_language" ON "Movie" ("ID_Language");
CREATE INDEX "fk_movie_dubbing" ON "Movie" ("ID_Dubbing");
CREATE INDEX "fk_movie_subtitles" ON "Movie" ("ID_Subtitles");
-- Full-text search index for "description" column
-- CREATE INDEX "desc_gin" ON "Movie" USING GIN (to_tsvector('english', "description"));
ALTER SEQUENCE "Movie_ID_Movie_seq" RESTART WITH 67;

-- ----------------------------
-- Records of Movie
-- ----------------------------
-- INSERT INTO "Movie" VALUES (1, 'To connect to a database                ', 'The On Startup feature                  ', '00:09:13', 'The On Startup feature                  ','0cbe328d-91b2-4846-8096-deae13600792', 9, 2, 2);
-- INSERT INTO "Movie" VALUES (2, 'Sometimes you win, sometimes you learn.', 'In a Telnet session,                    ', '00:09:59', 'Sometimes you win, sometimes you learn.', '47ffc826-3168-4be9-ae86-15a7f7267b52',8, 8, 8);
-- INSERT INTO "Movie" VALUES (3, 'If it scares you, it                    ', 'The past has no power                   ', '01:52:25', 'The past has no power                   ', '895d5b81-8108-4eec-abfd-bd6094794aad',6, 9, 9);
-- INSERT INTO "Movie" VALUES (4, 'Champions keep playing                  ', 'Import Wizard allows                    ', '01:12:03', 'Import Wizard allows                    Import Wizard allows                    ','51641d93-f38d-4ae6-907f-1f25dd0987bd', 1, 1, 1);
-- INSERT INTO "Movie" VALUES (5, 'To connect to a database                ', 'All the Navicat Cloud                   ', '00:58:59', 'All the Navicat Cloud                   All the Navicat Cloud                   All the Navicat Cloud                   ', 'c264e624-8552-425d-9e5a-212c9cc23faa',7, 8, 8);
-- INSERT INTO "Movie" VALUES (6, 'It wasn’t raining when                ', 'If opportunity doesn’t                ', '00:24:32', 'If opportunity doesn’t                ', '71af3233-9ceb-4c31-a833-4401b8eb79b7',5, 3, 3);
-- INSERT INTO "Movie" VALUES (7, 'Secure SHell (SSH) is                   ', 'Success consists of going               ', '00:19:26', 'If opportunity doesn’t                If opportunity doesn’t                If opportunity doesn’t                ', '884dd11c-5548-4ead-aa5e-72ffc589fde3',10, 10, 10);
INSERT INTO "Movie" VALUES (49, 'Conclave', 'Conclave', '01:01:01', 'Conclave', 'ce0de9fa-5770-4af2-9699-d6b6758a97e9', 2, 2, 2);
INSERT INTO "Movie" VALUES (50, 'Deadpool & Wolverine', 'Deadpool & Wolverine', '01:00:00', 'Deadpool & Wolverine', '0b5fcd98-811a-4d22-a713-cbfc9084f24c', 2, 2, 2);
INSERT INTO "Movie" VALUES (51, 'Oppeneimer', 'Oppeneimer', '01:00:00', 'Oppeneimer', '7c051ef4-e5bd-4976-93e5-0f20919b969e', 2, 2, 2);
INSERT INTO "Movie" VALUES (52, 'Joker: Folie à Deux', 'Joker: Folie à Deux', '01:00:00', 'Joker: Folie à Deux', '08f05470-6e27-41a9-a583-1c71f13a8f4b', 2, 2, 2);
INSERT INTO "Movie" VALUES (53, 'The Substance', 'The Substance', '01:00:00', 'The Substance', 'f46a22f3-3ab9-40a5-ad14-0b56414287ec', 2, 2, 2);
INSERT INTO "Movie" VALUES (54, 'A Real Pain', 'A Real Pain', '01:00:00', 'A Real Pain', 'dcbcd370-7c68-4124-9527-dd1ccdd59971', 2, 2, 2);
INSERT INTO "Movie" VALUES (55, 'Red One', 'Red One', '01:00:00', 'Red One', 'fdac9010-3c59-4d3b-b10d-8602e118e7e4', 2, 2, 2);
INSERT INTO "Movie" VALUES (56, 'Furiosa', 'Furiosa', '01:00:00', 'Furiosa', 'ad9543d3-b2a0-4d0e-a900-b1c2d85a386f', 2, 2, 2);
INSERT INTO "Movie" VALUES (57, 'The Wild Robot', 'The Wild Robot', '01:00:00', 'The Wild Robot', '53b63349-1842-4195-a35d-a669f021ab9e', 2, 2, 2);
INSERT INTO "Movie" VALUES (58, 'Venom 3: The Last Dance', 'Venom 3: The Last Dance', '01:00:00', 'Venom 3: The Last Dance', '80306a4b-77ff-45f8-985f-259dda900ee0', 2, 2, 2);
INSERT INTO "Movie" VALUES (59, 'Alien Romulus', 'Alien Romulus', '01:00:00', 'Alien Romulus', '5898364d-2f16-4cd5-a1ef-e80fe3a802af', 2, 2, 2);
INSERT INTO "Movie" VALUES (60, 'Despicable Me 4', 'Despicable Me 4', '01:00:00', 'Despicable Me 4', '1792c7cd-495b-41b9-9ac5-4ce8456be8f6', 2, 2, 2);
INSERT INTO "Movie" VALUES (61, 'Moana 2', 'Moana 2', '01:00:00', 'Moana 2', '85aa42fc-bc5f-44bf-a229-5410382c9401', 2, 2, 2);
INSERT INTO "Movie" VALUES (62, 'Dune part 2', 'Dune part 2', '01:00:00', 'Dune part 2', 'e2c7c18a-f338-4757-bc60-3a82f32aabb7', 2, 2, 2);
INSERT INTO "Movie" VALUES (63, 'A Quiet Place: Day One', 'A Quiet Place: Day One', '01:00:00', 'A Quiet Place: Day One', '2e62083f-918a-4163-853d-d588bcf4cb2c', 2, 2, 2);
INSERT INTO "Movie" VALUES (64, 'Inside Out 2', 'Inside Out 2', '01:00:00', 'Inside Out 2', 'ac9ea9a8-6f3c-407a-90ee-e5bbbc52fbcf', 2, 2, 2);
INSERT INTO "Movie" VALUES (65, 'The Ministry of Ungentlemanly Warfare', 'The Ministry of Ungentlemanly Warfare', '01:00:00', 'The Ministry of Ungentlemanly Warfare', '264fc6fd-3956-446e-ac18-e1a0cc1a482d', 2, 2, 2);
INSERT INTO "Movie" VALUES (66, 'The Fall Guy', 'The Fall Guy', '01:00:00', 'The Fall Guy', '922f2646-142a-4404-9b57-eeb64c0b775f', 2, 2, 2);



-- ----------------------------
-- Table structure for Movie_Genre
-- ----------------------------
DROP TABLE IF EXISTS "Movie_Genre" CASCADE;
CREATE TABLE "Movie_Genre" (
    "ID_Movie" INT NOT NULL,
    "ID_Genre" INT NOT NULL,
    PRIMARY KEY ("ID_Movie", "ID_Genre"),
    FOREIGN KEY ("ID_Movie") REFERENCES "Movie" ("ID_Movie") ON DELETE RESTRICT ON UPDATE RESTRICT,
    FOREIGN KEY ("ID_Genre") REFERENCES "Genre" ("ID_Genre") ON DELETE RESTRICT ON UPDATE RESTRICT
);
-- Index on "ID_Genre"
CREATE INDEX "fk_movie_genre_genre" ON "Movie_Genre" ("ID_Genre");


-- ----------------------------
-- Records of Movie_Genre
-- ----------------------------
-- INSERT INTO "Movie_Genre" VALUES (6, 1);
-- INSERT INTO "Movie_Genre" VALUES (7, 1);
-- INSERT INTO "Movie_Genre" VALUES (1, 2);
-- INSERT INTO "Movie_Genre" VALUES (2, 4);
-- INSERT INTO "Movie_Genre" VALUES (3, 4);
-- INSERT INTO "Movie_Genre" VALUES (7, 4);
-- INSERT INTO "Movie_Genre" VALUES (3, 5);
-- INSERT INTO "Movie_Genre" VALUES (5, 8);
-- INSERT INTO "Movie_Genre" VALUES (4, 9);

-- ----------------------------
-- Table structure for Permissions
-- ----------------------------
DROP TABLE IF EXISTS "Permissions" CASCADE;
CREATE TABLE "Permissions" (
    "ID_Perm" SERIAL PRIMARY KEY,
    "perm_name" VARCHAR(60) NOT NULL,
    UNIQUE ("perm_name")
);
ALTER SEQUENCE "Permissions_ID_Perm_seq" RESTART WITH 10;

-- ----------------------------
-- Records of Permissions
-- ----------------------------
-- INSERT INTO "Permissions" VALUES (1, 'create_reservation');
-- INSERT INTO "Permissions" VALUES (3, 'create_screening');
-- INSERT INTO "Permissions" VALUES (2, 'modify_reservation');
-- INSERT INTO "Permissions" VALUES (4, 'modify_screening');
INSERT INTO "Permissions" VALUES (5, 'removeMovie');
INSERT INTO "Permissions" VALUES (6, 'removeReservation');
INSERT INTO "Permissions" VALUES (7, 'removeScreening');
INSERT INTO "Permissions" VALUES (8, 'removeClient');
INSERT INTO "Permissions" VALUES (9, 'removeUser');


-- ----------------------------
-- Table structure for Purchase
-- ----------------------------
DROP TABLE IF EXISTS "Purchase" CASCADE;
CREATE TABLE "Purchase" (
    "ID_Purchase" SERIAL PRIMARY KEY,
    "ID_Item" INT NOT NULL,
    "ID_Client" INT DEFAULT NULL,
    "purchase_date" TIMESTAMP WITH TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP,
    "price_netto" NUMERIC(10, 2) NOT NULL,
    "price_brutto" NUMERIC(10, 2) NOT NULL,
    "ID_order" UUID NOT NULL,
    FOREIGN KEY ("ID_Client") REFERENCES "Client" ("ID_Client") ON DELETE RESTRICT ON UPDATE RESTRICT,
    FOREIGN KEY ("ID_Item") REFERENCES "Item" ("ID_item") ON DELETE RESTRICT ON UPDATE RESTRICT
);
-- Indexes
CREATE INDEX "id_item" ON "Purchase" ("ID_Item");
CREATE INDEX "id_clie" ON "Purchase" ("ID_Client");
ALTER SEQUENCE "Purchase_ID_Purchase_seq" RESTART WITH 4;

-- ----------------------------
-- Records of Purchase
-- ----------------------------
-- INSERT INTO "Purchase" VALUES (1, 1, 1, '2024-06-20 16:54:37', 14.42, 17.22, 'f771e4bb-2f14-11ef-aa29-005056b91d10');
-- INSERT INTO "Purchase" VALUES (2, 3, 1, '2024-06-20 16:55:48', 7.21, 8.87, 'f771e4bb-2f14-11ef-aa29-005056b91d10');
-- INSERT INTO "Purchase" VALUES (3, 6, 5, '2024-06-20 16:56:20', 18.58, 20.86, '39a7a2e8-2f15-11ef-aa29-005056b91d10');



-- ----------------------------
-- Table structure for Screening_Type
-- ----------------------------
DROP TABLE IF EXISTS "Screening_Type" CASCADE;
CREATE TABLE "Screening_Type" (
    "ID_Screening_Type" SERIAL PRIMARY KEY,
    "screening_name" VARCHAR(40) NOT NULL,
    "price" NUMERIC(10, 2) NOT NULL
);
-- Unique Index
CREATE UNIQUE INDEX "screening_name" ON "Screening_Type" ("screening_name");
ALTER SEQUENCE "Screening_Type_ID_Screening_Type_seq" RESTART WITH 5;

-- ----------------------------
-- Records of Screening_Type
-- ----------------------------
INSERT INTO "Screening_Type" VALUES (1, '2D', 13.00);
INSERT INTO "Screening_Type" VALUES (2, '3D', 17.00);
INSERT INTO "Screening_Type" VALUES (3, '4D', 30.00);
INSERT INTO "Screening_Type" VALUES (4, 'XD', 69.00);



-- ----------------------------
-- Table structure for Screening
-- ----------------------------
DROP TABLE IF EXISTS "Screening" CASCADE;
CREATE TABLE "Screening" (
    "ID_Screening" SERIAL PRIMARY KEY,
    "ID_Movie" INT NOT NULL,
    "ID_Hall" INT NOT NULL,
    "ID_Screening_Type" INT NOT NULL,
    "start_time" TIMESTAMP WITH TIME ZONE NULL,
    -- Foreign Keys
    FOREIGN KEY ("ID_Hall") REFERENCES "Hall" ("ID_Hall") ON DELETE RESTRICT ON UPDATE RESTRICT,
    FOREIGN KEY ("ID_Movie") REFERENCES "Movie" ("ID_Movie") ON DELETE RESTRICT ON UPDATE RESTRICT,
    FOREIGN KEY ("ID_Screening_Type") REFERENCES "Screening_Type" ("ID_Screening_Type") ON DELETE RESTRICT ON UPDATE RESTRICT
);
-- Indexes
CREATE INDEX "fk_screening_movie" ON "Screening" ("ID_Movie");
CREATE INDEX "fk_screening_hall" ON "Screening" ("ID_Hall");
CREATE INDEX "fk_screening_screening_type" ON "Screening" ("ID_Screening_Type");
ALTER SEQUENCE "Screening_ID_Screening_seq" RESTART WITH 1020;

-- ----------------------------
-- Records of Screening
-- ----------------------------
-- INSERT INTO "Screening" VALUES (1, 5, 3, 4, '2024-06-02 16:43:22');
-- INSERT INTO "Screening" VALUES (2, 4, 5, 4, '2024-03-30 14:49:52');
-- INSERT INTO "Screening" VALUES (3, 3, 3, 3, '2019-09-10 18:03:57');
-- INSERT INTO "Screening" VALUES (4, 3, 6, 2, '2020-07-23 21:39:53');
-- INSERT INTO "Screening" VALUES (5, 4, 6, 2, '2024-11-08 20:39:48');
-- INSERT INTO "Screening" VALUES (6, 7, 5, 2, '2022-02-08 19:26:38');
-- INSERT INTO "Screening" VALUES (7, 5, 2, 1, '2021-01-26 19:57:25');
-- INSERT INTO "Screening" VALUES (8, 4, 5, 1, '2023-04-20 09:42:54');
-- INSERT INTO "Screening" VALUES (9, 4, 2, 4, '2020-07-18 19:35:15');
-- INSERT INTO "Screening" VALUES (10, 6, 4, 4, '2023-12-19 16:01:15');
-- INSERT INTO "Screening" VALUES (11, 6, 4, 3, '2021-08-10 17:29:30');
-- INSERT INTO "Screening" VALUES (12, 2, 3, 3, '2022-09-30 21:36:58');
-- INSERT INTO "Screening" VALUES (13, 2, 3, 2, '2023-06-30 21:55:30');
-- INSERT INTO "Screening" VALUES (14, 4, 2, 3, '2021-05-31 13:59:03');
-- INSERT INTO "Screening" VALUES (15, 1, 1, 3, '2024-07-17 16:38:39');
-- INSERT INTO "Screening" VALUES (16, 7, 4, 2, '2020-08-28 19:18:50');
-- INSERT INTO "Screening" VALUES (17, 2, 3, 4, '2022-02-25 11:39:24');
-- INSERT INTO "Screening" VALUES (18, 2, 2, 3, '2019-05-31 09:34:51');
-- INSERT INTO "Screening" VALUES (19, 3, 2, 1, '2021-07-10 14:37:00');
-- INSERT INTO "Screening" VALUES (20, 5, 1, 4, '2018-12-23 21:11:35');
-- INSERT INTO "Screening" VALUES (21, 3, 2, 2, '2021-02-03 20:15:53');
-- INSERT INTO "Screening" VALUES (22, 5, 4, 4, '2024-06-02 11:53:51');
-- INSERT INTO "Screening" VALUES (23, 1, 2, 2, '2023-06-29 17:57:08');
-- INSERT INTO "Screening" VALUES (24, 1, 1, 4, '2015-11-15 16:50:33');
-- INSERT INTO "Screening" VALUES (25, 6, 2, 1, '2025-08-19 18:53:15');
-- INSERT INTO "Screening" VALUES (26, 2, 6, 1, '2025-09-14 14:09:37');
-- INSERT INTO "Screening" VALUES (27, 5, 6, 2, '2026-01-22 11:09:57');
-- INSERT INTO "Screening" VALUES (28, 4, 5, 4, '2026-01-17 14:44:02');
-- INSERT INTO "Screening" VALUES (29, 6, 4, 2, '2025-06-02 14:24:08');
-- INSERT INTO "Screening" VALUES (30, 1, 4, 3, '2025-07-03 17:05:51');
INSERT INTO "Screening" VALUES (1001, 59, 2, 1, '2026-01-01 09:00:00');
INSERT INTO "Screening" VALUES (1002, 63, 2, 2, '2026-01-02 09:00:00');
INSERT INTO "Screening" VALUES (1003, 54, 2, 1, '2026-01-03 09:00:00');
INSERT INTO "Screening" VALUES (1004, 49, 2, 1, '2026-01-04 09:00:00');
INSERT INTO "Screening" VALUES (1005, 50, 2, 1, '2026-01-05 09:00:00');
INSERT INTO "Screening" VALUES (1006, 60, 1, 2, '2026-01-06 09:00:00');
INSERT INTO "Screening" VALUES (1007, 62, 1, 1, '2026-01-07 09:00:00');
INSERT INTO "Screening" VALUES (1008, 56, 2, 3, '2026-10-08 09:00:00');
INSERT INTO "Screening" VALUES (1009, 64, 1, 3, '2026-02-09 09:00:00');
INSERT INTO "Screening" VALUES (1011, 52, 1, 1, '2026-10-10 08:10:00');
INSERT INTO "Screening" VALUES (1012, 61, 2, 4, '2026-10-11 09:11:00');
INSERT INTO "Screening" VALUES (1013, 51, 2, 2, '2026-10-12 08:00:00');
INSERT INTO "Screening" VALUES (1014, 55, 1, 1, '2026-10-13 08:00:00');
INSERT INTO "Screening" VALUES (1015, 66, 2, 2, '2026-10-14 08:00:00');
INSERT INTO "Screening" VALUES (1016, 65, 2, 2, '2026-10-16 08:00:00');
INSERT INTO "Screening" VALUES (1017, 53, 2, 4, '2026-10-17 08:00:00');
INSERT INTO "Screening" VALUES (1018, 57, 2, 3, '2026-10-18 10:00:00');
INSERT INTO "Screening" VALUES (1019, 58, 2, 3, '2026-10-19 08:00:00');




-- ----------------------------
-- Table structure for Seat_Type
-- ----------------------------
DROP TABLE IF EXISTS "Seat_Type" CASCADE;
CREATE TABLE "Seat_Type" (
    "ID_Seat_Type" SERIAL PRIMARY KEY,
    "seat_name" VARCHAR(40) NOT NULL,
    "price" NUMERIC(10, 2) NOT NULL
);
-- Unique Index
CREATE UNIQUE INDEX "seat_name" ON "Seat_Type" ("seat_name");
ALTER SEQUENCE "Seat_Type_ID_Seat_Type_seq" RESTART WITH 4;

-- ----------------------------
-- Records of Seat_Type
-- ----------------------------
INSERT INTO "Seat_Type" VALUES (1, 'standard', 5.00);
INSERT INTO "Seat_Type" VALUES (2, 'premium', 9.00);
INSERT INTO "Seat_Type" VALUES (3, 'bed', 18.00);


-- ----------------------------
-- Table structure for Seat
-- ----------------------------
DROP TABLE IF EXISTS "Seat" CASCADE;
CREATE TABLE "Seat" (
    "ID_Seat" SERIAL PRIMARY KEY,
    "ID_Hall" INT NOT NULL,
    "ID_Seat_Type" INT NOT NULL,
    "row" VARCHAR(2) NOT NULL,
    "number" INT NOT NULL,

    -- Foreign Keys
    FOREIGN KEY ("ID_Hall") REFERENCES "Hall" ("ID_Hall") ON DELETE RESTRICT ON UPDATE RESTRICT,
    FOREIGN KEY ("ID_Seat_Type") REFERENCES "Seat_Type" ("ID_Seat_Type") ON DELETE RESTRICT ON UPDATE RESTRICT
);
-- Indexes
CREATE INDEX "fk_seat_hall" ON "Seat" ("ID_Hall");
CREATE INDEX "fk_seat_seat_type" ON "Seat" ("ID_Seat_Type");
ALTER SEQUENCE "Seat_ID_Seat_seq" RESTART WITH 14;

-- ----------------------------
-- Records of Seat
-- ----------------------------
INSERT INTO "Seat" VALUES (1, 1, 1, 'a', 1);
INSERT INTO "Seat" VALUES (2, 1, 1, 'a', 2);
INSERT INTO "Seat" VALUES (3, 1, 1, 'a', 3);
INSERT INTO "Seat" VALUES (4, 1, 1, 'b', 1);
INSERT INTO "Seat" VALUES (5, 1, 1, 'b', 2);
INSERT INTO "Seat" VALUES (6, 1, 1, 'b', 3);
INSERT INTO "Seat" VALUES (7, 1, 2, 'c', 1);
INSERT INTO "Seat" VALUES (8, 1, 2, 'c', 2);
INSERT INTO "Seat" VALUES (9, 1, 2, 'c', 3);
INSERT INTO "Seat" VALUES (10, 2, 1, 'a', 1);
INSERT INTO "Seat" VALUES (11, 2, 1, 'b', 1);
INSERT INTO "Seat" VALUES (12, 2, 3, 'c', 1);
INSERT INTO "Seat" VALUES (13, 2, 3, 'd', 1);

-- ----------------------------
-- Table structure for User_Type
-- ----------------------------
DROP TABLE IF EXISTS "User_Type" CASCADE;
CREATE TABLE "User_Type" (
    "ID_User_Type" SERIAL PRIMARY KEY,
    "type_name" VARCHAR(40) NOT NULL,
    -- Unique index on "type_name"
    CONSTRAINT "type_name_unique" UNIQUE ("type_name")
);
ALTER SEQUENCE "User_Type_ID_User_Type_seq" RESTART WITH 8;

-- ----------------------------
-- Records of User_Type
-- ----------------------------
-- INSERT INTO "User_Type" VALUES (1, 'Cashier');
-- INSERT INTO "User_Type" VALUES (2, 'Chief operations officer');
-- INSERT INTO "User_Type" VALUES (3, 'Manager');
-- INSERT INTO "User_Type" VALUES (4, 'Scheduler');
-- INSERT INTO "User_Type" VALUES (5, 'Administrator');
INSERT INTO "User_Type" VALUES (6, 'Admin');
INSERT INTO "User_Type" VALUES (7, 'Employee');

-- ----------------------------
-- Table structure for User
-- ----------------------------
DROP TABLE IF EXISTS "User" CASCADE;
CREATE TABLE "User" (
    "ID_User" SERIAL PRIMARY KEY,
    "ID_User_Type" INT NOT NULL,
    "user_name" VARCHAR(40) NOT NULL,
    "user_surname" VARCHAR(40) NOT NULL,
    "nick" VARCHAR(40) NOT NULL UNIQUE,
    "password_hash" VARCHAR(80) NOT NULL,
    -- Foreign Key
    CONSTRAINT "fk_user_type" FOREIGN KEY ("ID_User_Type") REFERENCES "User_Type" ("ID_User_Type") ON DELETE RESTRICT ON UPDATE RESTRICT
);
-- Index
CREATE INDEX "fk_user_type" ON "User" ("ID_User_Type");
ALTER SEQUENCE "User_ID_User_seq" RESTART WITH 7;

-- ----------------------------
-- Records of User
-- ----------------------------
-- INSERT INTO "User" VALUES (1, 5, 'Larry', 'Stevens', 'larry_stevens', 'hashed_password1');
-- INSERT INTO "User" VALUES (2, 5, 'Luis', 'Boyd', 'luis_boyd', 'hashed_password2');
-- INSERT INTO "User" VALUES (3, 5, 'Leroy', 'Moreno', 'leroy_moreno', 'hashed_password3');
-- INSERT INTO "User" VALUES (4, 5, 'Anne', 'Butler', 'anne_butler', 'hashed_password4');
-- INSERT INTO "User" VALUES (5, 5, 'Marjorie', 'Ruiz', 'marjorie_ruiz', 'hashed_password5');
-- INSERT INTO "User" VALUES (6, 5, 'Scott', 'Castillo', 'scott_castillo', 'hashed_password6');
INSERT INTO "User" VALUES (7, 6, 'admin', 'admin', 'admin', '$2y$12$oHBPgN2x397l9ecLwcNdEeB/.1V6TFekvDNckkfmmvmq8Dfr7qFWe');
INSERT INTO "User" VALUES (8, 7, 'employee', 'employee', 'employee', '$2y$12$cLCGaBNo0hiWN3sMu.FNUOvWgCTar1ZryB605kBTqpdwm9d2uElTy');

-- ----------------------------
-- Table structure for UserType_Permissions
-- ----------------------------
DROP TABLE IF EXISTS "UserType_Permissions" CASCADE;
CREATE TABLE "UserType_Permissions" (
    "ID_User_Type" INT NOT NULL,
    "ID_perm" INT NOT NULL,
    -- Foreign Keys
    CONSTRAINT "ID_Perm" FOREIGN KEY ("ID_perm") REFERENCES "Permissions" ("ID_Perm") ON DELETE RESTRICT ON UPDATE RESTRICT,
    CONSTRAINT "ID_User_Type" FOREIGN KEY ("ID_User_Type") REFERENCES "User_Type" ("ID_User_Type") ON DELETE RESTRICT ON UPDATE RESTRICT
);
-- Indexes
CREATE INDEX "ID_User_Type" ON "UserType_Permissions" ("ID_User_Type");
CREATE INDEX "ID_Perm" ON "UserType_Permissions" ("ID_perm");

-- ----------------------------
-- Records of UserType_Permissions
-- ----------------------------
-- INSERT INTO "UserType_Permissions" VALUES (1, 1);
-- INSERT INTO "UserType_Permissions" VALUES (1, 2);
-- INSERT INTO "UserType_Permissions" VALUES (2, 1);
-- INSERT INTO "UserType_Permissions" VALUES (2, 2);
-- INSERT INTO "UserType_Permissions" VALUES (2, 3);
-- INSERT INTO "UserType_Permissions" VALUES (3, 1);
-- INSERT INTO "UserType_Permissions" VALUES (3, 2);
-- INSERT INTO "UserType_Permissions" VALUES (4, 3);
-- INSERT INTO "UserType_Permissions" VALUES (3, 3);
-- INSERT INTO "UserType_Permissions" VALUES (4, 4);
-- INSERT INTO "UserType_Permissions" VALUES (5, 3);
-- INSERT INTO "UserType_Permissions" VALUES (5, 4);
-- INSERT INTO "UserType_Permissions" VALUES (5, 1);
--type Admin permissions removeMovie, removeReservation, removeScreening, removeClient, removeUser
INSERT INTO "UserType_Permissions" VALUES (6, 5);
INSERT INTO "UserType_Permissions" VALUES (6, 6);
INSERT INTO "UserType_Permissions" VALUES (6, 7);
INSERT INTO "UserType_Permissions" VALUES (6, 8);
INSERT INTO "UserType_Permissions" VALUES (6, 9);
--type Employee permissions - none



-- ----------------------------
-- Table structure for Forum
-- ----------------------------
DROP TABLE IF EXISTS "Forum" CASCADE;
CREATE TABLE "Forum" (
    "ID_Comment" SERIAL PRIMARY KEY,
    "ID_ParentComment" INT NULL DEFAULT NULL,
    "ID_Movie" INT NOT NULL,
    "ID_Client" INT NOT NULL,
    "content" VARCHAR(400) NOT NULL,
    "stars" SMALLINT CHECK ("stars" BETWEEN 0 AND 10),
    FOREIGN KEY ("ID_Client") REFERENCES "Client" ("ID_Client") ON DELETE RESTRICT ON UPDATE RESTRICT,
    FOREIGN KEY ("ID_Movie") REFERENCES "Movie" ("ID_Movie") ON DELETE RESTRICT ON UPDATE RESTRICT
);
CREATE INDEX "idx_id_movie" ON "Forum" ("ID_Movie");
CREATE INDEX "idx_id_client" ON "Forum" ("ID_Client");

-- PostgreSQL does not support FULLTEXT indexing natively like MariaDB. You can use a GIN index for text search:
-- CREATE INDEX "content_gin" ON "Forum" USING GIN (to_tsvector('english', "content"));
ALTER SEQUENCE "Forum_ID_Comment_seq" RESTART WITH 49;

-- ----------------------------
-- Records of Forum
-- ----------------------------
-- INSERT INTO "Forum" VALUES (1, NULL, 7, 27, 'There is no way to happiness. Happiness is the way. The On Startup feature allows you to control what tabs appear when you launch Navicat. Navicat is a multi-connections Database Administration       ', 1);
-- INSERT INTO "Forum" VALUES (2, NULL, 4, 22, 'Such sessions are also susceptible to session hijacking, where a malicious user takes over your session once you have authenticated. The Synchronize to Database function will give you                 ', 3);
-- INSERT INTO "Forum" VALUES (3, NULL, 1, 26, 'You will succeed because most people are lazy. To clear or reload various internal caches, flush tables, or acquire locks, control-click your connection in the Navigation pane and select              ', 7);
-- INSERT INTO "Forum" VALUES (4, NULL, 6, 23, 'Monitored servers include MySQL, MariaDB and SQL Server, and compatible with cloud databases like Amazon RDS, Amazon Aurora, Oracle Cloud, Google Cloud and Microsoft Azure.', 2);
-- INSERT INTO "Forum" VALUES (5, NULL, 7, 6, 'To successfully establish a new connection to local/remote server - no matter via SSL or SSH, set the database login information in the General tab.', 7);
-- INSERT INTO "Forum" VALUES (6, NULL, 1, 17, 'The On Startup feature allows you to control what tabs appear when you launch Navicat. Navicat Cloud could not connect and access your databases. By which it means, it could only store                ', 10);
-- INSERT INTO "Forum" VALUES (38, 1, 3, 2, 'bad', NULL);
-- INSERT INTO "Forum" VALUES (39, NULL, 3, 2, 'bad', 3);
-- INSERT INTO "Forum" VALUES (40, NULL, 3, 2, 'not good', NULL);
-- INSERT INTO "Forum" VALUES (41, NULL, 4, 2, 'not bad', 3);
-- INSERT INTO "Forum" VALUES (42, 41, 4, 2, 'notgood', NULL);
-- INSERT INTO "Forum" VALUES (43, 2, 4, 6, 'mmm', NULL);
-- INSERT INTO "Forum" VALUES (44, 2, 4, 2, '22', NULL);
-- INSERT INTO "Forum" VALUES (45, 42, 4, 1, '1111111111', NULL);
-- INSERT INTO "Forum" VALUES (46, 2, 4, 1, '555', NULL);
-- INSERT INTO "Forum" VALUES (47, 44, 4, 1, '555', NULL);
-- INSERT INTO "Forum" VALUES (48, NULL, 2, 4, 'megahiperultra', 10);




-- ----------------------------
-- Table structure for Reservation
-- ----------------------------
DROP TABLE IF EXISTS "Reservation" CASCADE;
CREATE TABLE "Reservation" (
    "ID_Reservation" SERIAL PRIMARY KEY,
    "ID_Seat" INT NOT NULL,
    "ID_Screening" INT NOT NULL,
    "ID_Discount" INT DEFAULT NULL,
    "ID_Client" INT DEFAULT NULL,
    "total_price_netto" NUMERIC(10, 2) NOT NULL,
    "total_price_brutto" NUMERIC(10, 2) NOT NULL,
    "vat_percentage" NUMERIC(4, 2) NOT NULL,
    "reservation_date" TIMESTAMP WITH TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP,
    "NIP" VARCHAR(10) DEFAULT NULL,
    "NRB" VARCHAR(26) DEFAULT NULL,
    "address_street" VARCHAR(100) DEFAULT NULL,
    "address_nr" VARCHAR(10) DEFAULT NULL,
    "address_flat" VARCHAR(10) DEFAULT NULL,
    "address_city" VARCHAR(50) DEFAULT NULL,
    "address_zip" VARCHAR(11) DEFAULT NULL,
    -- Foreign Keys
    FOREIGN KEY ("ID_Client") REFERENCES "Client" ("ID_Client") ON DELETE RESTRICT ON UPDATE RESTRICT,
    FOREIGN KEY ("ID_Discount") REFERENCES "Discount" ("ID_Discount") ON DELETE RESTRICT ON UPDATE RESTRICT,
    FOREIGN KEY ("ID_Screening") REFERENCES "Screening" ("ID_Screening") ON DELETE RESTRICT ON UPDATE RESTRICT,
    FOREIGN KEY ("ID_Seat") REFERENCES "Seat" ("ID_Seat") ON DELETE RESTRICT ON UPDATE RESTRICT
);
-- Indexes
CREATE UNIQUE INDEX "seat_screening" ON "Reservation" ("ID_Seat", "ID_Screening");
CREATE INDEX "fk_reservation_screening" ON "Reservation" ("ID_Screening");
CREATE INDEX "fk_reservation_discount" ON "Reservation" ("ID_Discount");
CREATE INDEX "ID_Client" ON "Reservation" ("ID_Client");
ALTER SEQUENCE "Reservation_ID_Reservation_seq" RESTART WITH 1562;


-- ----------------------------
-- Records of Reservation
-- ----------------------------
-- INSERT INTO "Reservation" VALUES (1, 1, 1, 1, 30, 70.00, 86.10, 23.00, '2024-06-19 15:03:26', NULL, NULL, NULL, NULL, NULL, NULL, NULL);
-- INSERT INTO "Reservation" VALUES (2, 2, 3, 1, 20, 31.00, 38.13, 23.00, '2024-05-23 15:03:57', NULL, NULL, NULL, NULL, NULL, NULL, NULL);
-- INSERT INTO "Reservation" VALUES (3, 6, 6, 2, 15, 18.00, 22.14, 23.00, '2024-06-19 15:04:20', NULL, NULL, NULL, NULL, NULL, NULL, NULL);
-- INSERT INTO "Reservation" VALUES (4, 7, 6, 2, 15, 22.00, 27.06, 23.00, '2024-06-19 15:04:25', NULL, NULL, NULL, NULL, NULL, NULL, NULL);
-- INSERT INTO "Reservation" VALUES (1518, 8, 6, 2, 15, 22.00, 27.06, 23.00, '2024-04-16 15:05:44', NULL, NULL, NULL, NULL, NULL, NULL, NULL);
-- INSERT INTO "Reservation" VALUES (1519, 3, 15, 2, 18, 31.00, 36.58, 18.00, '2024-06-19 15:06:01', NULL, NULL, NULL, NULL, NULL, NULL, NULL);
-- INSERT INTO "Reservation" VALUES (1520, 4, 15, 2, 19, 31.00, 36.58, 18.00, '2024-06-19 15:06:09', NULL, NULL, NULL, NULL, NULL, NULL, NULL);
-- INSERT INTO "Reservation" VALUES (1521, 5, 15, 2, 19, 31.00, 38.13, 23.00, '2024-06-19 15:06:17', NULL, NULL, NULL, NULL, NULL, NULL, NULL);
-- INSERT INTO "Reservation" VALUES (1522, 7, 15, 1, 19, 35.00, 43.05, 23.00, '2024-05-28 15:06:27', NULL, NULL, NULL, NULL, NULL, NULL, NULL);
-- INSERT INTO "Reservation" VALUES (1523, 13, 12, 4, 3, 45.00, 49.05, 9.00, '2024-05-28 15:06:49', NULL, NULL, NULL, NULL, NULL, NULL, NULL);
-- INSERT INTO "Reservation" VALUES (1524, 8, 7, 4, 3, 19.00, 22.42, 18.00, '2024-06-19 15:09:08', '1234567890', '123456', 'kolorowa', '1', '123', 'Kraków', '30-001');
-- INSERT INTO "Reservation" VALUES (1525, 10, 5, 4, 3, 19.00, 22.42, 18.00, '2024-06-19 15:16:23', '1234567890', '123456', 'zielona', '2', '123\\2', 'Warszawa', '00-001');
-- INSERT INTO "Reservation" VALUES (1526, 11, 5, 4, 3, 19.00, 22.42, 18.00, '2024-06-19 15:16:41', '1234567890', '123456', 'magiczna', '3a', '23b', 'Poznań', '60-001');
-- INSERT INTO "Reservation" VALUES (1527, 12, 5, 1, 20, 31.00, 36.58, 18.00, '2024-06-19 15:24:08', NULL, NULL, NULL, NULL, NULL, NULL, NULL);
-- INSERT INTO "Reservation" VALUES (1528, 3, 5, 1, 20, 18.00, 21.24, 18.00, '2024-06-19 15:35:07', NULL, NULL, NULL, NULL, NULL, NULL, NULL);
-- INSERT INTO "Reservation" VALUES (1542, 1, 15, 1, 30, 31.00, 38.13, 23.00, '2024-06-19 22:59:47', '1234567890', '123123123123123123', 'dfg', '23f', '12', 'Wrocław', '50-001');
-- INSERT INTO "Reservation" VALUES (1544, 2, 15, 1, 30, 31.00, 38.13, 23.00, '2024-06-19 23:00:07', '1234567890', '123123123123123123', 'dfg', '23f', '12', 'Wrocław', '50-001');
-- INSERT INTO "Reservation" VALUES (1561, 6, 15, NULL, 1, 35.00, 43.05, 23.00, '2024-06-19 23:17:23', '1234567890', '123123123123123123', 'dfg', '23f', '12', 'Wrocław', '50-001');


-- ----------------------------
-- View structure for vMonthlyRevenue
-- ----------------------------
DROP VIEW IF EXISTS "vMonthlyRevenue";
CREATE VIEW "vMonthlyRevenue" AS
SELECT
    EXTRACT(YEAR FROM "Reservation"."reservation_date") AS "year",
    EXTRACT(MONTH FROM "Reservation"."reservation_date") AS "month",
    SUM("Reservation"."total_price_netto") AS "revenue_netto",
    SUM("Reservation"."total_price_brutto") AS "revenue_brutto",
    COUNT("Reservation"."total_price_netto") AS "numberOfReservations"
FROM "Reservation"
GROUP BY EXTRACT(YEAR FROM "Reservation"."reservation_date"),
         EXTRACT(MONTH FROM "Reservation"."reservation_date");

-- ----------------------------
-- View structure for vMovieRatings
-- ----------------------------
DROP VIEW IF EXISTS "vMovieRatings";
CREATE VIEW "vMovieRatings" AS
SELECT
    "Movie"."title" AS "title",
    AVG("Forum"."stars") AS "avg(stars)",
    COUNT("Forum"."stars") AS "number_of_reviews"
FROM "Movie"
         JOIN "Forum" ON "Movie"."ID_Movie" = "Forum"."ID_Movie"
WHERE "Forum"."ID_ParentComment" IS NULL
GROUP BY "Movie"."title";

-- ----------------------------
-- View structure for vTicket
-- ----------------------------
DROP VIEW IF EXISTS "vTicket";
CREATE VIEW "vTicket" AS
SELECT
    "Client"."nick" AS "nick",
    "Movie"."title" AS "title",
    "Screening"."start_time" AS "start_time",
    CONCAT("Seat"."row", "Seat"."number") AS "seat",
    "Hall"."hall_name" AS "hall_name"
FROM "Reservation"
         JOIN "Client" ON "Reservation"."ID_Client" = "Client"."ID_Client"
         JOIN "Screening" ON "Reservation"."ID_Screening" = "Screening"."ID_Screening"
         JOIN "Movie" ON "Screening"."ID_Movie" = "Movie"."ID_Movie"
         LEFT JOIN "Seat" ON "Seat"."ID_Seat" = "Reservation"."ID_Seat"
         LEFT JOIN "Hall" ON "Seat"."ID_Hall" = "Hall"."ID_Hall"
WHERE "Screening"."start_time" > CURRENT_TIMESTAMP;



-- ----------------------------
-- Function structure for calculate_price
-- ----------------------------
DROP FUNCTION IF EXISTS calculate_price;
CREATE OR REPLACE FUNCTION calculate_price(
    vidSeat INT,
    vidScreening INT,
    vidDiscount INT
)
    RETURNS DECIMAL(10, 2) AS
$$
DECLARE
    vSeatPrice DECIMAL(10, 2);
    vScreeningPrice DECIMAL(10, 2);
    vDiscountAmount DECIMAL(10, 2);
    vTotalPrice DECIMAL(10, 2);
BEGIN
    -- Check if the seat exists
    IF NOT EXISTS (SELECT 1 FROM "Seat" WHERE "ID_Seat" = vidSeat) THEN
        RAISE EXCEPTION 'Nie ma takiego siedzenia';
        -- Check if the screening exists
    ELSIF NOT EXISTS (SELECT 1 FROM "Screening" WHERE "ID_Screening" = vidScreening) THEN
        RAISE EXCEPTION 'Nie ma takiego pokazu';
        -- Check if the discount exists
    ELSIF vidDiscount IS NOT NULL AND NOT EXISTS (SELECT 1 FROM "Discount" WHERE "ID_Discount" = vidDiscount) THEN
       RAISE EXCEPTION 'Nie ma takiej zniżki';
    ELSE
        -- Get the seat price
        SELECT "price" INTO vSeatPrice
        FROM "Seat_Type"
        WHERE "ID_Seat_Type" = (
            SELECT "ID_Seat_Type"
            FROM "Seat"
            WHERE "ID_Seat" = vidSeat
        );

        -- Get the screening price
        SELECT "price" INTO vScreeningPrice
        FROM "Screening_Type"
        WHERE "ID_Screening_Type" = (
            SELECT "ID_Screening_Type"
            FROM "Screening"
            WHERE "ID_Screening" = vidScreening
        );

        -- Get the discount amount if applicable
        IF vidDiscount IS NOT NULL THEN
            SELECT "amount" INTO vDiscountAmount
            FROM "Discount"
            WHERE "ID_Discount" = vidDiscount;
        ELSE
            vDiscountAmount := 0;
        END IF;

        -- Calculate the total price
        vTotalPrice := vSeatPrice + vScreeningPrice - vDiscountAmount;

        -- Ensure the total price is not negative
        IF vTotalPrice < 0 THEN
            vTotalPrice := 0;
        END IF;

        -- Return the total price
        RETURN vTotalPrice;
    END IF;
END;
$$ LANGUAGE plpgsql;



-- ----------------------------
-- Procedure structure for display_comments
-- ----------------------------
DROP FUNCTION IF EXISTS display_comments;
CREATE OR REPLACE FUNCTION display_comments(movie_id INT)
    RETURNS TABLE (
                      ID_Comment INT,
                      ID_ParentComment INT,
                      ID_Movie INT,
                      ID_Client INT,
                      content TEXT,
                      stars INT,
                      level INT
                  ) AS $$
BEGIN
    RETURN QUERY
        WITH RECURSIVE CommentTree AS (
            -- CTE Anchor: Select main comments (no parent)
            SELECT
                ID_Comment,
                ID_ParentComment,
                ID_Movie,
                ID_Client,
                content,
                stars,
                0 AS level,
                CAST(ID_Comment AS TEXT) AS comment_path
            FROM "Forum"
            WHERE ID_ParentComment IS NULL AND ID_Movie = movie_id

            UNION ALL

            -- CTE Recursive Member: Select child comments
            SELECT
                f."ID_Comment",
                f."ID_ParentComment",
                f."ID_Movie",
                f."ID_Client",
                f.content,
                f.stars,
                ct.level + 1 AS level,
                CONCAT(ct.comment_path, '/', CAST(f."ID_Comment" AS TEXT)) AS comment_path
            FROM "Forum" f
                     INNER JOIN CommentTree ct ON f."ID_ParentComment" = ct.ID_Comment
        )
        SELECT
            ID_Comment,
            ID_ParentComment,
            ID_Movie,
            ID_Client,
            content,
            stars,
            level
        FROM CommentTree
        ORDER BY comment_path;
END;
$$ LANGUAGE plpgsql;


-- ----------------------------
-- Procedure structure for new_comment
-- ----------------------------
DROP FUNCTION IF EXISTS new_comment;
CREATE OR REPLACE FUNCTION new_comment(
    vidParent INT,
    vidMovie INT,
    vidClient INT,
    vContent VARCHAR(400),
    vStars INT
)
    RETURNS VOID AS
$$
DECLARE
    -- Declare the necessary variables
    vMovie INT;
BEGIN
    -- Check if the parent comment exists, raise exception if not
    IF vidParent IS NOT NULL AND NOT EXISTS (SELECT 1 FROM "Forum" WHERE "ID_Comment" = vidParent) THEN
        RAISE EXCEPTION 'Nie da się odpowiedzieć na nieistniejący komentarz.';
    END IF;

    -- Set stars to NULL if parent comment exists or if a comment already exists with stars = NULL
    IF vidParent IS NOT NULL OR EXISTS (SELECT 1 FROM "Forum" WHERE "ID_Movie" = vidMovie AND "ID_Client" = vidClient AND stars IS NULL) THEN
        vStars := NULL;
    END IF;

    -- If there's a parent comment, fetch the movie ID from the parent comment
    IF vidParent IS NOT NULL THEN
        SELECT "ID_Movie" INTO vMovie FROM "Forum" WHERE "ID_Comment" = vidParent;
    ELSE
        vMovie := vidMovie;
    END IF;

    -- Insert the new comment into the Forum table
    INSERT INTO "Forum" ("ID_ParentComment", "ID_Movie", "ID_Client", content, stars)
    VALUES (vidParent, vMovie, vidClient, vContent, vStars);

END;
$$ LANGUAGE plpgsql;


-- ----------------------------
-- Procedure structure for new_reservation
-- ----------------------------
DROP FUNCTION IF EXISTS new_reservation;
CREATE OR REPLACE FUNCTION new_reservation(
    vidSeat INT,
    vidScreening INT,
    vidDiscount INT,
    vidClient INT,
    vVatPercentage DECIMAL(4,2),
    vNIP VARCHAR(10),
    vNRB VARCHAR(26),
    vAddress_street VARCHAR(100),
    vAddress_nr VARCHAR(10),
    vAddress_flat VARCHAR(10),
    vAddress_city VARCHAR(50),
    vAddress_zip VARCHAR(11)
)
    RETURNS "Reservation" AS
$$
DECLARE
    price_netto DECIMAL(10,2);
    errno INT := 0;
    reservation_record "Reservation"; -- Declare a variable to hold the returned record
BEGIN
    -- Calculate the price_netto using the provided function
    price_netto := calculate_price(vidSeat, vidScreening, vidDiscount);

    -- Check if the client exists
    IF NOT EXISTS (SELECT 1 FROM "Client" WHERE "ID_Client" = vidClient) THEN
        errno := 3;
    END IF;

    -- Check if the screening has already started
    IF NOW() > (SELECT start_time FROM "Screening" WHERE "ID_Screening" = vidScreening) THEN
        errno := 1;
    END IF;

    -- Check if the seat belongs to the correct hall
    IF (SELECT "ID_Hall" FROM "Seat" WHERE "ID_Seat" = vidSeat) <> (SELECT "ID_Hall" FROM "Screening" WHERE "ID_Screening" = vidScreening) THEN
        errno := 2;
    END IF;

    -- If there is an error, raise the exception
    IF errno <> 0 THEN
        CASE errno
            WHEN 1 THEN
                RAISE EXCEPTION 'Pokaz już się zaczął';
            WHEN 2 THEN
                RAISE EXCEPTION 'Podane siedzenie nie znajduje się w tej sali';
            WHEN 3 THEN
                RAISE EXCEPTION 'Nie ma takiego użytkownika';
            END CASE;
    END IF;

    -- Insert the new reservation into the Reservation table
    INSERT INTO "Reservation" (
        "ID_Seat",
        "ID_Screening",
        "ID_Discount",
        "ID_Client",
        total_price_netto,
        total_price_brutto,
        reservation_date,
        vat_percentage,
        "NIP",
        "NRB",
        address_street,
        address_nr,
        address_flat,
        address_city,
        address_zip
    )
    VALUES (
               vidSeat,
               vidScreening,
               vidDiscount,
               vidClient,
               price_netto,
               (1 + vVatPercentage / 100) * price_netto,
               NOW(),
               vVatPercentage,
               vNIP,
               vNRB,
               vAddress_street,
               vAddress_nr,
               vAddress_flat,
               vAddress_city,
               vAddress_zip
           )
    RETURNING * INTO reservation_record;

    -- Return the inserted reservation record
    RETURN reservation_record;

END;
$$ LANGUAGE plpgsql;

-- ----------------------------
-- Procedure structure for new_screening
-- ----------------------------
DROP FUNCTION IF EXISTS new_screening;
CREATE OR REPLACE FUNCTION new_screening(
    p_id_movie INTEGER,
    p_id_hall INTEGER,
    p_id_screening_type INTEGER,
    p_start_time TIMESTAMP WITH TIME ZONE,
    p_buffer_minutes INTEGER DEFAULT 15
)
    RETURNS "Screening" AS $$
DECLARE
    movie_duration INTERVAL;
    buffer_interval INTERVAL;
    new_start_time TIMESTAMP WITH TIME ZONE;
    new_end_time TIMESTAMP WITH TIME ZONE;
    new_screening "Screening"; -- Variable to store the newly inserted screening
BEGIN
    -- Validate buffer_minutes parameter
    IF p_buffer_minutes IS NULL OR p_buffer_minutes < 0 THEN
        RAISE EXCEPTION 'Invalid buffer minutes: % (must be non-null and non-negative)', p_buffer_minutes;
    END IF;

    -- Convert buffer_minutes to interval
    buffer_interval := p_buffer_minutes * INTERVAL '1 minute';

    -- Check if Movie ID is valid
    IF p_id_movie IS NULL OR NOT EXISTS (SELECT 1 FROM "Movie" WHERE "ID_Movie" = p_id_movie) THEN
        RAISE EXCEPTION 'Invalid Movie ID: %', p_id_movie;
    END IF;

    -- Check if Hall ID is valid
    IF p_id_hall IS NULL OR NOT EXISTS (SELECT 1 FROM "Hall" WHERE "ID_Hall" = p_id_hall) THEN
        RAISE EXCEPTION 'Invalid Hall ID: %', p_id_hall;
    END IF;

    -- Check if Screening Type ID is valid
    IF p_id_screening_type IS NULL OR NOT EXISTS (SELECT 1 FROM "Screening_Type" WHERE "ID_Screening_Type" = p_id_screening_type) THEN
        RAISE EXCEPTION 'Invalid Screening Type ID: %', p_id_screening_type;
    END IF;

    -- Check if Start Time is valid
    IF p_start_time IS NULL OR p_start_time < NOW() THEN
        RAISE EXCEPTION 'Invalid Start Time: %', p_start_time;
    END IF;

    -- Retrieve the movie's duration
    SELECT duration
    INTO movie_duration
    FROM "Movie"
    WHERE "ID_Movie" = p_id_movie;

    -- Calculate new time ranges for collision checking
    new_start_time := p_start_time - buffer_interval;
    new_end_time := p_start_time + movie_duration + buffer_interval;

    -- Check for time collisions in the given hall
    IF EXISTS (
        SELECT 1
        FROM "Screening"
            NATURAL JOIN "Movie"
        WHERE
            "ID_Hall" = p_id_hall
            AND NOT (new_start_time >= start_time + duration + buffer_interval)
            AND NOT (new_end_time <= start_time - buffer_interval)
    ) THEN
        RAISE EXCEPTION 'Time collision detected for hall ID % at start time %', p_id_hall, p_start_time;
    END IF;

    -- Insert the new screening using the original start time
    INSERT INTO "Screening" (
        "ID_Movie",
        "ID_Hall",
        "ID_Screening_Type",
        start_time
    ) VALUES (
         p_id_movie,
         p_id_hall,
         p_id_screening_type,
         p_start_time
     )
    RETURNING * INTO new_screening;

    -- Return the inserted screening
    RETURN new_screening;
END;
$$ LANGUAGE plpgsql;


-- ----------------------------
-- Procedure structure for new_user
-- ----------------------------
DROP FUNCTION IF EXISTS new_user;
CREATE OR REPLACE FUNCTION new_user(
    vCname VARCHAR(40),
    vCsurname VARCHAR(40),
    vNick VARCHAR(40),
    vPassHash VARCHAR(80),
    vMail VARCHAR(320)
)
    RETURNS VOID AS
$$
DECLARE
    -- Declare any necessary variables (if needed)
BEGIN
    -- Start the transaction
    -- SET TRANSACTION ISOLATION LEVEL READ COMMITTED;

    -- Check if the nickname or email already exists
    IF EXISTS (SELECT 1 FROM "Client" WHERE nick = vNick OR mail = vMail) THEN
        RAISE EXCEPTION 'Nazwa lub mail jest zajęta';
    END IF;

    -- Insert the new user
    INSERT INTO "Client" (client_name, client_surname, nick, password_hash, mail)
    VALUES (vCname, vCsurname, vNick, vPassHash, vMail);

    -- Commit the transaction
    -- COMMIT;
END;
$$ LANGUAGE plpgsql;


-- ----------------------------
-- Event structure for delete_old_screening
-- ----------------------------
-- Check if pg_cron extension is installed (it should be by now)
CREATE EXTENSION IF NOT EXISTS pg_cron; --
-- Drop the cron job if it exists
SELECT cron.unschedule(jobid) FROM cron.job WHERE jobname = 'delete_old_screening';

-- Schedule the job to run every week
SELECT cron.schedule(
'delete_old_screening', -- Job name
'0 0 * * 1',            -- Every Monday at midnight
$$
    DELETE FROM Screening
    WHERE start_time < NOW() - INTERVAL '365 days';
$$);

-- ----------------------------
-- Event structure for delete_expired_sessions
-- ----------------------------
-- Drop the cron job if it exists
SELECT cron.unschedule(jobid) FROM cron.job WHERE jobname = 'delete_expired_sessions';

-- Schedule the job to run every day at midnight
SELECT cron.schedule(
'delete_expired_sessions', -- Job name
'0 0 * * *',               -- Every day at midnight
$$
    DELETE FROM "Client_sessions"
    WHERE "expiration_date" < NOW();
    DELETE FROM "User_sessions"
    WHERE "expiration_date" < NOW();
$$);


-- ----------------------------
-- Triggers structure for table Client
-- ----------------------------
CREATE OR REPLACE FUNCTION archive_client_update_func()
    RETURNS TRIGGER AS $$
BEGIN
    INSERT INTO "Client_archive" (
        "ID_Client",
        client_name,
        client_surname,
        nick,
        password_hash,
        mail,
        operation_type
    )
    VALUES (
               OLD."ID_Client",
               OLD.client_name,
               OLD.client_surname,
               OLD.nick,
               OLD.password_hash,
               OLD.mail,
               'u'  -- 'u' for update operation
           );

    RETURN NEW;  -- Return NEW to ensure the update proceeds
END;
$$ LANGUAGE plpgsql;
CREATE TRIGGER archive_client_update
    AFTER UPDATE ON "Client"
    FOR EACH ROW
EXECUTE FUNCTION archive_client_update_func();


-- ----------------------------
-- Triggers structure for table Client
-- ----------------------------
CREATE OR REPLACE FUNCTION archive_client_delete_func()
    RETURNS TRIGGER AS $$
BEGIN
    -- Insert the deleted row into the Client_archive table
    INSERT INTO "Client_archive" (
        "ID_Client",
        client_name,
        client_surname,
        nick,
        password_hash,
        mail,
        operation_type
    )
    VALUES (
               OLD."ID_Client",
               OLD.client_name,
               OLD.client_surname,
               OLD.nick,
               OLD.password_hash,
               OLD.mail,
               'd'  -- 'd' for delete operation
           );

    RETURN OLD;  -- Return OLD to indicate that the deletion should happen
END;
$$ LANGUAGE plpgsql;
CREATE TRIGGER archive_client_delete
    AFTER DELETE ON "Client"
    FOR EACH ROW
EXECUTE FUNCTION archive_client_delete_func();


-- ----------------------------
-- Triggers structure for table Forum
-- ----------------------------
CREATE OR REPLACE FUNCTION check_stars_func()
    RETURNS TRIGGER AS $$
BEGIN
    -- Check if stars is greater than 10
    IF NEW.stars > 10 THEN
        NEW.stars := 10;
        -- Check if stars is less than 0
    ELSIF NEW.stars < 0 THEN
        NEW.stars := 0;
    END IF;

    -- Return the modified row
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;
CREATE TRIGGER check_stars
    BEFORE INSERT ON "Forum"
    FOR EACH ROW
EXECUTE FUNCTION check_stars_func();

-- ----------------------------
-- Table structure for Client_sessions
-- ----------------------------
DROP TABLE IF EXISTS "Client_sessions" CASCADE;
CREATE TABLE "Client_sessions" (
    "ID_Session_Client" SERIAL PRIMARY KEY,
    "ID_Client" INT NOT NULL,
    "session_token" VARCHAR(80) UNIQUE NOT NULL,
    "expiration_date" TIMESTAMP WITH TIME ZONE NOT NULL,
    FOREIGN KEY ("ID_Client") REFERENCES "Client" ("ID_Client") ON DELETE CASCADE ON UPDATE CASCADE
);

-- ----------------------------
-- Table structure for User_sessions
-- ----------------------------
DROP TABLE IF EXISTS "User_sessions" CASCADE;
CREATE TABLE "User_sessions" (
    "ID_Session_User" SERIAL PRIMARY KEY,
    "ID_User" INT NOT NULL,
    "session_token" VARCHAR(80) UNIQUE NOT NULL,
    "expiration_date" TIMESTAMP WITH TIME ZONE NOT NULL,
    FOREIGN KEY ("ID_User") REFERENCES "User" ("ID_User") ON DELETE CASCADE ON UPDATE CASCADE
);

-- ----------------------------
-- Function for creating a new session with timezone for output
-- ----------------------------
DROP FUNCTION IF EXISTS create_session;
CREATE OR REPLACE FUNCTION create_session(
    p_id INT,
    p_type CHAR(1),
    p_days INT,
    p_hours INT,
    p_minutes INT,
    p_timezone TEXT, -- Timezone parameter for output, using TEXT to avoid SQLi issues
    OUT session_token VARCHAR(80),
    OUT expiration_date TIMESTAMP WITHOUT TIME ZONE -- OUT parameter for expiration date
) AS $$
DECLARE
    v_expiration_date TIMESTAMP WITH TIME ZONE := NOW() AT TIME ZONE 'UTC' + INTERVAL '1 day' * p_days + INTERVAL '1 hour' * p_hours + INTERVAL '1 minute' * p_minutes;
BEGIN
    session_token := gen_random_uuid()::text;

    -- Insert into corresponding session table based on session type
    IF p_type = 'c' THEN
        INSERT INTO "Client_sessions" ("ID_Client", "session_token", "expiration_date")
        VALUES (p_id, session_token, v_expiration_date);
    ELSIF p_type = 'u' THEN
        INSERT INTO "User_sessions" ("ID_User", "session_token", "expiration_date")
        VALUES (p_id, session_token, v_expiration_date);
    ELSE
        -- Invalid type, return NULL
        session_token := NULL;
        expiration_date := NULL;
        RETURN;
    END IF;

    -- Set the OUT parameter for the expiration date, converted to the requested timezone
    expiration_date := v_expiration_date at time zone p_timezone;

END;
$$ LANGUAGE plpgsql;

-- ----------------------------
-- Function for checking if a session exists and is not expired
-- ----------------------------
DROP FUNCTION IF EXISTS check_session;
CREATE OR REPLACE FUNCTION check_session(
    p_id INT,
    p_type CHAR(1),
    p_session_token VARCHAR(80),
    p_days INT,
    p_hours INT,
    p_minutes INT,
    p_timezone TEXT,
    OUT is_valid BOOLEAN,
    OUT expiration_date TIMESTAMP WITHOUT TIME ZONE -- OUT parameter for expiration date

) AS $$
DECLARE
    v_expiration_date TIMESTAMP WITH TIME ZONE := NOW() AT TIME ZONE 'UTC' + INTERVAL '1 day' * p_days + INTERVAL '1 hour' * p_hours + INTERVAL '1 minute' * p_minutes;
    v_table_name TEXT;
    v_id_column TEXT;
    v_cursor REFCURSOR; -- Cursor for dynamic query
    v_cursor_portal TEXT;
    v_session_row RECORD; -- Row type to store fetched session
BEGIN
    is_valid := FALSE;
    expiration_date := NULL;

    -- Determine the table and ID column based on the type
    IF p_type = 'c' THEN
        v_table_name := 'Client_sessions';
        v_id_column := 'ID_Client';
    ELSIF p_type = 'u' THEN
        v_table_name := 'User_sessions';
        v_id_column := 'ID_User';
    ELSE
        RETURN;
    END IF;

    -- Escape table and column names
    v_table_name := quote_ident(v_table_name);
    v_id_column := quote_ident(v_id_column);
    p_session_token := quote_literal(p_session_token);

    -- Open a cursor dynamically with escaped variables
    OPEN v_cursor FOR EXECUTE '
        SELECT *
        FROM ' || v_table_name || '
        WHERE ' || v_id_column || ' = ' || p_id || '
          AND session_token = ' || p_session_token || '
        FOR UPDATE';

    -- Store the portal name of the cursor for WHERE CURRENT OF in dynamic EXECUTE https://post.bytes.com/forum/topic/postgresql/776821-plpgsql-update-cursor-where-current-of-with-dynamic-query
    v_cursor_portal := quote_ident(v_cursor::text);

    -- Fetch the session row
    FETCH v_cursor INTO v_session_row;

    -- Check if the row was found
    IF NOT FOUND THEN
        -- No session found, return FALSE
        CLOSE v_cursor;
        RETURN;
    END IF;

    -- Check expiration date and decide to update or delete
    IF v_session_row.expiration_date <= NOW() THEN
        -- Expired session, delete the row using WHERE CURRENT OF
        EXECUTE '
            DELETE FROM ' || v_table_name || '
            WHERE CURRENT OF' || v_cursor_portal;

    ELSE
        -- Valid session, update expiration date using WHERE CURRENT OF
        EXECUTE '
            UPDATE ' || v_table_name || '
            SET expiration_date = ' || quote_literal(v_expiration_date) || '
            WHERE CURRENT OF' || v_cursor_portal;

        is_valid := TRUE;
        expiration_date := v_expiration_date at time zone p_timezone;
    END IF;

    -- Close the cursor and return the result
    CLOSE v_cursor;
END;
$$ LANGUAGE plpgsql;

-- ----------------------------
-- Function for deleting a session
-- ----------------------------
DROP FUNCTION IF EXISTS delete_session;
CREATE OR REPLACE FUNCTION delete_session(
    p_id INT,
    p_type CHAR(1),
    p_session_token VARCHAR(80)
)
    RETURNS BOOLEAN AS $$
DECLARE
    v_table_name TEXT;
    v_id_column TEXT;
BEGIN
    -- Determine the table and ID column based on the type
    IF p_type = 'c' THEN
        v_table_name := 'Client_sessions';
        v_id_column := 'ID_Client';
    ELSIF p_type = 'u' THEN
        v_table_name := 'User_sessions';
        v_id_column := 'ID_User';
    ELSE
        RETURN FALSE;
    END IF;

    -- Escape table and column names
    v_table_name := quote_ident(v_table_name);
    v_id_column := quote_ident(v_id_column);
    p_session_token := quote_literal(p_session_token);

    -- Delete the session row
    EXECUTE '
        DELETE FROM ' || v_table_name || '
        WHERE ' || v_id_column || ' = ' || p_id || '
          AND session_token = ' || p_session_token;

    -- Return TRUE if the row was deleted
    RETURN FOUND;
END;
$$ LANGUAGE plpgsql;