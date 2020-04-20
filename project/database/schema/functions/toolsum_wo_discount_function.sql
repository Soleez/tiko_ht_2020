-- työkalujen loppusumma sopimuksella c_id, aikaväliltä date1-date2
CREATE OR REPLACE FUNCTION toolsum_wo_discount_function(c_id bigint) RETURNS numeric(10,2)
AS $suorita$
BEGIN
    return(
    
    select *, round(sum(quantity*tool_selling_price),2)
    from Sold_tool 
        join Tool on Sold_tool.tool_id = Tool.tool_id 
        join Vat_type on Tool.vat_type_id=Vat_type.vat_type_id
        LEFT OUTER JOIN bill on bill.contract_id = sold_tool.contract_id
    where Sold_tool.contract_id = c_id AND date_added >);

END;
$suorita$
language plpgsql;