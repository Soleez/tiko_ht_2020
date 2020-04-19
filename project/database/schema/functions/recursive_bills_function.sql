-- laskun rekursiivinen kysely
-- parametri id halutun ekan tason laskun id
create or replace function recursive_bills_function() 
returns table(
    bill_id INT,
    contract_id BIGINT,
    total_sum NUMERIC(10,2),
    billing_address VARCHAR(60),
    bill_status_id INT,
    bill_due_date date,
    previous_bill_id BIGINT,
    handling_fee NUMERIC(10,2),
    bill_number INT,
    tier INT
)
as $suorita$
BEGIN
    return query
    with recursive bl(bill_id, contract_id, total_sum, billing_address, 
        bill_status_id, bill_due_date, previous_bill_id, handling_fee, bill_number,tier) as (
        select b.bill_id, cast(b.contract_id as bigint), b.total_sum, 
            b.billing_address,  b.bill_status_id, b.bill_due_date, 
            b.previous_bill_id, bt.handling_fee, b.bill_number, 1
        from (Bill b 
            join Contract on b.contract_id = Contract.contract_id) 
            join Bill_type bt on bt.bill_type_id = b.bill_type_id
        where contract_type_id = 1 or contract_type_id = 2 
        union
        select b.bill_id, cast(b.contract_id as bigint), b.total_sum, 
            b.billing_address, b.bill_status_id, b.bill_due_date, 
            bl.previous_bill_id, bt.handling_fee, b.bill_number,bl.tier + 1
        from ((Bill b 
            join bl on b.previous_bill_id = bl.bill_id) 
            join Contract on b.contract_id = Contract.contract_id)  
            join Bill_type bt on bt.bill_type_id = b.bill_type_id
        where b.previous_bill_id = bl.bill_id and contract_type_id = 1 or 
            contract_type_id = 2)
    select * from bl
    order by bill_id, tier;
END;
$suorita$
language plpgsql;