CREATE TABLE IF NOT EXISTS student (
    regno TEXT PRIMARY KEY,
    name TEXT NOT NULL,
    password TEXT NOT NULL,
    address TEXT,
    arrear INTEGER,
    S1percentage REAL,
    S2percentage REAL,
    S3percentage REAL,
    S4percentage REAL,
    S5percentage REAL,
    S6percentage REAL,
    blood_group TEXT,
    phone_number TEXT,
    email TEXT UNIQUE,
    gender TEXT,
    dob TEXT
);

CREATE TABLE IF NOT EXISTS attendance (
  regno TEXT NOT NULL,
  date TEXT NOT NULL,
  status TEXT CHECK(status IN ('P', 'A', 'H')) NOT NULL,
  FOREIGN KEY (regno) REFERENCES student(regno)
);

CREATE TABLE IF NOT EXISTS hod (
  hod_id TEXT PRIMARY KEY,
  name TEXT NOT NULL,
  password TEXT NOT NULL
);

CREATE TABLE IF NOT EXISTS staff (
  staff_id TEXT PRIMARY KEY,
  name TEXT NOT NULL,
  email TEXT NOT NULL,
  password TEXT NOT NULL
);