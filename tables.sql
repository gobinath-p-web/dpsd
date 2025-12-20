CREATE TABLE students (
    regno VARCHAR(20) PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    address TEXT,
    blood_group VARCHAR(5),
    phone_number VARCHAR(15),
    email VARCHAR(100) UNIQUE,
    gender VARCHAR(10),
    dob DATE
);