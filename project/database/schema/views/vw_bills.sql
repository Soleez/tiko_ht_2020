-- Näkymä, jolla haetaan laskujen tiedot.

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
    Contract.amount_of_payments,
    Contract_type.contract_type_name,
    Bill.bill_id,
    Bill.total_sum,
    Bill.billing_address,
    Bill_status.bill_status_name,
    Bill_type.bill_type_name
    FROM (((((((Contractor JOIN Customer ON Contractor.contractor_id = Customer.contractor_id)
        LEFT OUTER JOIN Project ON Customer.customer_id = Project.customer_id)
        LEFT OUTER JOIN Contract ON Project.project_id = Contract.project_id) 
        LEFT OUTER JOIN Contract_type ON Contract.contract_type_id = Contract_type.contract_type_id)
        LEFT OUTER JOIN Bill ON Contract.contract_id = Bill.contract_id)
        LEFT OUTER JOIN Bill_status ON Bill.contract_id = Bill_status.bill_status_id)
        LEFT OUTER JOIN Bill_type ON Bill.contract_id = Bill_type.bill_type_id);

-- Kysely:
SELECT *
FROM vw_bills;

-- Poisto:
DROP VIEW vw_bills;