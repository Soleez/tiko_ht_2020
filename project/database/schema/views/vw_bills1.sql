CREATE OR REPLACE VIEW vw_bills1
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
    Bill.bill_due_date,
    Bill.bill_sending_date,
    Bill_status.bill_status_id,
    Bill_status.bill_status_name,
    Bill_type.bill_type_id,
    Bill_type.bill_type_name,
    toolsum_wo_discount_function(Contract.contract_id) AS toolsum_wo_discount,
    tool_tax_sum_function(Contract.contract_id) AS tool_tax_sum,
    worksum_wo_discount_function(Contract.contract_id) AS worksum_wo_discount,
    work_tax_sum_function(Contract.contract_id) AS work_tax_sum,
    toolsum_function(Contract.contract_id) AS toolsum,
    worksum_function(Contract.contract_id) AS worksum
    FROM bill
    LEFT OUTER JOIN contract 
        ON contract.contract_id = Bill.contract_id
    LEFT OUTER JOIN project
        ON project.project_id = contract.project_id
    LEFT OUTER JOIN customer
        ON customer.customer_id = project.customer_id
    INNER JOIN contractor
        ON contractor.contractor_id = customer.contractor_id
    LEFT OUTER JOIN bill_status
        ON bill_status.bill_status_id = bill.bill_status_id
    LEFT OUTER JOIN bill_type
        ON bill_type.bill_type_id = bill.bill_type_id
    LEFT OUTER JOIN contract_type
        ON contract_type.contract_type_id = contract.contract_type_id;
