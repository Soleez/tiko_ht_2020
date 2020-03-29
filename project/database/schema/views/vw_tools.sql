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
    CAST (Sold_tool.sale_percentage AS NUMERIC(10,2)), 
    Sold_tool.date_added,
    Tool.tool_name, 
    Tool.unit, 
    Tool.tool_selling_price AS tool_price, -- lyhenne jotta mahtuisi shell näkymään paremmin, 
    CAST (Vat_type.vat_rate AS NUMERIC(10,2)),
    CAST ((Sold_tool.quantity * Tool.tool_selling_price * (Vat_type.vat_rate/100.00)) AS NUMERIC(10,2)) AS tax_only,   --alvin määrä
    CAST ((Sold_tool.quantity * Tool.tool_selling_price * ((100.00 - Vat_type.vat_rate)/100.00)) AS NUMERIC(10,2)) AS price_wo_tax_wo_sale,   --ilman alvia, ilman alennusta
    CAST ((Sold_tool.quantity * Tool.tool_selling_price * ((100.00 - Vat_type.vat_rate)/100.00) * ((100.00 - Sold_tool.sale_percentage)/100.00)) AS NUMERIC(10,2)) AS price_wo_tax_w_sale  --ilman alvia, alennus mukana    
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

--| Tulos nyt:
--| contractor_id |  customer_name  | customer_id | project_id | contract_id | sold_tool_id | quantity | sale_percentage | date_added | tool_name  | unit  | tool_price | vat_rate | tax_only | price_wo_tax_wo_sale | price_wo_tax_w_sale
--|---------------+-----------------+-------------+------------+-------------+--------------+----------+-----------------+------------+------------+-------+------------+----------+----------+----------------------+---------------------
--|             1 | Tiina Mäkelä    |           1 |          1 |           1 |            1 |        4 |                 | 2019-01-01 | pistorasia | kpl   |       2.00 |    24.00 |     1.92 |                 6.08 |
--|             1 | Heli Soininen   |           2 |          3 |           3 |            2 |       10 |           10.00 | 2019-01-01 | pistorasia | kpl   |       2.00 |    24.00 |     4.80 |                15.20 |               13.68
--|             1 | Heli Soininen   |           2 |          3 |           3 |            3 |        1 |                 | 2019-01-01 | opaskirja  | kpl   |      10.00 |    10.00 |     1.00 |                 9.00 |
--|             1 | Pertti Manninen |           3 |          4 |           4 |            4 |        2 |            5.00 | 2019-01-01 | sulake     | kpl   |       2.00 |    24.00 |     0.96 |                 3.04 |                2.89
--|             1 | Heli Soininen   |           2 |          2 |           2 |            5 |       11 |                 | 2019-01-01 | sähköjohto | metri |       0.90 |    24.00 |     2.38 |                 7.52 |
--|             1 | Pertti Manninen |           3 |          5 |           5 |            6 |        3 |                 | 2020-03-29 | sähköjohto | metri |       0.90 |    24.00 |     0.65 |                 2.05 |
--|             1 | Pertti Manninen |           3 |          5 |           5 |            7 |        1 |                 | 2020-03-29 | pistorasia | kpl   |       2.00 |    24.00 |     0.48 |                 1.52 |
--|(7 rows)
