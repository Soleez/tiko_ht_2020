-- Näkymä, jolla haetaan sopimuksiin liittyvät työtunnit.

-- Näkymän luonti:
CREATE VIEW vw_hours
AS
    SELECT Contractor.Contractor_id, 
    Customer.customer_name, 
    Customer.customer_id, 
    Project.Project_id,
    Project.Project_name,
    Contract.contract_id, 
    Billable_hour.billable_hour_id AS bh_id, -- lyhenne jotta mahtuisi shell näkymään paremmin
    Billable_hour.quantity, 
    Billable_hour.date_added,
    CAST (Billable_hour.sale_percentage AS NUMERIC(10,2)), 
    Work_type.work_type_name, 
    Work_type.hourly_rate, 
    CAST (Vat_type.vat_rate AS NUMERIC(10,2)),
    CAST (Billable_hour.quantity * Work_type.hourly_rate AS NUMERIC(10,2)) AS total_before_sale, --alkuperäinen hinta veron kanssa ennen alennusta
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
SELECT * FROM vw_hours;

-- Poisto:
DROP VIEW vw_hours;