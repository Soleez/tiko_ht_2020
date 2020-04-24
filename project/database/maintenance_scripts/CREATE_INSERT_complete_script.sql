-- tämä skripti sisältää kaikki tarvittavat taulujen create-lauseet, näkymien create-lauseet, funktioiden luontilauseet ja rivien insert-lauseet

-- taulujen luontilauseet:

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
    amount_of_payments INT default 1,
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
    bill_number INT default 1,
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

-- näkymien luonti-/korvauslauseet:

CREATE OR REPLACE VIEW vw_bills
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
    Bill.bill_due_date,
    Bill.bill_sending_date,
    Bill_status.bill_status_id,
    Bill_status.bill_status_name,
    Bill_type.bill_type_id,
    Bill_type.bill_type_name
    FROM bill
    LEFT OUTER JOIN contract 
        ON contract.contract_id = Bill.contract_id
    LEFT OUTER JOIN project
        ON project.project_id = contract.project_id
    LEFT OUTER JOIN customer
        ON customer.customer_id = project.customer_id
    LEFT OUTER JOIN contractor
        ON contractor.contractor_id = customer.contractor_id
    LEFT OUTER JOIN bill_status
        ON bill_status.bill_status_id = bill.bill_status_id
    LEFT OUTER JOIN bill_type
        ON bill_type.bill_type_id = bill.bill_type_id
    LEFT OUTER JOIN contract_type
        ON contract_type.contract_type_id = contract.contract_type_id;

CREATE OR REPLACE VIEW vw_hours
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

CREATE OR REPLACE VIEW vw_tools
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
        
-- funktioiden luonti-/korvauslauseet:

create or replace function count_penalty_interest_function(due_date date)
returns numeric(10,2)
as $suorita$
declare
    days numeric(10,2) := current_date - CAST(due_date as date);
begin

    return days * 0.16/365;
end;
$suorita$
language plpgsql;

-- laskun rekursiivinen kysely
-- parametri id halutun ekan tason laskun id
create or replace function recursive_bills_function() 
returns table(
    bill_id INT,
    contract_id BIGINT,
    total_sum NUMERIC(10,2),
    billing_address VARCHAR(60),
    bill_status_id INT,
    bill_due_date date,
    previous_bill_id BIGINT,
    handling_fee NUMERIC(10,2),
    bill_number INT,
    tier INT
)
as $suorita$
BEGIN
    return query
    with recursive bl(bill_id, contract_id, total_sum, billing_address, 
        bill_status_id, bill_due_date, previous_bill_id, handling_fee, bill_number,tier) as (
        select b.bill_id, cast(b.contract_id as bigint), b.total_sum, 
            b.billing_address,  b.bill_status_id, b.bill_due_date, 
            b.previous_bill_id, bt.handling_fee, b.bill_number, 1
        from (Bill b 
            join Contract on b.contract_id = Contract.contract_id) 
            join Bill_type bt on bt.bill_type_id = b.bill_type_id
        where contract_type_id = 1 or contract_type_id = 2 
        union
        select b.bill_id, cast(b.contract_id as bigint), b.total_sum, 
            b.billing_address, b.bill_status_id, b.bill_due_date, 
            bl.previous_bill_id, bt.handling_fee, b.bill_number,bl.tier + 1
        from ((Bill b 
            join bl on b.previous_bill_id = bl.bill_id) 
            join Contract on b.contract_id = Contract.contract_id)  
            join Bill_type bt on bt.bill_type_id = b.bill_type_id
        where b.previous_bill_id = bl.bill_id and contract_type_id = 1 or 
            contract_type_id = 2)
    select * from bl
    order by bill_id, tier;
END;
$suorita$
language plpgsql;

-- työkalujen loppusumma sopimuksella c_id, aikaväliltä date1-date2
CREATE OR REPLACE FUNCTION tool_tax_sum_function(c_id bigint) RETURNS numeric(10,2)
AS $suorita$
BEGIN
    return(select round(sum((quantity*tool_selling_price*vat_rate)/100),2)
    from Sold_tool join Tool on Sold_tool.tool_id = Tool.tool_id join Vat_type on Tool.vat_type_id=Vat_type.vat_type_id
    where Sold_tool.contract_id = c_id );
