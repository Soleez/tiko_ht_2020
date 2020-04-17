-- Tietokantaohjelmointi 2020 Harjoitustyö
-- Marika Lähteenmäki, Jani Mäkelä, Otto Thitz
-- Testidata

-- R1 contract_id = 1; R2 contract_id = 2, R3 contract_id = 3, R4 contract_id = 4, R5 voi käyttää R4:sta 

INSERT INTO Contractor(contractor_name, company_name, industry) VALUES
('Seppo Tärsky', 'Tmi Sähkötärsky', 'sähköala');

INSERT INTO Customer(contractor_id, customer_name, customer_address, bool_tax_credit) VALUES 
(1, 'Tiina Mäkelä', 'Rapakatu 2, Tampere', true),
(1, 'Heli Soininen', 'Taapertajantie 56, Tampere', true),
(1, 'Pertti Manninen', 'Kaapankatu 7, Tampere', true);

INSERT INTO Project(customer_id, project_name, project_address, bool_tax_credit) VALUES
(1, 'Autotallin sähköt (R1)', 'Rapakatu 2, Tampere', true),
(2, 'Mökin sähkötyöt (R2)', 'Kuusikuja 6', true),
(2, 'Mökin sähkötyöt (R3)', 'Kuusikuja 6', true),
(3, 'Keittiön sähköasennus (R4)', 'Kaapankatu 7, Tampere', true);

INSERT INTO Contract_type(contract_type_id, contract_type_name) VALUES
(1, 'tuntilaskutteinen'),
(2, 'urakka'),
(3, 'urakkatarjous'),
(4, 'hinta-arvio');

INSERT INTO Contract(project_id, contract_type_id, bool_in_use, amount_of_payments) VALUES
(1, 4, true, null),
(2, 1, true, null),
(3, 1, true, null),
(4, 3, true, null);

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

INSERT INTO Bill(contract_id, total_sum, billing_address, bill_type_id, bill_status_id, date_added, date_modified, bill_due_date, bill_sending_date, previous_bill_id) VALUES
(1, null, 'Rapakatu 2, Tampere', 1, 1, '2020-04-10', null, null, null, null),
(2, null, 'Taapertajantie 56, Tampere', 1, 1,'2020-01-18', null, null, null, null),
(3, null, 'Taapertajantie 56, Tampere', 1, 1,'2020-01-18', null, null, null, null),
(4, null, 'Kaapankatu 7, Tampere', 1, 1, '2020-03-15', null, null, null, null);

INSERT INTO Billable_hour(work_type_id, contract_id, date_added, quantity, sale_percentage) VALUES
(1, 1, '2020-04-10', 3, default),
(2, 1, '2020-04-10', 12, default),
(1, 2, '2020-03-20', 5, default),
(1, 3, '2020-03-20', 5, 10),
(2, 2, '2020-04-01', 10, default),
(2, 3, '2020-04-01', 10, default),
(1, 4, '2020-03-15', 5, 10),
(2, 4, '2020-03-15', 20, 10);

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
(7, 2, 4, '2020-03-15', default);

--INSERT INTO Payment(payment_amount, bill_id, payment_date) VALUES