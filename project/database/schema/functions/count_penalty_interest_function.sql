create or replace function count_penalty_interest_function(due_date date)
returns numeric(10,2)
as $suorita$
declare
    days numeric(10,2) := current_date - CAST(due_date as date);
begin

    return days * 0.16/365;
end;
$suorita$
language plpgsql;