END;
$suorita$
language plpgsql;

-- työkalujen loppusumma sopimuksella c_id, aikaväliltä date1-date2
CREATE OR REPLACE FUNCTION toolsum_function(c_id bigint) RETURNS numeric(10,2)
AS $suorita$
BEGIN
    return(select round(sum(quantity*tool_selling_price - (quantity*tool_selling_price*(cast(100-vat_rate as numeric(10,2))/100)*(cast(sale_percentage as numeric(10,2))/100))),2)
    from Sold_tool join Tool on Sold_tool.tool_id = Tool.tool_id join Vat_type on Tool.vat_type_id=Vat_type.vat_type_id
    where Sold_tool.contract_id = c_id );
END;
$suorita$
language plpgsql;

-- työkalujen loppusumma sopimuksella c_id, aikaväliltä date1-date2
CREATE OR REPLACE FUNCTION toolsum_wo_discount_function(c_id bigint) RETURNS numeric(10,2)
AS $suorita$
BEGIN
    return(select round(sum(quantity*tool_selling_price),2)
    from Sold_tool join Tool on Sold_tool.tool_id = Tool.tool_id join Vat_type on Tool.vat_type_id=Vat_type.vat_type_id
    where Sold_tool.contract_id = c_id );
END;
$suorita$
language plpgsql;

-- töiden loppusumma sopimuksella c_id, aikaväliltä date1-date2
CREATE OR REPLACE FUNCTION work_tax_sum_function(c_id bigint) RETURNS numeric(10,2)
AS $suorita$
BEGIN
    return(select round(sum((quantity*hourly_rate*vat_rate)/100),2)
    from Billable_hour join Work_type on Billable_hour.work_type_id = Work_type.work_type_id join Vat_type on Work_type.vat_type_id=Vat_type.vat_type_id
    where Billable_hour.contract_id = c_id );
END;
$suorita$
language plpgsql;

-- töiden loppusumma sopimuksella c_id, aikaväliltä date1-date2
CREATE OR REPLACE FUNCTION worksum_function(c_id bigint) RETURNS numeric(10,2)
AS $suorita$
BEGIN
    return(select round(sum(quantity*hourly_rate - (quantity*hourly_rate*(cast(100-vat_rate as numeric(10,2))/100)*(cast(sale_percentage as numeric(10,2)) /100))),2)
    from Billable_hour join Work_type on Billable_hour.work_type_id = Work_type.work_type_id join Vat_type on Work_type.vat_type_id=Vat_type.vat_type_id
    where Billable_hour.contract_id = c_id );
END;
$suorita$
language plpgsql;

-- töiden loppusumma sopimuksella c_id, aikaväliltä date1-date2
CREATE OR REPLACE FUNCTION worksum_wo_discount_function(c_id bigint) RETURNS numeric(10,2)
AS $suorita$
BEGIN
    return(select round(sum(quantity*hourly_rate),2)
    from Billable_hour join Work_type on Billable_hour.work_type_id = Work_type.work_type_id
    where Billable_hour.contract_id = c_id );
END;
$suorita$
language plpgsql;

-- insert-lauseet:
INSERT INTO Contractor(contractor_name, company_name, industry) VALUES
('Seppo Tärsky', 'Tmi Sähkötärsky', 'sähköala');

INSERT INTO Customer(contractor_id, customer_name, customer_address, bool_tax_credit) VALUES 
(1, 'Tiina Mäkelä', 'Rapakatu 2, Tampere', true),
(1, 'Heli Soininen', 'Taapertajantie 56, Tampere', true),
(1, 'Pertti Manninen', 'Kaapankatu 7, Tampere', true),
(1, 'Tarja Toivonen', 'Hallituskatu 56, Tampere', true),
(1, 'Reijo Ristola', 'Laitostie 245, Tampere', true),
(1, 'Kenkä Oy', 'Saapaskuja 23, Tampere', false),
(1, 'Fanni Forss', 'Ruotsalaisentie 142, Tampere', true),
(1, 'Heikki Halminen', 'Satamakatu 3, Tampere', true);

