<%@ page session="true" contentType="text/html; charset=ISO-8859-1" %> <%@
taglib uri="http://www.tonbeller.com/jpivot" prefix="jp" %> <%@ taglib
prefix="c" uri="http://java.sun.com/jstl/core" %>

<jp:mondrianQuery
  id="query01"
  jdbcDriver="com.mysql.jdbc.Driver"
  jdbcUrl="jdbc:mysql://localhost/wh_adventureworks?user=root&password="
  catalogUri="/WEB-INF/queries/faktaPenjualan.xml"
>
  select {[Measures].[Unit Price],[Measures].[Order Quantity],[Measures].[Sub Total]} 
  ON COLUMNS, 
  {([Customer Store],[Time].[All Times],[Customer Address].[All Customer Address],[Shipping Method], [Product].[All Product])}
  ON ROWS from [Jual]
</jp:mondrianQuery>

<c:set var="title01" scope="session"
  >Query Adventure Works using Mondrian OLAP</c:set
>
