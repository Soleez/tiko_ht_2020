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
    customer_name VARCHAR (60) NOT NULL,
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
    tax_credit_notes VARCHAR (300),
    PRIMARY KEY (tax_credit_id),
    FOREIGN KEY (customer_id) REFERENCES Customer
);

CREATE TABLE Project (
    project_id SERIAL,
    customer_id BIGINT NOT NULL,
    project_name VARCHAR (60) NOT NULL,
    project_address VARCHAR (60),
    bool_tax_credit BOOLEAN,
    PRIMARY KEY (project_id),
    FOREIGN KEY (customer_id) REFERENCES Customer
);

CREATE TABLE Contract_type (
    contract_type_id INT NOT NULL,
    contract_type_name VARCHAR (60) NOT NULL,
    PRIMARY KEY (contract_type_id) 
);

CREATE TABLE Contract (
    contract_id SERIAL,
    project_id BIGINT NOT NULL,
    contract_type_id INT NOT NULL,
    bool_in_use BOOLEAN,
    amount_of_payments INT,
    PRIMARY KEY (contract_id),
    FOREIGN KEY (project_id) REFERENCES Project,
    FOREIGN KEY (contract_type_id) REFERENCES Contract_type
);

CREATE TABLE Bill_status (
    bill_status_id INT NOT NULL,
    bill_status_name VARCHAR (60) NOT NULL,
    status_notes VARCHAR (300),
    PRIMARY KEY (bill_status_id)
);

CREATE TABLE Bill_type (
    bill_type_id INT NOT NULL,
    bill_type_name VARCHAR (60) NOT NULL,
    handling_fee NUMERIC(10,2),
    PRIMARY KEY (bill_type_id)
);

CREATE TABLE Vat_type (
    vat_type_id INT NOT NULL,
    vat_type_name VARCHAR(60) NOT NULL,
    vat_rate INT NOT NULL,
    PRIMARY KEY (vat_type_id)
);

CREATE TABLE Tool (
    tool_id SERIAL,
    tool_name VARCHAR (60) NOT NULL,
    tool_purchase_price NUMERIC(10,2) NOT NULL,
    availability INT,
    vat_type_id INT NOT NULL,
    tool_selling_price NUMERIC(10,2) NOT NULL,
    unit VARCHAR (10),
    bool_in_use BOOLEAN,
    PRIMARY KEY (tool_id),
    FOREIGN KEY (vat_type_id) REFERENCES Vat_type
);

CREATE TABLE Work_type(
    work_type_id INT NOT NULL,
    work_type_name VARCHAR (60) NOT NULL,
    hourly_rate NUMERIC(10,2),
    vat_type_id INT NOT NULL,
    PRIMARY KEY (work_type_id),
    FOREIGN KEY (vat_type_id) REFERENCES Vat_type
);

CREATE TABLE Bill (
    bill_id SERIAL,
    contract_id BIGINT NOT NULL,
    total_sum NUMERIC(10,2),
    billing_address VARCHAR (60) NOT NULL,
    bill_type_id INT NOT NULL default 1,
    bill_status_id INT NOT NULL default 1,
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
    work_type_id INT NOT NULL,
    contract_id BIGINT NOT NULL,
    date_added DATE NOT NULL,
    quantity INT, 
    sale_percentage INT,
    PRIMARY KEY (billable_hour_id),
    FOREIGN KEY (contract_id) REFERENCES Contract,
    FOREIGN KEY (work_type_id) REFERENCES Work_type
);

CREATE TABLE Sold_tool(
    sold_tool_id SERIAL,
    tool_id BIGINT NOT NULL,
    quantity INT NOT NULL,
    contract_id BIGINT NOT NULL,
    date_added DATE,
    sale_percentage INT,
    PRIMARY KEY (sold_tool_id),
    FOREIGN KEY (tool_id) REFERENCES Tool,
    FOREIGN KEY (contract_id) REFERENCES Contract
);

CREATE TABLE Payment (
    payment_id SERIAL,
    payment_amount NUMERIC(10,2) NOT NULL,
    bill_id BIGINT NOT NULL,
    payment_date DATE,
    PRIMARY KEY (payment_id),
    FOREIGN KEY (bill_id) REFERENCES Bill
);
 