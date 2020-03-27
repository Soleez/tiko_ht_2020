-- Näkymä, jolla haetaan sopimuksiin liittyvät työkalut.

-- Näkymän luonti:
CREATE VIEW vw_tools
AS
    SELECT Customer.customer_name, Contract.contract_id, Sold_tool.sold_tool_id, Tool.tool_name, Sold_tool.quantity, 
    Tool.unit, Sold_tool.date_added, Tool.tool_selling_price, Sold_tool.sale_percentage, Vat_type.vat_rate
    FROM Customer, Project, Contract, Sold_tool, Tool, Vat_type
    WHERE Customer.customer_id = Project.customer_id AND Project.project_id = Contract.project_id 
    AND Contract.contract_id = Sold_tool.contract_id AND Sold_tool.tool_id = Tool.tool_id 
    AND Vat_type.vat_type_id = Tool.vat_type_id;
    
-- Kysely:
SELECT *
FROM vw_tools;

-- Poisto:
DROP VIEW vw_tools;

-- Tulos nyt:
--
--  customer_name  | contract_id | sold_tool_id | tool_name  | quantity | unit  | date_added | tool_selling_price | sale_percentage | vat_rate
-------------------+-------------+--------------+------------+----------+-------+------------+--------------------+-----------------+----------
-- Tiina Mäkelä    |           1 |            1 | pistorasia |        4 | kpl   | 2019-01-01 |               2.00 |                 |       24
-- Heli Soininen   |           3 |            2 | pistorasia |       10 | kpl   | 2019-01-01 |               2.00 |              10 |       24
-- Heli Soininen   |           3 |            3 | opaskirja  |        1 | kpl   | 2019-01-01 |              10.00 |                 |       10
-- Pertti Manninen |           4 |            4 | sulake     |        2 | kpl   | 2019-01-01 |               2.00 |               5 |       24
-- Heli Soininen   |           2 |            5 | sähköjohto |       11 | metri | 2019-01-01 |               0.90 |                 |       24