-- Tietokantaohjelmointi Harjoitustyö 2020 
-- Marika Lähteenmäki, Jani Mäkelä, Otto Thitz 
-- Tietokannan luontilauseet 
CREATE TABLE Contractor (
    contractor_id SERIAL,
    contractor_name VARCHAR (60) NOT NULL,
    company_name VARCHAR (60) NOT NULL,
    industry VARCHAR (60),
    PRIMARY KEY (contractor_id)
);

CREATE TABLE Customer (
    customer_id SERIAL,
    contractor_id BIGINT NOT NULL,
    customer_name VARCHAR (60),
    customer_address VARCHAR (60),
    bool_tax_credit BOOLEAN,
    PRIMARY KEY (customer_id),
    FOREIGN KEY (contractor_id) REFERENCES Contractor
);

CREATE TABLE Tax_credit_for_household (
    tax_credit_id SERIAL,
    customer_id BIGINT NOT NULL,
    billing_date DATE,
    credit_amount NUMERIC(10,2),
    tax_credit_notes VARCHAR,
    PRIMARY KEY (tax_credit_id),
    FOREIGN KEY (customer_id) REFERENCES Customer
);

CREATE TABLE Project (
    project_id SERIAL,
    customer_id BIGINT NOT NULL,
    project_name VARCHAR (60),
    project_address VARCHAR (60),
    bool_tax_credit BOOLEAN,
    PRIMARY KEY (project_id),
    FOREIGN KEY (customer_id) REFERENCES Customer
);

CREATE TABLE Contract_type (
    contract_type_id SERIAL,
    contract_type_name VARCHAR (60),
    amount_of_payments INT,
    PRIMARY KEY (contract_type_id) 
);

CREATE TABLE Contract (
    contract_id SERIAL,
    project_id BIGINT NOT NULL,
    contract_type_id BIGINT NOT NULL,
    bool_in_use BOOLEAN,
    PRIMARY KEY (contract_id),
    FOREIGN KEY (project_id) REFERENCES Project,
    FOREIGN KEY (contract_type_id) REFERENCES Contract_type
);

CREATE TABLE Bill_status (
    bill_status_id SERIAL,
    bill_status_name VARCHAR (60),
    status_notes VARCHAR,
    PRIMARY KEY (bill_status_id)
);

CREATE TABLE Bill_type (
    bill_type_id SERIAL,
    bill_type_name VARCHAR,
    handling_fee NUMERIC(10,2),
    PRIMARY KEY (bill_type_id)
);

CREATE TABLE Vat_type (
    vat_type_id SERIAL,
    vat_type_name VARCHAR(60),
    vat_rate INT NOT NULL,
    PRIMARY KEY (vat_type_id)
);

CREATE TABLE Tool (
    tool_id SERIAL,
    tool_name VARCHAR (60),
    tool_purchase_price NUMERIC(10,2) NOT NULL,
    availability BOOLEAN,
    vat_type_id BIGINT NOT NULL,
    tool_selling_price NUMERIC(10,2) NOT NULL,
    unit VARCHAR (10),
    bool_in_use BOOLEAN,
    PRIMARY KEY (tool_id),
    FOREIGN KEY (vat_type_id) REFERENCES Vat_type
);

CREATE TABLE Work_type(
    work_type_id SERIAL,
    work_type_name VARCHAR NOT NULL,
    hourly_rate NUMERIC(10,2),
    vat_type_id BIGINT NOT NULL,
    PRIMARY KEY (work_type_id),
    FOREIGN KEY (vat_type_id) REFERENCES Vat_type
);

CREATE TABLE Bill (
    bill_id SERIAL,
    contract_id BIGINT NOT NULL,
    total_sum NUMERIC(10,2) NOT NULL,
    billing_address VARCHAR (30),
    bill_type_id BIGINT NOT NULL,
    bill_status_id BIGINT NOT NULL,
    date_added DATE,
    date_modified DATE,
    bill_due_date DATE,
    bill_sending_date DATE,
    PRIMARY KEY (bill_id),
    FOREIGN KEY (contract_id) REFERENCES Contract,
    FOREIGN KEY (bill_type_id) REFERENCES Bill_type,
    FOREIGN KEY (bill_status_id) REFERENCES Bill_status
);

CREATE TABLE Billable_hour (
    billable_hour_id SERIAL,
    work_type_id BIGINT NOT NULL,
    bill_id BIGINT NOT NULL,
    date_added DATE NOT NULL,
    quantity INT, 
    sale_percentage INT,
    PRIMARY KEY (billable_hour_id),
    FOREIGN KEY (bill_id) REFERENCES Bill,
    FOREIGN KEY (work_type_id) REFERENCES Work_type
);

CREATE TABLE Sold_tool(
    sold_tool_id SERIAL,
    tool_id BIGINT NOT NULL,
    quantity INT NOT NULL,
    bill_id BIGINT NOT NULL,
    date_added DATE,
    sale_percentage INT,
    PRIMARY KEY (sold_tool_id),
    FOREIGN KEY (tool_id) REFERENCES Tool,
    FOREIGN KEY (bill_id) REFERENCES Bill
);

