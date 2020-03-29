-- c_id on haluttu contract_id
CREATE OR REPLACE FUNCTION laskun_muodostus(c_id bigint)
RETURNS table(
    name varchar, sum numeric, vat numeric, salepercentage numeric, total numeric
)
AS $suorita$
BEGIN
    return QUERY
    with temp as 
        (select work_type_name as name, sum(Billable_hour.quantity*hourly_rate) as sum, round(avg(vat_rate)) as vat_rate, round(avg(sale_percentage),2) as sale_percentage
        FROM Billable_hour left outer join Work_type on Billable_hour.work_type_id = Work_type.work_type_id left outer join Vat_type on Work_type.vat_type_id = Vat_type.vat_type_id
        WHERE Billable_hour.contract_id = c_id
        group by work_type_name)
    select temp.name, temp.sum, temp.vat_rate, temp.sale_percentage, round(temp.sum - (temp.sum*((100-temp.vat_rate)/100)*(temp.sale_percentage/100)),2) as total
    from temp 
    union
    (with temp2 as
        (select tool_name as name, sum(tool_selling_price*Sold_tool.quantity) as sum, round(avg(vat_rate)) as vat_rate, round(avg(sale_percentage),2) as sale_percentage
        from Sold_tool left outer join Tool on Sold_tool.tool_id = Tool.tool_id left outer join Vat_type on Tool.vat_type_id = Vat_type.vat_type_id
        where contract_id = c_id
        group by tool_name)
    select temp2.name, temp2.sum, temp2.vat_rate, temp2.sale_percentage, round(temp2.sum + (temp2.sum*((100-temp2.vat_rate)/100)*(temp2.sale_percentage/100)),2) as total
    from temp2);
END;
$suorita$
language plpgsql;     