INSERT INTO Project(customer_id, project_name, project_address, bool_tax_credit) VALUES
(1, 'Autotallin sähköt (R1)', 'Rapakatu 2, Tampere', true),
(2, 'Mökin sähkötyöt (R2)', 'Kuusikuja 6', true),
(2, 'Mökin sähkötyöt (R3)', 'Kuusikuja 6', true),
(3, 'Keittiön sähköasennus (R4)', 'Kaapankatu 7, Tampere', true),
(4, 'Keittiön sähkötyöt (T4)', 'Hallituskatu 56, Tampere', true),
(5, 'Kiukaan asennus (T4)', 'Laitostie 245, Tampere', true),
(6, 'Myymälän sähkötyöt (T4)', 'Keskuskatu 23, Tampere', false),
(7, 'Pihan valaistuksen sähkötyöt (T3)', 'Ruotsalaisentie 142, Tampere', true),
(8, 'Keittiö- ja pesutilaremontin sähkötyöt (T3)', 'Satamakatu 3, Tampere', true),
(3, 'Keittiön sähköasennus (R5)', 'Kaapankatu 7, Tampere', true);

INSERT INTO Contract_type(contract_type_id, contract_type_name) VALUES
(1, 'tuntilaskutteinen'),
(2, 'urakka'),
(3, 'urakkatarjous'),
(4, 'hinta-arvio');

INSERT INTO Contract(project_id, contract_type_id, bool_in_use, amount_of_payments) VALUES
(1, 4, true, 1),
(2, 1, true, 1),
(3, 1, true, 1),
(4, 3, true, 2),
(5, 2, true, 1),
(6, 1, true, 1),
(7, 2, true, 1),
(8, 1, true, 1),
(9, 2, true, 1),
(10, 3, true, 2);

INSERT INTO Bill_status(bill_status_id, bill_status_name, status_notes) VALUES
(1, 'laskuttamatta', null),
(2, 'laskutettu', null),
(3, 'maksettu', null),
(4, 'osa maksettu', null),
(5, 'peruttu', null);

INSERT INTO Bill_type(bill_type_id, bill_type_name, handling_fee) VALUES
(1, 'lasku', 5),
(2, 'maksumuistutus', 5),
(3, 'karhu', 5);

INSERT INTO Vat_type(vat_type_id, vat_type_name, vat_rate) VALUES
(1, 'työ', 24),
(2, 'työkalut', 24),
(3, 'kirjat', 10);

INSERT INTO Tool(tool_name, tool_purchase_price, availability, vat_type_id, tool_selling_price, unit, bool_in_use) VALUES
('pistorasia', 1.456, 20, 2, 2.00, 'kpl', true),
('sähköjohto', 0.52, 50, 2, 0.90, 'm', true),
('opaskirja', 5.00, 5, 3, 10.00, 'kpl', true),
('valaisinliitin', 0.15, 5, 2, 0.80, 'kpl', true),
('sulake', 1.50, 200, 2, 2.00, 'kpl', true),
('valaisinpistorasia', 3.50, 10, 2, 6.95, 'kpl', true),
('valonsäädin', 21.55, 5, 2, 38.00, 'kpl', true),
('kytkin', 1.20, 150, 2, 2.50, 'kpl', true);

INSERT INTO Work_type(work_type_id, work_type_name, hourly_rate, vat_type_id) VALUES
(1, 'suunnittelu', 55.00, 1),
(2, 'asennustyö', 45.00, 1),
(3, 'aputyö', 35.00, 1);