CREATE TABLE Payment (
    payment_id SERIAL,
    payment_amount NUMERIC(2) NOT NULL,
    bill_id BIGINT NOT NULL,
    payment_date DATE,
    PRIMARY KEY (payment_id),
    FOREIGN KEY (bill_id) REFERENCES Bill
);

-- Test data

INSERT INTO Contractor(contractor_name, company_name, industry VALUES ('Seppo Tärsky', 'Tmi Sähkötärsky', 'sähköala');

INSERT INTO Customer(contractor_id, customer_name, customer_address, bool_tax_credit) VALUES 
(1, 'Tiina Mäkelä', 'Rapakatu 2, Tampere', true),
(1, 'Heli Soininen', 'Taapertajan tie 56, Tampere', true),
(1, 'Pertti Manninen', 'Kaapankatu 7, Tampere', true);

INSERT INTO Project(customer_id, project_name, project_address, bool_tax_credit) VALUES
(1, 'Keittiön sähköasennus', 'Rapakatu 2, Tampere', true),
(2, 'Kylpyhuoneen sähkötyöt', 'Holopaisenkatu 1, Tampere', true),
(2, 'Mökin sähkötyöt', 'Kuusikuja 6', true),
(3, 'Vanhempien sähkösaunan asennus', 'Sahakatu 46, Tampere', true);

INSERT INTO Contract_type(contract_type_name, amount_of_payments) VALUES
('tuntilaskutteinen', null),
('urakka', null),
('urakkatarjous', null);

INSERT INTO Contract(project_id, contract_type_id, bool_in_use) VALUES
(1, 1, true),
(2, 2, true),
(3, 3, false),
(4, 3, true);

INSERT INTO Bill_status(bill_status_name, status_notes) VALUES
('laskuttamatta', null),
('laskutettu', null),
('maksettu', null),
('osa maksettu', null),
('peruttu', null);

INSERT INTO Bill_type(bill_type_name, handling_fee) VALUES
('lasku', 5),
('maksumuistutus', null),
('karhu', null);

INSERT INTO Vat_type(vat_type_name, vat_rate) VALUES
('työ', 24),
('työkalut', 24),
('kirjat', 10);


INSERT INTO Tool(tool_name, tool_purchase_price, availability, vat_type_id, tool_selling_price, unit, bool_in_use) values
('pistorasia', 1.456, true, 2, 2.00, 'kpl', true),
('sähköjohto', 0.52, true, 2, 0.90, 'metri', true),
('opaskirja', 5.00, true, 3, 10.00, 'kpl', true),
('valaisinliitin', 0.15, true, 2, 0.80, 'kpl', true),
('sulake', 1.50, true, 2, 2.00, 'kpl', true);

INSERT INTO Work_type(work_type_name, hourly_rate, vat_type_id) VALUES
('suunnittelu', 55.00, 1),
('työ', 45.00, 1),
('aputyö', 35.00, 1);

INSERT INTO Bill(contract_id, total_sum, billing_address, bill_type_id, bill_status_id, date_added, date_modified, bill_due_date, bill_sending_date) VALUES
(1, 400, 'Rapakatu 2, Tampere', 1, 2, '2019-04-04', null, '2019-04-05', '2019-05-05'),
(1, 400, 'Rapakatu 2, Tampere', 1, 1, '2019-09-20', null, null, null),
(2,2, 'Holopaisenkatu 1, Tampere)',1, 3,'2019-02-18', null, '2019-02-19', '2019-03-20'),
(3,2, 'Holopaisenkatu 1, Tampere', 1, 3,'2019-07-25', null, '2019-07-27', '2019-08-27'),
(4,3, 'Tallikuja 7, Tampere',1, 3, '2019-05-04', null, '2019-05-19', '2019-06-19');

INSERT INTO Billable_hour(work_type_id, bill_id, date_added, quantity, sale_percentage) VALUES
(1,1, '2019-01-19', 5, null),
(2,1, '2018-12-30', 10, null),
(3,3, '2018-09-13', 4, 10);

INSERT INTO Sold_tool(tool_id, quantity, bill_id, date_added, sale_percentage) VALUES
(1, 4, 1, '2019-01-01', null),
(1, 10, 4, '2019-01-01', 10),
(3, 1, 4, '2019-01-01', null),
(5, 2, 5, '2019-01-01', 5),
(2, 11, 3, '2019-01-01', null);

INSERT INTO Payment(payment_amount, bill_id, payment_date) VALUES
(2.00, 3, null),
(2.00, 4, null),
(3.00, 4, null);

-- Muuttaa serial numeroinnin alkamaan arvosta 1
-- (taulu_idnimi_seq)
ALTER SEQUENCE Customer_customer_id_seq RESTART WITH 1;