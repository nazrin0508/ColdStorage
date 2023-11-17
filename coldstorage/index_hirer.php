

<!DOCTYPE html>
<html lang="en" class="light">
    <!-- BEGIN: Head -->
    <head>
        <meta charset="utf-8">
        <link href="dist/images/logo.svg" rel="shortcut icon">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Enigma admin is super flexible, powerful, clean & modern responsive tailwind admin template with unlimited possibilities.">
        <meta name="keywords" content="admin template, Enigma Admin Template, dashboard template, flat admin template, responsive admin template, web app">
        <meta name="author" content="LEFT4CODE">
        <title>Village</title>
        <!-- BEGIN: CSS Assets-->
        <link rel="stylesheet" href="dist/css/app.css" />
		<link rel="stylesheet" href="dist/css/sweetalert2.min.css" />
		<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" />
        <!-- END: CSS Assets-->


    </head>
	<style>
        .dataTables_length select
        {
            width:60px;
        }
    </style>	

<style>
        /* Hard-coded styles for badges */
        .badge {
            display: inline-block;
            padding: 5px 10px;
            font-size: 14px;
            font-weight: bold;
            border-radius: 5px;
        }

        /* Custom badge styles for "Transferred, InActive and Resigned" status */
        .badge-other {
            color: white;
            background-color: red;
        }

        /* Custom badge styles for "Active" statuses */
        .badge-active {
            color: white;
            background-color: green;
        }
    </style>
    <!-- END: Head -->
    <body class="py-5 md:py-0">
                <!-- BEGIN: Mobile Menu -->
        <!-- END: Mobile Menu -->
    <div class="flex mt-[4.7rem] md:mt-0">
    <?php include 'sidemenu.php' ?>
        
        <!-- BEGIN: Content -->
        <div class="content content--top-nav">
        <?php include 'topbar.php' ?>
            <div class="intro-y flex items-center mt-8">
                <h2 class="text-lg font-medium mr-auto">
                    Village
                </h2>
            </div>
            <div class="grid grid-cols-12 gap-6 mt-5">

                <div class="intro-y col-span-12 lg:col-span-12">

                            <button class="btn btn-primary shadow-md mr-2"
                            onclick="add_new()" data-tw-toggle="modal" 
                            data-tw-target="#header-footer-modal-preview-view">Hirer</button>

                    <!-- BEGIN: Responsive Table -->
                    <div class="intro-y box mt-5">				
                        <div class="p-5" id="responsive-table">
                            <div class="preview">
                               <div class="overflow-x-auto">
                               <table id="table" class="table table-bordered table-striped" style="width:100%" cellpadding="7px">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Hirer Name</th>
                                            <th>Father's Name</th>
                                            <th>A/c off</th>
                                            <th>Phone No</th>
                                            <th>Marketing</th>
                                            <th>VoterId/PanNo</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>

                               </div>
                            </div>
                            
                        </div>
                    </div>
                    <!-- END: Responsive Table -->
                </div>
            </div>
        </div>
        <!-- END: Content -->
        <!-- BEGIN: Delete Confirmation Modal -->
                <div id="delete-confirmation-modal" class="modal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-body p-0">
                                <div class="p-5 text-center">
                                    <i data-lucide="x-circle" class="w-16 h-16 text-danger mx-auto mt-3"></i> 
                                    <div class="text-3xl mt-5">Are you sure?</div>
                                    <div class="text-slate-500 mt-2">
                                        Do you really want to delete these records? 
                                        <br>
                                        This process cannot be undone.
                                    </div>
                                </div>
                                <div class="px-5 pb-8 text-center">
                                    <button type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary w-24 mr-1">Cancel</button>
                                    <button type="button" class="btn btn-danger w-24">Delete</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END: Delete Confirmation Modal -->
                    <!-- BEGIN: View Modal -->
                    <div class="intro-y box mt-5 hidden">
                        <div class="flex flex-col sm:flex-row items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
                            <h2 class="font-medium text-base mr-auto">
                                Header & Footer Modal
                            </h2>
                        </div>
                        <div id="header-footer-modal" class="p-5">
                            <div class="preview">
                                <!-- BEGIN: Modal Toggle -->
                                <div class="text-center"> <a href="javascript:;" data-tw-toggle="modal" data-tw-target="#header-footer-modal-preview" class="btn btn-primary">Show Modal</a> </div>
                                <!-- END: Modal Toggle -->
                                <!-- BEGIN: Modal Content -->
                                <div id="header-footer-modal-preview-view" class="modal" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <!-- BEGIN: Modal Header -->
                                            <div class="modal-header">
                                                <h2 class="font-medium text-base mr-auto">
                                                 Hirer
                                                </h2>
                                            </div>
                                            <!-- END: Modal Header -->
                                            <!-- BEGIN: Modal Body -->
											<form id="frm_user" name="frm_user" action="" method="post">
                                            <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                                                <input id="txt_hirer_id" name="txt_hirer_id" type="hidden" class="form-control" placeholder="Hirer Id" readonly>
                                                <div class="col-span-12 sm:col-span-12">
                                                    <label for="txt_hirer_name" class="form-label">Hirer Name</label>
                                                    <input id="txt_hirer_name" name="txt_hirer_name" type="text" class="form-control" placeholder="Enter your Hirer Name">
                                                </div>
                                                <div class="col-span-12 sm:col-span-12">
                                                    <label for="txt_fathe_name" class="form-label">Father Name</label>
                                                    <input id="txt_father_name" name="txt_father_name" type="text" class="form-control" placeholder="Enter your Father Name">
                                                </div>
                                                <div class="col-span-12 sm:col-span-12">
                                                    <label for="txt_a/c_off" class="form-label">A/C Off</label>
                                                    <input id="txt_a/c_off" name="txt_a/c_off" type="text" class="form-control" placeholder="Enter your A/c off">
                                                </div>
                                                <div class="col-span-12 sm:col-span-12">
                                                    <label for="txt_district" class="form-label">District</label>
                                                    <input id="txt_district" name="txt_district" type="text" class="form-control" placeholder="Enter your District ">
                                                </div>
                                            </div>
                                            <!-- END: Modal Body -->
											</form>
                                            <!-- BEGIN: Modal Footer -->
                                            <div class="modal-footer">
                                            <button type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary w-20 mr-1">Cancel</button>
                                                <button id="btn_save" data-tw-dismiss="modal" class="btn btn-primary w-20">Save</button>
												<button id="btn_update" data-tw-dismiss="modal" class="btn btn-primary w-20">Update</button>
                                            </div>
											
                                            <!-- END: Modal Footer -->
                                        </div>
                                    </div>
                                </div>
                                <!-- END: Modal Content -->
                            </div>
                        </div>
                    </div>
                    <!-- END: View Modal -->
        <!-- END: JS Assets-->
		<script src="dist/js/app.js"></script>
		<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
		<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
		<script src="dist/js/sweetalert2.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </div>
    </body>
	<script>
	    var dtable = $('#table').DataTable({
            buttons: ['copy', 'excel', 'pdf'],
            "processing": true,
            "searching": true,
            "serverSide": true,
            "ajax": "ajax_village.php",
            "columns": [
                { "data": "id" },
                { "data": "village" },
                { "data": "post" },
                { "data": "anchal" },
                { "data": "district" },
                { "data": "action" }
            ],
            "order": [0, "asc"],
        });
        

        function convertFormToJSON(form) {
            const array = $(form).serializeArray(); 
            const json = {};
            $.each(array, function () {
                key=this.name;
                key=key.substring(key.indexOf("_") + 1);
                json[key] = this.value || "";
            });
            return json;
        }


	    function remove_data(id) {
            console.log("CLicked Remove")
        Swal.fire({
        title: 'Are you sure to Delete?',
        text: 'You won\'t be able to revert this!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            // Delete event
            $.ajax({
                url: '../../ColdStorage/coldstorageApi/village/' + id,
                type: 'DELETE',
                dataType: 'json',
                contentType: 'application/json',
                success: function (data) {
                    if (data.status == "Ok") {
                        Swal.fire({
                            title: data.msg,
                            icon: 'success',
                        }).then((result) => {
                            dtable.draw();
                        });
                    } else {
                        Swal.fire(data.msg, '', 'error');
                    }
                },
                error: function (xhr, textStatus, errorThrown) {
                    Swal.fire('Error', 'An error occurred while deleting the data.', 'error');
                }
            });

        } else {
            Swal.close();
        }
    });
}


        function add_new() {
            $("#btn_save").show();
            $("#btn_update").hide();
            $("#txt_id").removeAttr("readonly");
            $('#frm_user').trigger("reset");
        }

	    $("#btn_update" ).on( "click", function() {	
        const form = $("#frm_user");
        console.log(form);
        const json = convertFormToJSON(form);
		console.log(json);
		var id=$("#txt_village_id").val();
		$.ajax({
            url: '../../ColdStorage/coldstorageApi/village/'+id,
            type: 'PUT',
            dataType: 'json',
            contentType: 'application/json',
            success: function (data) {
				if (data.status=="Ok") {
					$("#header-footer-modal-preview").hide();
					dtable.draw();
				}
            },
            data: JSON.stringify(json)
        });
	});
	
	$("#btn_save" ).on( "click", function() {	
        const form = $("#frm_user");
        const json = convertFormToJSON(form);
		console.log(json);
		$.ajax({
            url: '../../ColdStorage/coldstorageApi/village',
            type: 'POST',
            dataType: 'json',
            contentType: 'application/json',
            success: function (data) 
            {
				if (data.status=="Ok") {
					$("#header-footer-modal-preview").hide();
					dtable.draw();
				}
               
            },
            data: JSON.stringify(json)
        });
	    });

	
	function load_data(id) 
    {
		$("#btn_save").hide();
		$("#btn_update").show();
		$.ajax({
			url:'../../ColdStorage/coldstorageApi/village/'+id,
			method:"GET",              
			success:function(res){
				$("#txt_village_id").val(res.id);
				$("#txt_village_name").val(res.villageName);
                $("#txt_post").val(res.post);
                $("#txt_anchal").val(res.anchal);
                $("#txt_district").val(res.district);
				
			}
		});
	}
	
		
	</script>
   
</html>