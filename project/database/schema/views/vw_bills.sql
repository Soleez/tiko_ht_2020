-- Näkymä, jolla haetaan sopimuksiin liittyvät työkalut.

-- Näkymän luonti:
CREATE VIEW vw_bills
AS
    SELECT Contractor.Contractor_id, 
    Customer.customer_name, 
    Customer.customer_id,
    Project.Project_id,
    Project.Project_name,
    Project.Project_address,
    Contract.contract_id, 
    Bill.total_sum,
    Bill.billing_address,
    Bill_status.bill_status_name,
    Bill_type.bill_type_name
    FROM ((((((Contractor JOIN Customer ON Contractor.contractor_id = Customer.contractor_id)
        JOIN Project ON Customer.customer_id = Project.customer_id)
        JOIN Contract ON Project.project_id = Contract.project_id) 
        JOIN Bill ON Contract.contract_id = Bill.contract_id)
        JOIN Bill_status ON Bill.contract_id = Bill_status.bill_status_id)
        JOIN Bill_type ON Bill.contract_id = Bill_type.bill_type_id);    

-- Kysely:
SELECT *
FROM vw_bills;

-- Poisto:
DROP VIEW vw_bills;

-- Tulos nyt:
-- contractor_id | customer_name | customer_id | project_id |      project_name      |      project_address      | contract_id | total_sum |      billing_address       | bill_status_name | bill_type_name
-----------------+---------------+-------------+------------+------------------------+---------------------------+-------------+-----------+----------------------------+------------------+----------------
--             1 | Tiina Mäkelä  |           1 |          1 | Keittiön sähköasennus  | Rapakatu 2, Tampere       |           1 |    400.00 | Rapakatu 2, Tampere        | laskuttamatta    | lasku
--             1 | Tiina Mäkelä  |           1 |          1 | Keittiön sähköasennus  | Rapakatu 2, Tampere       |           1 |    400.00 | Rapakatu 2, Tampere        | laskuttamatta    | lasku
--             1 | Heli Soininen |           2 |          2 | Kylpyhuoneen sähkötyöt | Holopaisenkatu 1, Tampere |           2 |      2.00 | Holopaisenkatu 1, Tampere) | laskutettu       | maksumuistutus
--             1 | Heli Soininen |           2 |          3 | Mökin sähkötyöt        | Kuusikuja 6               |           3 |      2.00 | Holopaisenkatu 1, Tampere  | maksettu         | karhu
--(4 rows)
