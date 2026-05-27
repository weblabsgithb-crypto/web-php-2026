-- Создание базы данных
CREATE DATABASE IF NOT EXISTS address_book CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE address_book;

-- Создание таблицы контактов
CREATE TABLE IF NOT EXISTS contacts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    surname VARCHAR(100) NOT NULL DEFAULT '',
    name VARCHAR(100) NOT NULL DEFAULT '',
    patronymic VARCHAR(100) NOT NULL DEFAULT '',
    gender ENUM('male', 'female') NOT NULL DEFAULT 'male',
    birth_date DATE DEFAULT NULL,
    phone VARCHAR(30) NOT NULL DEFAULT '',
    address VARCHAR(255) NOT NULL DEFAULT '',
    email VARCHAR(100) NOT NULL DEFAULT '',
    comment TEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Добавление тестовых записей
INSERT INTO contacts (surname, name, patronymic, gender, birth_date, phone, address, email, comment) VALUES
('Иванов', 'Иван', 'Иванович', 'male', '1990-05-15', '+7-900-123-45-67', 'г. Москва, ул. Ленина, д. 10', 'ivanov@example.com', 'Коллега по работе'),
('Петрова', 'Анна', 'Сергеевна', 'female', '1985-12-03', '+7-900-234-56-78', 'г. СПб, Невский пр., д. 25', 'petrova@example.com', 'Друг семьи'),
('Сидоров', 'Алексей', 'Петрович', 'male', '1992-08-20', '+7-900-345-67-89', 'г. Казань, ул. Баумана, д. 5', 'sidorov@example.com', ''),
('Козлова', 'Мария', 'Андреевна', 'female', '1988-03-10', '+7-900-456-78-90', 'г. Новосибирск, Красный пр., д. 15', 'kozlova@example.com', 'Одногруппница'),
('Морозов', 'Дмитрий', 'Владимирович', 'male', '1995-11-25', '+7-900-567-89-01', 'г. Екатеринбург, ул. Мира, д. 8', 'morozov@example.com', 'Сосед'),
('Новикова', 'Елена', 'Игоревна', 'female', '1993-07-07', '+7-900-678-90-12', 'г. Москва, ул. Арбат, д. 3', 'novikova@example.com', 'Знакомая'),
('Волков', 'Сергей', 'Александрович', 'male', '1987-01-30', '+7-900-789-01-23', 'г. Ростов, ул. Пушкина, д. 12', 'volkov@example.com', 'Партнёр по бизнесу'),
('Лебедева', 'Ольга', 'Николаевна', 'female', '1991-09-14', '+7-900-890-12-34', 'г. Самара, ул. Советская, д. 7', 'lebedeva@example.com', ''),
('Кузнецов', 'Андрей', 'Дмитриевич', 'male', '1989-04-22', '+7-900-901-23-45', 'г. Воронеж, ул. Кольцовская, д. 20', 'kuznetsov@example.com', 'Тренер'),
('Попова', 'Татьяна', 'Викторовна', 'female', '1994-06-18', '+7-900-012-34-56', 'г. Краснодар, ул. Красная, д. 30', 'popova@example.com', 'Врач'),
('Соколов', 'Николай', 'Павлович', 'male', '1986-02-28', '+7-900-111-22-33', 'г. Тула, пр. Ленина, д. 45', 'sokolov@example.com', 'Преподаватель'),
('Михайлова', 'Екатерина', 'Олеговна', 'female', '1997-10-05', '+7-900-222-33-44', 'г. Рязань, ул. Почтовая, д. 9', 'mikhailova@example.com', '');
