CREATE TABLE students (
    regno VARCHAR(20) PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    address TEXT,
    arrear NUMBER(2)
    S1percentage NUMBER(3,2);
    S2percentage NUMBER(3,2);
    S3percentage NUMBER(3,2);
    S4percentage NUMBER(3,2);
    S5percentage NUMBER(3,2);
    S6percentage NUMBER(3,2);
    blood_group VARCHAR(5),
    phone_number NUMBER(10),
    email VARCHAR(100) UNIQUE,
    gender VARCHAR(10),
    dob DATE
);