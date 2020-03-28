-- Tietokantaohjelmointi 2020 Harjoitustyö
-- Marika Lähteenmäki, Jani Mäkelä, Otto Thitz
-- Testi data

INSERT INTO Contractor(contractor_name, company_name, industry) VALUES
('Seppo Tärsky', 'Tmi Sähkötärsky', 'sähköala');

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

INSERT INTO Bill_type(bill_type_id, bill_type_name, handling_fee) VALUES
(1, 'lasku', 5),
(2, 'maksumuistutus', null),
(3, 'karhu', null);

INSERT INTO Vat_type(vat_type_name, vat_rate) VALUES
('työ', 24),
('työkalut', 24),
('kirjat', 10);

INSERT INTO Tool(tool_name, tool_purchase_price, availability, vat_type_id, tool_selling_price, unit, bool_in_use) VALUES
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

INSERT INTO Billable_hour(work_type_id, contract_id, date_added, quantity, sale_percentage) VALUES
(1, 1, '2019-01-19', 5, null),
(2, 1, '2018-12-30', 10, null),
(3, 2, '2018-09-13', 4, 10);

INSERT INTO Sold_tool(tool_id, quantity, contract_id, date_added, sale_percentage) VALUES
(1, 4, 1, '2019-01-01', null),
(1, 10, 3, '2019-01-01', 10),
(3, 1, 3, '2019-01-01', null),
(5, 2, 4, '2019-01-01', 5),
(2, 11, 2, '2019-01-01', null);

INSERT INTO Payment(payment_amount, bill_id, payment_date) VALUES
(2.00, 3, null),
(2.00, 4, null),
(3.00, 4, null);

-- Muuttaa serial numeroinnin alkamaan arvosta 1
-- (taulu_idnimi_seq)
-- ALTER SEQUENCE Contractor_contractor_id_seq RESTART WITH 1;