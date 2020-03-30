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
    CAST (Billable_hour.sale_percentage AS NUMERIC(10,2)), 
    Work_type.work_type_name, 
    Work_type.hourly_rate, 
    CAST (Vat_type.vat_rate AS NUMERIC(10,2)),
    CAST ((Billable_hour.quantity * Work_type.hourly_rate * (Vat_type.vat_rate/100.00)) AS NUMERIC(10,2)) AS tax_only,   --alvin määrä
    CAST ((Billable_hour.quantity * Work_type.hourly_rate * ((100.00 - Vat_type.vat_rate)/100.00) * ((100.00 - Billable_hour.sale_percentage)/100.00)) AS NUMERIC(10,2)) AS price_wo_tax_w_sale,  --ilman alvia, alennus mukana
    CAST ((Billable_hour.quantity * Work_type.hourly_rate * (Vat_type.vat_rate/100.00)) AS NUMERIC(10,2)) + CAST ((Billable_hour.quantity * Work_type.hourly_rate * ((100.00 - Vat_type.vat_rate)/100.00) * ((100.00 - Billable_hour.sale_percentage)/100.00)) AS NUMERIC(10,2)) AS total_sum
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

--| Tulos nyt:
-- contractor_id |  customer_name  | customer_id | project_id | contract_id | bh_id | quantity | date_added | sale_percentage | work_type_name | hourly_rate | vat_rate | tax_only | price_wo_tax_w_sale | total_sum
-----------------+-----------------+-------------+------------+-------------+-------+----------+------------+-----------------+----------------+-------------+----------+----------+---------------------+-----------
--             1 | Tiina Mäkelä    |           1 |          1 |           1 |     1 |        5 | 2019-01-19 |            0.00 | suunnittelu    |       55.00 |    24.00 |    66.00 |              209.00 |    275.00
--             1 | Tiina Mäkelä    |           1 |          1 |           1 |     2 |       10 | 2018-12-30 |            0.00 | työ            |       45.00 |    24.00 |   108.00 |              342.00 |    450.00
--             1 | Heli Soininen   |           2 |          2 |           2 |     3 |        4 | 2018-09-13 |           10.00 | aputyö         |       35.00 |    24.00 |    33.60 |               95.76 |    129.36
--             1 | Pertti Manninen |           3 |          5 |           5 |     4 |        3 | 2020-03-29 |            0.00 | suunnittelu    |       55.00 |    24.00 |    39.60 |              125.40 |    165.00
--             1 | Pertti Manninen |           3 |          5 |           5 |     5 |       12 | 2020-03-29 |            0.00 | työ            |       45.00 |    24.00 |   129.60 |              410.40 |    540.00
