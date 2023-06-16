<%@ page session="true" contentType="text/html; charset=ISO-8859-1" %> 
<%@ taglib uri="http://www.tonbeller.com/jpivot" prefix="jp" %> 
<%@ taglib prefix="c" uri="http://java.sun.com/jstl/core" %>

<jp:mondrianQuery
  id="query01"
  jdbcDriver="com.mysql.jdbc.Driver"
  jdbcUrl="jdbc:mysql://localhost/dwo_lagi?user=root&password="
  catalogUri="/WEB-INF/queries/fakta_barang_tidak_diterima.xml"
>
  select [Measures].[Order Qty] ON COLUMNS, {([Vendor].[All Vendor],[Time].[All Times],[Employee].[All Employees])} ON ROWS from [Purcashing]
</jp:mondrianQuery>

<c:set var="title01" scope="session">Query DWO Sales using Mondrian OLAP</c:set>
