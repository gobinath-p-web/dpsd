CREATE TABLE IF NOT EXISTS student (
    regno TEXT PRIMARY KEY,
    name TEXT NOT NULL,
    password TEXT NOT NULL,
    address TEXT,
    arrear INTEGER,
    "S1percentage" REAL,
    "S2percentage" REAL,
    "S3percentage" REAL,
    "S4percentage" REAL,
    "S5percentage" REAL,
    "S6percentage" REAL,
    blood_group TEXT,
    phone_number TEXT,
    email TEXT UNIQUE,
    gender TEXT,
    dob TEXT
);