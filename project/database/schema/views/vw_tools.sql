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
        