create or replace function create_new_bill(bill_type INT) 
returns table(
    
)
as $suorita$
BEGIN
    return query
    INSERT INTO Bill VALUES
    ();
    
END;
$suorita$
language plpgsql;