create or replace function count_penalty_intrest_function(due_date date)
returns numeric(10,2)
as $suorita$
declare
    days numeric(10,2) := current_date - due_date;
begin

    return days * 0.16/365;
end;
$suorita$
language plpgsql;