$(function() {
  var oTable = $('.datatable').DataTable({
    "oLanguage": {
      "sSearch": "Filter Data"
    },
    // "columnDefs": [
    //         {
    //             "targets": [ 1 ],
    //             "visible": false,
    //             //"searchable": false
    //         },
    // 
    // "iDisplayLength": -1,
    // "sPaginationType": "full_numbers",

  });


//fixedHeader: {
    //         header: true,
    //         headerOffset: $('#navbar-collapse').height()
    //     }

  $("#datepicker_from").datepicker({
    //showOn: "button",
    //buttonImage: "images/calendar.gif",
    //buttonImageOnly: false,
    "onSelect": function(date) {
      minDateFilter = new Date(date).getTime();
      oTable.fnDraw();
    }
  }).keyup(function() {
    minDateFilter = new Date(this.value).getTime();
    oTable.fnDraw();
  });

  $("#datepicker_to").datepicker({
    //showOn: "button",
    //buttonImage: "images/calendar.gif",
    //buttonImageOnly: false,
    "onSelect": function(date) {
      maxDateFilter = new Date(date).getTime();
      oTable.fnDraw();
    }
  }).keyup(function() {
    maxDateFilter = new Date(this.value).getTime();
    oTable.fnDraw();
  });

});

// Date range filter
minDateFilter = "";
maxDateFilter = "";

$.fn.dataTableExt.afnFiltering.push(
  function(oSettings, aData, iDataIndex) {
    if (typeof aData._date == 'undefined') {
      aData._date = new Date(aData[0]).getTime();
    }

    if (minDateFilter && !isNaN(minDateFilter)) {
      if (aData._date < minDateFilter) {
        return false;
      }
    }

    if (maxDateFilter && !isNaN(maxDateFilter)) {
      if (aData._date > maxDateFilter) {
        return false;
      }
    }

    return true;
  }
);