INSERT INTO Bill(contract_id, total_sum, billing_address, bill_type_id, bill_status_id, date_added, date_modified, bill_due_date, bill_sending_date, previous_bill_id, bill_number) VALUES
(1, null, 'Rapakatu 2, Tampere', 1, 1, '2020-04-10', null, null, null, null, default),
(2, null, 'Taapertajantie 56, Tampere', 1, 1,'2020-01-18', null, null, null, null, default),
(3, null, 'Taapertajantie 56, Tampere', 1, 1,'2020-01-18', null, null, null, null, default),
(4, null, 'Kaapankatu 7, Tampere', 1, 1, '2020-03-15', null, null, null, null, default),
(5, 852, 'Hallituskatu 56, Tampere', 1, 2, null, null, '2019-05-04', '2019-04-03', null, default),
(6, 138.80, 'Laitostie 245, Tampere', 1, 2, null, null, '2019-08-19', '2019-07-17', null, default),
(7, 1195.60, 'Saapaskuja 23, Tampere', 1, 2, null, null, '2019-07-20', '2019-06-19', null, default),
(8, 386.40, 'Ruotsalaisentie 142, Tampere', 1, 2, null, null, '2020-04-15', '2020-03-14', null, default), 
(9, 721.40, 'Satamakatu 3, Tampere', 1, 2, null, null, '2020-05-30', '2020-04-30', null, default),
(7, 1200.60, 'Saapaskuja 23, Tampere', 2, 2, null, null, '2019-08-26', '2019-07-25', 7, 2),
(8, 391.40, 'Ruotsalaisentie 142, Tampere', 2, 2, null, null, '2020-05-20', '2020-04-19', 8, 2),
(5, 857, 'Hallituskatu 56, Tampere', 2, 2, null, null, '2019-06-10', '2019-05-10', 5, 2),
(10, null, 'Kaapankatu 7, Tampere', 1, 1, '2020-03-15', null, null, null, null, default);

INSERT INTO Billable_hour(work_type_id, contract_id, date_added, quantity, sale_percentage) VALUES
(1, 1, '2020-04-10', 3, default),
(2, 1, '2020-04-10', 12, default),
(1, 2, '2020-03-20', 5, default),
(1, 3, '2020-03-20', 5, 10),
(2, 2, '2020-04-01', 10, default),
(2, 3, '2020-04-01', 10, default),
(1, 4, '2020-03-15', 5, 10),
(2, 4, '2020-03-15', 20, 10),
(1, 5, '2019-03-12', 3, default),
(2, 5, '2019-04-01', 6, default),
(2, 5, '2019-04-02', 5, default),
(3, 5, '2019-04-02', 3, default),
(2, 6, '2019-07-16', 3, default),
(1, 7, '2019-04-10', 5, default),
(2, 7, '2019-06-17', 8, default),
(2, 7, '2019-06-18', 8, default),
(1, 8, '2020-01-23', 2, default),
(2, 8, '2020-01-25', 5, default),
(1, 9, '2020-04-10', 2, default),
(2, 9, '2020-04-30', 6, default),
(2, 9, '2020-04-30', 5, default),
(3, 9, '2020-04-30', 3, default),
(1, 10, '2020-03-15', 5, 10),
(2, 10, '2020-03-15', 20, 10);

INSERT INTO Sold_tool(tool_id, quantity, contract_id, date_added, sale_percentage) VALUES
(1, 1, 1, '2020-04-10', default),
(2, 3, 1, '2020-04-10', default),
(1, 10, 2, '2020-04-01', default),
(1, 10, 3, '2020-04-01', 20),
(2, 20, 2, '2020-04-01', default),
(2, 20, 3, '2020-04-01', 10),
(5, 6, 2, '2020-04-01', default),
(5, 6, 3, '2020-04-01', 20),
(3, 1, 3, '2020-04-05', default),
(2, 25, 4, '2020-03-15', default),
(4, 16, 4, '2020-03-15', default),
(7, 2, 4, '2020-03-15', default),
(1, 2, 5, '2019-04-01', default),
(2, 6, 5, '2019-04-01', default),
(4, 2, 5, '2019-04-01', default),
(7, 2, 5, '2019-04-01', default),
(1, 1, 6, '2019-07-16', default),
(2, 2, 6, '2019-07-16', default),
(7, 5, 7, '2019-06-17', default),
(4, 2, 7, '2019-06-17', default),
(2, 10, 7,'2019-06-17', default),
(4, 7, 8, '2020-01-25', default),
(2, 20, 8, '2020-01-25', default),
(6, 4, 8, '2020-01-25', default),
(2, 10, 9, '2020-04-30', default),
(4, 3, 9, '2020-04-30', default),
(2, 25, 10, '2020-03-15', default),
(4, 16, 10, '2020-03-15', default),
(7, 2, 10, '2020-03-15', default);