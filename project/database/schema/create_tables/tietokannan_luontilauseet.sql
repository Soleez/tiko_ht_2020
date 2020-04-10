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
    previous_bill_id BIGINT,
    PRIMARY KEY (bill_id),
    FOREIGN KEY (contract_id) REFERENCES Contract,
    FOREIGN KEY (bill_type_id) REFERENCES Bill_type,
    FOREIGN KEY (bill_status_id) REFERENCES Bill_status,
    FOREIGN KEY (previous_bill_id) REFERENCES Bill
);

CREATE TABLE Billable_hour (
    billable_hour_id SERIAL,
    work_type_id INT NOT NULL,
    contract_id BIGINT NOT NULL,
    date_added DATE NOT NULL,
    quantity INT not null, 
    sale_percentage INT NOT NULL default 0,
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
    sale_percentage INT NOT NULL default 0,
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

-- Näkymien luontilauseet:
CREATE VIEW vw_bills
AS
    SELECT Contractor.Contractor_id, 
    Customer.customer_name, 
    Customer.customer_id,
    Project.Project_id,
    Project.Project_name,
    Project.Project_address,
    Contract.contract_id, 
    Contract.amount_of_payments,
    Contract_type.contract_type_name,
    Bill.bill_id,
    Bill.total_sum,
    Bill.billing_address,
    Bill_status.bill_status_name,
    Bill_type.bill_type_name
    FROM (((((((Contractor JOIN Customer ON Contractor.contractor_id = Customer.contractor_id)
        LEFT OUTER JOIN Project ON Customer.customer_id = Project.customer_id)
        LEFT OUTER JOIN Contract ON Project.project_id = Contract.project_id) 
        LEFT OUTER JOIN Contract_type ON Contract.contract_type_id = Contract_type.contract_type_id)
        LEFT OUTER JOIN Bill ON Contract.contract_id = Bill.contract_id)
        LEFT OUTER JOIN Bill_status ON Bill.contract_id = Bill_status.bill_status_id)
        LEFT OUTER JOIN Bill_type ON Bill.contract_id = Bill_type.bill_type_id);

CREATE VIEW vw_hours
AS
    SELECT Contractor.Contractor_id, 
    Customer.customer_name, 
    Customer.customer_id, 
    Project.Project_id,
    Project.Project_name,
    Contract.contract_id, 
    Billable_hour.billable_hour_id,
    Billable_hour.quantity, 
    Billable_hour.date_added,
    CAST (Billable_hour.sale_percentage AS NUMERIC(10,2)), 
    Work_type.work_type_name, 
    Work_type.hourly_rate, 
    CAST (Vat_type.vat_rate AS NUMERIC(10,2)),
    CAST (Billable_hour.quantity * Work_type.hourly_rate AS NUMERIC(10,2)) AS total_before_sale, --alkuperäinen hinta veron kanssa ennen alennusta
    CAST ((Billable_hour.quantity * Work_type.hourly_rate * (Vat_type.vat_rate/100.00)) AS NUMERIC(10,2)) AS tax_only,   --alvin määrä
    CAST ((Billable_hour.quantity * Work_type.hourly_rate * ((100.00 - Vat_type.vat_rate)/100.00) * ((100.00 - Billable_hour.sale_percentage)/100.00)) AS NUMERIC(10,2)) AS price_wo_tax_w_sale,  --ilman alvia, alennus mukana
    CAST ((Billable_hour.quantity * Work_type.hourly_rate * (Vat_type.vat_rate/100.00)) AS NUMERIC(10,2)) + CAST ((Billable_hour.quantity * Work_type.hourly_rate * ((100.00 - Vat_type.vat_rate)/100.00) * ((100.00 - Billable_hour.sale_percentage)/100.00)) AS NUMERIC(10,2)) AS total_sum
    FROM ((((((Contractor JOIN Customer ON Contractor.contractor_id = Customer.contractor_id)
        JOIN Project ON Customer.customer_id = Project.customer_id)
        JOIN Contract ON Project.project_id = Contract.project_id)
        JOIN Billable_hour ON Contract.contract_id = Billable_hour.contract_id)
        JOIN Work_type ON Billable_hour.work_type_id = Work_type.work_type_id)
        JOIN Vat_type ON Vat_type.vat_type_id = Work_type.vat_type_id);

CREATE VIEW vw_tools
AS
    SELECT Contractor.Contractor_id, 
    Customer.customer_name, 
    Customer.customer_id,
    Project.Project_id,
    Project.Project_name,
    Contract.contract_id, 
    Sold_tool.sold_tool_id, 
    Sold_tool.quantity, 
    CAST (Sold_tool.sale_percentage AS NUMERIC(10,2)), 
    Sold_tool.date_added,
    Tool.tool_name, 
    Tool.unit, 
    Tool.tool_selling_price,
    CAST (Vat_type.vat_rate AS NUMERIC(10,2)),
    CAST (Sold_tool.quantity * Tool.tool_selling_price  AS NUMERIC(10,2)) AS total_before_sale, --alkuperäinen hinta veron kanssa ennen alennusta
    CAST ((Sold_tool.quantity * Tool.tool_selling_price * (Vat_type.vat_rate/100.00)) AS NUMERIC(10,2)) AS tax_only,   --alvin määrä
    CAST ((Sold_tool.quantity * Tool.tool_selling_price * ((100.00 - Vat_type.vat_rate)/100.00) * ((100.00 - Sold_tool.sale_percentage)/100.00)) AS NUMERIC(10,2)) AS price_wo_tax_w_sale,  --ilman alvia, alennus mukana
    CAST ((Sold_tool.quantity * Tool.tool_selling_price * (Vat_type.vat_rate/100.00)) AS NUMERIC(10,2)) + CAST ((Sold_tool.quantity * Tool.tool_selling_price * ((100.00 - Vat_type.vat_rate)/100.00) * ((100.00 - Sold_tool.sale_percentage)/100.00)) AS NUMERIC(10,2)) AS total_sum
    FROM ((((((Contractor JOIN Customer ON Contractor.contractor_id = Customer.contractor_id)
        JOIN Project ON Customer.customer_id = Project.customer_id)
        JOIN Contract ON Project.project_id = Contract.project_id) 
        JOIN Sold_tool ON Contract.contract_id = Sold_tool.contract_id)
        JOIN Tool ON Sold_tool.tool_id = Tool.tool_id)
        JOIN Vat_type ON Vat_type.vat_type_id = Tool.vat_type_id);