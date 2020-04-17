-- laskun rekursiivinen kysely
-- parametri id halutun ekan tason laskun id
create or replace function recursive_bills_function() 
returns table(
    bill_id INT,
    previous_bill_id BIGINT,
    tier INT
)
as $suorita$
BEGIN
    return query
    with recursive bl(bill_id, previous_bill_id, tier) as (
        select b.bill_id, b.previous_bill_id, 1
        from Bill b
        where b.bill_due_date < current_date and 
        bill_status_id != 1
        union
        select b.bill_id, bl.previous_bill_id, bl.tier + 1
        from Bill b, bl
        where b.previous_bill_id = bl.bill_id)
        
    select * from bl
    order by tier, bill_id;
END;
$suorita$
language plpgsql;

insert into Bill values
(DEFAULT, 1, 300, 'osote1', 1, 2, null, null, '2020-01-20', '2019-11-30'),
(DEFAULT, 1, 300, 'osote1', 2, 1, null, null, '2020-02-20', '2020-02-02'),
(default, 2, 400, 'osote2', 1, 2, null, null, '2020-03-18', '2020-01-01'),
(default, 4, 590, 'osote3', 1, 2, null, null, '2020-04-30', '2020-03-20');