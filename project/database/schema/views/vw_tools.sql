-- Näkymä, jolla haetaan sopimuksiin liittyvät työkalut.

-- Näkymän luonti:
CREATE VIEW vw_tools
AS
    SELECT Contractor.Contractor_id, 
    Customer.customer_name, 
    Customer.customer_id,
    Project.Project_id,
    Contract.contract_id, 
    Sold_tool.sold_tool_id, 
    Sold_tool.quantity, 
    Sold_tool.sale_percentage,
    Sold_tool.date_added,
    Tool.tool_name, 
    Tool.unit, 
    Tool.tool_selling_price AS tool_price, -- lyhenne jotta mahtuisi shell näkymään paremmin, 
    Vat_type.vat_rate
    FROM ((((((Contractor JOIN Customer ON Contractor.contractor_id = Customer.contractor_id)
        JOIN Project ON Customer.customer_id = Project.customer_id)
        JOIN Contract ON Project.project_id = Contract.project_id) 
        JOIN Sold_tool ON Contract.contract_id = Sold_tool.contract_id)
        JOIN Tool ON Sold_tool.tool_id = Tool.tool_id)
        JOIN Vat_type ON Vat_type.vat_type_id = Tool.vat_type_id);    

-- Kysely:
SELECT *
FROM vw_tools;

-- Poisto:
DROP VIEW vw_tools;

-- Tulos nyt:
-- contractor_id |  customer_name  | customer_id | project_id | contract_id | sold_tool_id | quantity | sale_percentage | date_added | tool_name  | unit  | tool_price | vat_rate
-----------------+-----------------+-------------+------------+-------------+--------------+----------+-----------------+------------+------------+-------+------------+----------
--             1 | Tiina Mäkelä    |           1 |          1 |           1 |            1 |        4 |                 | 2019-01-01 | pistorasia | kpl   |       2.00 |       24
--             1 | Heli Soininen   |           2 |          3 |           3 |            2 |       10 |              10 | 2019-01-01 | pistorasia | kpl   |       2.00 |       24
--             1 | Heli Soininen   |           2 |          3 |           3 |            3 |        1 |                 | 2019-01-01 | opaskirja  | kpl   |      10.00 |       10
--             1 | Pertti Manninen |           3 |          4 |           4 |            4 |        2 |               5 | 2019-01-01 | sulake     | kpl   |       2.00 |       24
--             1 | Heli Soininen   |           2 |          2 |           2 |            5 |       11 |                 | 2019-01-01 | sähköjohto | metri |       0.90 |       24
