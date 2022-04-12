<?php
require 'class.eyemysqladap.inc.php';
require 'class.eyedatagrid.inc.php';

// Load the database adapter
$db = new EyeMySQLAdap('localhost', 'root', 'g3st1onkb', 'sis_gestion');

// Load the datagrid class
$x = new EyeDataGrid($db);

// Set the query, select all rows from the kardex table
$x->setQuery("*", "planillas");

// Hide ID Column
$x->hideColumn('idplanilla');
//$x->hideColumn('id');

// Show reset grid control
//$x->showReset();

// Add standard control
$x->addStandardControl(EyeDataGrid::STDCTRL_EDIT,  "href='edit_planilla.php?id=%idplanilla%'");
//$x->addStandardControl(EyeDataGrid::STDCTRL_DELETE,"href='eliminar.php?cod=%codproducto%'" );

// Add create control
//$x->showCreateButton('#', EyeDataGrid::TYPE_ONCLICK,"<a href='insertar.php'>Insertar</a>");

// Show checkboxes
//$x->showCheckboxes();

// Show row numbers
$x->showRowNumber();

// Change the amount of results per page
$x->setResultsPerPage(15);

// Stop ordering
$x->hideOrder();
?>

<link href="table.css" rel="stylesheet" type="text/css">

<?php
// Print the table
mysql_close();
$x->printTable();
?>