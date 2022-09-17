$(document).ready(function() {
	   let tables = ['.ace-table'];
	   for(var i=0; i<tables.length;i++){
          $(tables[i]).DataTable();
        }
    });
    $('#example23').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    });
