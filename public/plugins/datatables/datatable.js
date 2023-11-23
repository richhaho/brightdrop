$(function () {
        $("#example1").DataTable();
        $('#example2').DataTable({
          "paging": false,
          "lengthChange": true,
          "searching": false,
          "ordering": false,
          "info": false,
          // "autoWidth": false,
          "scrollCollapse": false,
          // "bAutoWidth": true
          "fixedColumns": true,
          "aaSorting": [],
          columnDefs: [
           { targets: [1], width: '100px', bSortable: false },
           { targets: [2], bSortable: true},
           { targets: [3], bSortable: true},
           {targets: [4], bSortable: true},
           {targets: [5], bSortable: true},
           {targets: [6], bSortable: true},
           {targets: [7], bSortable: true}
          ],
        });
        $('#example3').DataTable({
          "paging": false,
          "lengthChange": true,
          "searching": false,
          "ordering": false,
          "info": false,
          "scrollCollapse": false,
          "fixedColumns": true,
          "aaSorting": [],
          columnDefs: [
           { targets: [1], width: '100px', bSortable: false },
           { targets: [2], bSortable: false},
           { targets: [3], bSortable: true},
           {targets: [4], bSortable: true},
           {targets: [5], bSortable: true}
          ]
        });
        $('#example4').DataTable({
          "paging": false,
          "lengthChange": true,
          "searching": false,
          "ordering": false,
          "info": false,
          "scrollCollapse": false,
          "fixedColumns": true,
          "aaSorting": [],
          columnDefs: [
           { targets: [1], width: '100px', bSortable: false },
           { targets: [2], bSortable: true},
           { targets: [3], bSortable: true},
           {targets: [4], bSortable: true},
           {targets: [5], bSortable: true}
          ]
        });
        $('#example5').DataTable({
          "paging": false,
          "lengthChange": true,
          "searching": false,
          "ordering": true,
          "info": false,
          "scrollCollapse": false,
          "fixedColumns": true,
          "aaSorting": [],
          columnDefs: [
           { targets: [1], width: '100px', bSortable: true },
           { targets: [2], bSortable: true},
           { targets: [3], bSortable: true},
           {targets: [4], bSortable: true},
           {targets: [5], bSortable: true}
          ]
        });
        $('#example6').DataTable({
          "paging": false,
          "lengthChange": true,
          "searching": false,
          "ordering": false,
          "info": false,
          "scrollCollapse": false,
          "fixedColumns": true,
          "aaSorting": [],
          columnDefs: [
           { targets: [1], width: '100px', bSortable: false },
           { targets: [2], bSortable: true},
           { targets: [3], bSortable: true},
           {targets: [4], bSortable: true},
           {targets: [5], bSortable: true}
          ]
        });
        $('#example7').DataTable({
          "paging": false,
          "lengthChange": true,
          "searching": false,
          "ordering": false,
          "info": false,
          "scrollCollapse": false,
          "fixedColumns": true,
          "aaSorting": [],
          columnDefs: [
           { targets: [1], width: '100px', bSortable: false },
           { targets: [2], bSortable: true},
           { targets: [3], bSortable: true},
           {targets: [4], bSortable: true},
           {targets: [5], bSortable: true}
          ]
        });
      });