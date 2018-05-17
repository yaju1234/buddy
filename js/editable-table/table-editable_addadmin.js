var EditableTable = function () {

    return {

        //main function to initiate the module
        init: function () {
            function restoreRow(oTable, nRow) {
                var aData = oTable.fnGetData(nRow);
                var jqTds = $('>td', nRow);

                for (var i = 0, iLen = jqTds.length; i < iLen; i++) {
                    oTable.fnUpdate(aData[i], nRow, i, false);
                }

                oTable.fnDraw();
            }

            function editRow(oTable, nRow) {
				
				
				var config = {
					'.chosen-select'           : {},
					'.chosen-select-deselect'  : {allow_single_deselect:true},
					'.chosen-select-no-single' : {disable_search_threshold:10},
					'.chosen-select-no-results': {no_results_text:'Oops, nothing found!'},
					'.chosen-select-width'     : {width:"95%"}
				  }
				  for (var selector in config) {
					$(selector).chosen(config[selector]);
				  }
       		  
                var aData = oTable.fnGetData(nRow);
                var jqTds = $('>td', nRow);
				
				
				
				jqTds[0].innerHTML = '<input type="text" class="form-control small" value="' + aData[0] + '">';
				
				//jqTds[1].innerHTML = '<select class="chosen-select" multiple="multiple" id="admin_location" name="admin_location[]"><option value="etc">etc val</option></select>';
				
				$.ajax({
					url      : BASE_URL+'admin/getAdminLocations',
					type     : 'POST',
					success  : function(resp_loc){
					//alert(resp);
						 if(resp_loc != '0'){
							var admin_location = resp_loc;
							
							jqTds[1].innerHTML = '<select class="chosen-select" multiple="multiple" id="admin_location" name="admin_location[]">'+ admin_location +'</select>';
					        $(".chosen-select").data("placeholder","Select").chosen();	 
					 }

					},
					error    : function(resp){

						 $.prompt("Sorry, something isn't working right.", {title:'Error'});
					}
				 });
                
				$.ajax({
					url      : BASE_URL+'admin/getAdminType',
					type     : 'POST',
					success  : function(resp){
					//alert(resp);
						 if(resp != '0'){
							var admin_type = resp;
							jqTds[2].innerHTML = '<select class="form-control" id="admin_type" name="admin_type">'+ admin_type +'</select>';
						 }

					},
					error    : function(resp){

						 $.prompt("Sorry, something isn't working right.", {title:'Error'});
					}
				 });
				
                
                
                /*jqTds[3].innerHTML = '<input type="text" class="form-control small" value="' + aData[3] + '">';
				jqTds[4].innerHTML = '<input type="text" class="form-control small" value="' + aData[4] + '">';
				jqTds[5].innerHTML = '<input type="text" class="form-control small" value="' + aData[5] + '">';*/
				
                jqTds[3].innerHTML = '<a class="edit" href="">Save</a>&nbsp;<a class="cancel_new" href="">Cancel</a>';
                //jqTds[7].innerHTML = '<a class="cancel" href="">Cancel</a>';
            }

            function saveRow(oTable, nRow) {
                var jqInputs = $('input', nRow);
                oTable.fnUpdate(jqInputs[0].value, nRow, 0, false);
                oTable.fnUpdate(jqInputs[1].value, nRow, 1, false);
                oTable.fnUpdate(jqInputs[2].value, nRow, 2, false);
                oTable.fnUpdate(jqInputs[3].value, nRow, 3, false);
				oTable.fnUpdate(jqInputs[4].value, nRow, 4, false);
                oTable.fnUpdate(jqInputs[5].value, nRow, 5, false);
                oTable.fnUpdate('<a class="edit" href="">Edit</a><a class="delete" href="">Delete</a>', nRow, 6, false);
                //oTable.fnUpdate('<a class="delete" href="">Delete</a>', nRow, 5, false);
                oTable.fnDraw();
            }

            function cancelEditRow(oTable, nRow) {
                var jqInputs = $('input', nRow);
                oTable.fnUpdate(jqInputs[0].value, nRow, 0, false);
                oTable.fnUpdate(jqInputs[1].value, nRow, 1, false);
                oTable.fnUpdate(jqInputs[2].value, nRow, 2, false);
                oTable.fnUpdate(jqInputs[3].value, nRow, 3, false);
                oTable.fnUpdate('<a class="edit" href="">Edit</a>', nRow, 4, false);
                oTable.fnDraw();
            }

            var oTable = $('#editable-sample').dataTable({
                "aLengthMenu": [
                    [5, 15, 20, -1],
                    [5, 15, 20, "All"] // change per page values here
                ],
                // set the initial value
                "iDisplayLength": 5,
                "sDom": "<'row'<'col-lg-6'l><'col-lg-6'f>r>t<'row'<'col-lg-6'i><'col-lg-6'p>>",
                "sPaginationType": "bootstrap",
                "oLanguage": {
                    "sLengthMenu": "_MENU_ records per page",
                    "oPaginate": {
                        "sPrevious": "Prev",
                        "sNext": "Next"
                    }
                },
                "aoColumnDefs": [{
                        'bSortable': false,
                        'aTargets': [0]
                    }
                ]
            });

            jQuery('#editable-sample_wrapper .dataTables_filter input').addClass("form-control medium"); // modify table search input
            jQuery('#editable-sample_wrapper .dataTables_length select').addClass("form-control xsmall"); // modify table per page dropdown

            var nEditing = null;

            $('#editable-sample_new').click(function (e) {
                e.preventDefault();
				
				
                var aiNew = oTable.fnAddData(['', '', '', 
                        '<a class="edit" href="">Edit</a>', '<a class="cancel" data-mode="new" href="">Cancel</a>'
                ]);
                var nRow = oTable.fnGetNodes(aiNew[0]);
				
                editRow(oTable, nRow);
                nEditing = nRow;
            });

            $('#editable-sample a.delete').live('click', function (e) {
                e.preventDefault();

                if (confirm("Are you sure to delete this row ?") == false) {
                    return;
                }

                var nRow = $(this).parents('tr')[0];
                oTable.fnDeleteRow(nRow);
                alert("Deleted! Do not forget to do some ajax to sync with backend :)");
            });

            $('#editable-sample a.cancel').live('click', function (e) {
                e.preventDefault();
				//alert($(this).attr("data-mode"));
                if ($(this).attr("data-mode") == "new") {
                    var nRow = $(this).parents('tr')[0];
                    oTable.fnDeleteRow(nRow);
                } else {
                    restoreRow(oTable, nEditing);
                    nEditing = null;
					//var nRow = $(this).parents('tr')[0];
                    //oTable.fnDeleteRow(nRow);
                }
            });
			
			
			//remove a new row if don't want to save
			$('#editable-sample a.cancel_new').live('click', function (e) {
                e.preventDefault();
				
                    var nRow = $(this).parents('tr')[0];
                    oTable.fnDeleteRow(nRow);
                
            });

            $('#editable-sample a.edit').live('click', function (e) {
                e.preventDefault();
				
                /* Get the row as a parent of the link that was clicked on */
                var nRow = $(this).parents('tr')[0];

                if (nEditing !== null && nEditing != nRow) {
                    /* Currently editing - but not this row - restore the old before continuing to edit mode */
                    restoreRow(oTable, nEditing);
                    editRow(oTable, nRow);
                    nEditing = nRow;
                } else if (nEditing == nRow && this.innerHTML == "Save") {
                    /* Editing this row and want to save it */
                    saveRow(oTable, nEditing);
                    nEditing = null;
                    alert("Updated! Do not forget to do some ajax to sync with backend :)");
                } else {
                    /* No edit in progress - let's start one */
                    editRow(oTable, nRow);
                    nEditing = nRow;
                }
            });
        }

    };

}();