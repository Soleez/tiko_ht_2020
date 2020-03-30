-- työkalujen loppusumma sopimuksella c_id, aikaväliltä date1-date2
CREATE OR REPLACE FUNCTION tyokalujensumma_function(c_id bigint, date1 date, date2 date) RETURNS numeric(10,2)
AS $suorita$
BEGIN
    return(select round(sum(quantity*tool_selling_price - (quantity*tool_selling_price*(cast(100-vat_rate as numeric(10,2))/100)*(cast(sale_percentage as numeric(10,2))/100))),2)
    from Sold_tool join Tool on Sold_tool.tool_id = Tool.tool_id join Vat_type on Tool.vat_type_id=Vat_type.vat_type_id
    where Sold_tool.contract_id = c_id and date_added >= date1 and date_added <= date2);
END;
$suorita$
language plpgsql;