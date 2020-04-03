-- töiden loppusumma sopimuksella c_id, aikaväliltä date1-date2
CREATE OR REPLACE FUNCTION worksum_function(c_id bigint) RETURNS numeric(10,2)
AS $suorita$
BEGIN
    return(select round(sum(quantity*hourly_rate - (quantity*hourly_rate*(cast(100-vat_rate as numeric(10,2))/100)*(cast(sale_percentage as numeric(10,2)) /100))),2)
    from Billable_hour join Work_type on Billable_hour.work_type_id = Work_type.work_type_id join Vat_type on Work_type.vat_type_id=Vat_type.vat_type_id
    where Billable_hour.contract_id = c_id );
END;
$suorita$
language plpgsql;