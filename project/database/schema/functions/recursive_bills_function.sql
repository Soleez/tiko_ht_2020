-- laskun rekursiivinen kysely
-- parametri id halutun ekan tason laskun id
create or replace function recursive_bills_function(id INT) 
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
        union
        select b.bill_id, bl.previous_bill_id, bl.tier + 1
        from Bill b, bl
        where b.previous_bill_id = bl.bill_id)
        
    select * from bl
    where bl.bill_id = id or bl.previous_bill_id = id
    order by tier, bill_id;
END;
$suorita$
language plpgsql;