-- Tietokantaohjelmointi Harjoitustyö 2020 
-- Marika Lähteenmäki, Jani Mäkelä, Otto Thitz 
-- Tietokannan luontilauseet 
CREATE TABLE Contractor (
    contractor_id SERIAL,
    contractor_name VARCHAR (30) NOT NULL,
    company_name VARCHAR (30) NOT NULL,
    industry VARCHAR (30) NOT NULL,
    PRIMARY KEY (contractor_id)
);

CREATE TABLE Customer (
    customer_id SERIAL,
    contractor_id SERIAL NOT NULL,
    customer_name VARCHAR (30) NOT NULL,
    customer_address VARCHAR (30) NOT NULL,
    bool_tax_credit BOOLEAN,
    PRIMARY KEY (customer_id),
    FOREIGN KEY (contractor_id) REFERENCES Contractor
);

CREATE TABLE Tax_credit_for_household (
    tax_credit_id SERIAL,
    customer_id SERIAL NOT NULL,
    tax_year INT NOT NULL,
    credit_amount INT NOT NULL,
    PRIMARY KEY (tax_credit_id),
    FOREIGN KEY (customer_id) REFERENCES Customer
);

CREATE TABLE Project (
    project_id SERIAL,
    customer_id SERIAL NOT NULL,
    project_name VARCHAR (20) NOT NULL,
    project_address VARCHAR (30) NOT NULL,
    bool_tax_credit BOOLEAN NOT NULL,
    PRIMARY KEY (project_id),
    FOREIGN KEY (customer_id) REFERENCES Customer
);

CREATE TABLE Contract_type (
    contract_type_id SERIAL,
    contract_type_name VARCHAR (20) NOT NULL,
    amount_of_payments INT NOT NULL,
    PRIMARY KEY (contract_type_id) 
);

CREATE TABLE Contract (
    contract_id SERIAL,
    project_id SERIAL NOT NULL,
    contract_type_id SERIAL NOT NULL,
    PRIMARY KEY (contract_id),
    FOREIGN KEY (project_id) REFERENCES Project,
    FOREIGN KEY (contract_type_id) REFERENCES Contract_type
);

CREATE TABLE Bill_status (
    bill_status_id SERIAL,
    bill_status_name VARCHAR (20) NOT NULL,
    status_notes VARCHAR,
    PRIMARY KEY (bill_status_id)
);

CREATE TABLE Bill_type (
    bill_type_id SERIAL,
    bill_type_name VARCHAR NOT NULL,
    handling_fee INT NOT NULL,
    PRIMARY KEY (bill_type_id)
);

CREATE TABLE Vat_type (
    vat_type_id SERIAL,
    vat_type_name VARCHAR NOT NULL,
    vat_rate INT NOT NULL,
    PRIMARY KEY (vat_type_id)
);

CREATE TABLE Tool (
    tool_id SERIAL,
    tool_name VARCHAR (30) NOT NULL,
    tool_purchase_price INT NOT NULL,
    availability BOOLEAN,
    vat_type_id SERIAL NOT NULL,
    tool_selling_price INT NOT NULL,
    unit VARCHAR (10),
    PRIMARY KEY (tool_id),
    FOREIGN KEY (vat_type_id) REFERENCES Vat_type
);

CREATE TABLE Work_type(
    work_type_id SERIAL,
    work_type_name VARCHAR NOT NULL,
    hourly_rate INT,
    vat_type_id SERIAL NOT NULL,
    PRIMARY KEY (work_type_id),
    FOREIGN KEY (vat_type_id) REFERENCES Vat_type
);

CREATE TABLE Bill (
    bill_id SERIAL,
    contract_id SERIAL NOT NULL,
    total_sum INT NOT NULL,
    billing_address VARCHAR (30),
    bill_type_id SERIAL NOT NULL,
    bill_status_id SERIAL NOT NULL,
    date_added DATE,
    date_modified DATE,
    bill_due_date DATE NOT NULL,
    bill_sending_date DATE NOT NULL,
    PRIMARY KEY (bill_id),
    FOREIGN KEY (contract_id) REFERENCES Contract,
    FOREIGN KEY (bill_type_id) REFERENCES Bill_type,
    FOREIGN KEY (bill_status_id) REFERENCES Bill_status
);

CREATE TABLE Billable_hour (
    billable_hour_id SERIAL,
    work_type_id SERIAL NOT NULL,
    bill_id SERIAL NOT NULL,
    date_added DATE NOT NULL,
    quantity INT, 
    sale_percentage INT,
    PRIMARY KEY (billable_hour_id),
    FOREIGN KEY (bill_id) REFERENCES Bill,
    FOREIGN KEY (work_type_id) REFERENCES Work_type
);

CREATE TABLE Sold_tool(
    sold_tool_id SERIAL,
    tool_id SERIAL NOT NULL,
    quantity int NOT NULL,
    bill_id SERIAL NOT NULL,
    date_added DATE,
    sale_percentage INT,
    PRIMARY KEY (sold_tool_id),
    FOREIGN KEY (tool_id) REFERENCES Tool,
    FOREIGN KEY (bill_id) REFERENCES Bill
);

CREATE TABLE Payment (
    payment_id SERIAL,
    payment_amount INT NOT NULL,
    bill_id SERIAL NOT NULL,
    payment_date DATE NOT NULL,
    PRIMARY KEY (payment_id),
    FOREIGN KEY (bill_id) REFERENCES Bill
);