use db211927;

create table Hospital(
	h_code varchar(30) PRIMARY KEY NOT NULL,
    h_name varchar(30) NOT NULL,
    h_local varchar(30) NOT NULL,
	h_number varchar(30) NOT NULL
);

create table Users(
	h_code varchar(30) PRIMARY KEY NOT NULL,
    floor int NOT NULL,
    password VARCHAR(30) NOT NULL,
    CONSTRAINT fk_Hospital FOREIGN KEY (h_code) REFERENCES Hospital(h_code),
    UNIQUE (floor)
);

CREATE TABLE Fall(
    BedCODE VARCHAR(30) PRIMARY KEY NOT NULL,
    F_DATE DATE NOT NULL,
    F_TIME TIME NOT NULL,
    F_ROOM VARCHAR(20) NOT NULL,
    F_WHETHER CHAR(2) NOT NULL,
    h_code VARCHAR(30) NOT NULL,
    floor INT NOT NULL,
    CONSTRAINT fk_Hospital2 FOREIGN KEY (h_code) REFERENCES Hospital(h_code),
    CONSTRAINT fk_Users FOREIGN KEY (floor) REFERENCES Users(floor)
);