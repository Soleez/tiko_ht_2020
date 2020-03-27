-- Näkymä, jolla haetaan sopimuksiin liittyvät työtunnit.

-- Näkymän luonti:
CREATE VIEW vw_hours
AS
    SELECT Customer.customer_name, Contract.contract_id, Billable_hour.billable_hour_id, Work_type.work_type_name, Billable_hour.quantity, 
    Billable_hour.date_added, Work_type.hourly_rate, Billable_hour.sale_percentage, Vat_type.vat_rate
    FROM Customer, Project, Contract, Billable_hour, Work_type, Vat_type
    WHERE Customer.customer_id = Project.customer_id AND Project.project_id = Contract.project_id 
    AND Contract.contract_id = Billable_hour.contract_id AND Billable_hour.work_type_id = Work_type.work_type_id
    AND Vat_type.vat_type_id = Work_type.vat_type_id;
    
-- Kysely:
SELECT *
FROM vw_hours;

-- Poisto:
DROP VIEW vw_hours;

-- Tulos nyt:
--
-- customer_name | contract_id | billable_hour_id | work_type_name | quantity | date_added | hourly_rate | sale_percentage | vat_rate
-----------------+-------------+------------------+----------------+----------+------------+-------------+-----------------+----------
-- Tiina Mäkelä  |           1 |                1 | suunnittelu    |        5 | 2019-01-19 |       55.00 |                 |       24
-- Tiina Mäkelä  |           1 |                2 | työ            |       10 | 2018-12-30 |       45.00 |                 |       24
-- Heli Soininen |           2 |                3 | aputyö         |        4 | 2018-09-13 |       35.00 |              10 |       24
