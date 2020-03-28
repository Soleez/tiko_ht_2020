-- Näkymä, jolla haetaan sopimuksiin liittyvät työtunnit.

-- Näkymän luonti:
CREATE VIEW vw_hours
AS
    SELECT Contractor.Contractor_id, 
    Customer.customer_name, 
    Customer.customer_id, 
    Project.Project_id,
    Contract.contract_id, 
    Billable_hour.billable_hour_id AS bh_id, -- lyhenne jotta mahtuisi shell näkymään paremmin
    Billable_hour.quantity, 
    Billable_hour.date_added,
    Billable_hour.sale_percentage, 
    Work_type.work_type_name, 
    Work_type.hourly_rate, 
    Vat_type.vat_rate
    FROM ((((((Contractor JOIN Customer ON Contractor.contractor_id = Customer.contractor_id)
        JOIN Project ON Customer.customer_id = Project.customer_id)
        JOIN Contract ON Project.project_id = Contract.project_id)
        JOIN Billable_hour ON Contract.contract_id = Billable_hour.contract_id)
        JOIN Work_type ON Billable_hour.work_type_id = Work_type.work_type_id)
        JOIN Vat_type ON Vat_type.vat_type_id = Work_type.vat_type_id);
    
-- Kysely:
SELECT *
FROM vw_hours;

-- Poisto:
DROP VIEW vw_hours;

-- Tulos nyt:
-- contractor_id | customer_name | customer_id | project_id | contract_id | bh_id | quantity | date_added | sale_percentage | work_type_name | hourly_rate | vat_rate
-----------------+---------------+-------------+------------+-------------+-------+----------+------------+-----------------+----------------+-------------+----------
--             1 | Tiina Mäkelä  |           1 |          1 |           1 |     1 |        5 | 2019-01-19 |                 | suunnittelu    |       55.00 |       24
--             1 | Tiina Mäkelä  |           1 |          1 |           1 |     2 |       10 | 2018-12-30 |                 | työ            |       45.00 |       24
--             1 | Heli Soininen |           2 |          2 |           2 |     3 |        4 | 2018-09-13 |              10 | aputyö         |       35.00 |       24

