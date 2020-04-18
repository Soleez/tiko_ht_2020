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
    tier INT
)
as $suorita$
BEGIN
    return query
    with recursive bl(bill_id, contract_id, total_sum, billing_address, bill_status_id, bill_due_date, previous_bill_id, tier) as (
        select b.bill_id, cast(b.contract_id as bigint), b.total_sum, b.billing_address,  b.bill_status_id, b.bill_due_date, b.previous_bill_id,1
        from Bill b join Contract on b.contract_id = Contract.contract_id 
        where contract_type_id = 1 or contract_type_id = 2 
        union
        select b.bill_id, cast(b.contract_id as bigint), b.total_sum, b.billing_address, b.bill_status_id, b.bill_due_date, bl.previous_bill_id, bl.tier + 1
        from (Bill b join bl on b.previous_bill_id = bl.bill_id) join Contract on b.contract_id = Contract.contract_id
        where b.previous_bill_id = bl.bill_id and contract_type_id = 1 or contract_type_id = 2)
    select * from bl
    order by bill_id, tier;
END;
$suorita$
language plpgsql;

insert into Bill values
(DEFAULT, 1, 300, 'osote1', 1, 2, null, null, '2020-01-20', '2019-11-30'),
(default, 2, 400, 'osote2', 1, 2, null, null, '2020-03-18', '2020-01-01'),
(default, 4, 590, 'osote3', 1, 2, null, null, '2020-04-30', '2020-03-20');

insert into Bill values 
(DEFAULT, 3, 3003, 'osote1', 1, 2, null, null, '2020-02-20', '2020-02-02', null),
(default, 2, 402, 'osote2', 2, 2, null, null, '2020-04-15', '2020-03-20', null);

insert into Bill values
(default, 3, 340, 'osote4', 1, 2, null, null, '2020-01-30', '2020-02-20', 4);