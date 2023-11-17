<!DOCTYPE html>
<!--
Template Name: Midone - HTML Admin Dashboard Template
Author: Left4code
Website: http://www.left4code.com/
Contact: muhammadrizki@left4code.com
Purchase: https://themeforest.net/user/left4code/portfolio
Renew Support: https://themeforest.net/user/left4code/portfolio
License: You must have a valid license purchased only from themeforest(the above link) in order to legally use the theme for your project.
-->
<html lang="en" class="light">
    <!-- BEGIN: Head -->
    <head>
        <meta charset="utf-8">
        <link href="dist/images/logo.svg" rel="shortcut icon">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Midone admin is super flexible, powerful, clean & modern responsive tailwind admin template with unlimited possibilities.">
        <meta name="keywords" content="admin template, Midone Admin Template, dashboard template, flat admin template, responsive admin template, web app">
        <meta name="author" content="LEFT4CODE">
        <title>CRUD Form - Midone - Tailwind HTML Admin Template</title>
        <!-- BEGIN: CSS Assets-->
        <link rel="stylesheet" href="dist/css/app.css" />
        <!-- END: CSS Assets-->
    </head>
    <!-- END: Head -->
    <body class="py-5">
		<div class="flex mt-[4.7rem] md:mt-0">
		<?php include 'sidemenu.php' ?>
            <!-- BEGIN: Content -->
            <div class="content">
			<?php include 'topbar.php' ?>
                <div class="intro-y flex items-center mt-8">
                    <h2 class="text-lg font-medium mr-auto">
                        item
                    </h2>
                </div>
                <div class="grid grid-cols-12 gap-6 mt-5">
                    <div class="intro-y col-span-12 lg:col-span-12">
                        <!-- BEGIN: Form Layout -->
                        <div class="intro-y box p-5">
                            <div>
                                <label for="txt_item" class="form-label">Itm Name</label>
                                <input id="txt_item" name="txt_item" type="text" class="form-control w-full" placeholder="Input text">
                            </div>
                            <div class="mt-3">
                            <label for="txt-unit" class="form-label">Unit</label>
                                <select id="txt-agent" name="txt_agent" class="form-select w-full">
                                    <option value="">Select an option</option>
                                    <option value="1">PCS</option>
                                    <option value="2">xxx</option>
                                    <option value="3">YYY</option>
                                    <option value="4">ZZZ</option>
                                </select>
                                </div>
                           
                            <div class="intro-y box p-5">
                            <div>
                                <label for="txt_rate" class="form-label">Rate</label>
                                <input id="txt_rate"    name="txt_rate"type="text" class="form-control w-full" placeholder="Input text">
                            </div>
                            <div class="mt-3">
                            <label for="txt-agent" class="form-label">Type</label>
                                <select id="txt-agent" name="txt_agent" class="form-select w-full">
                                    <option value="">Select an option</option>
                                    <option value="1">D</option>
                                    <option value="2">xxx</option>
                                    <option value="3">YYY</option>
                                    <option value="4">ZZZ</option>
                                </select>
                                </div>
                            <div class="mt-3">
                                <label for="txt_limit" class="form-label">Limit</label>
                                <div class="input-group">
                                    <input id="txt_limit"  name="txt_limit"type="text" class="form-control" placeholder="Quantity" aria-describedby="input-group-1">
                                  
                                </div>
                            </div>
                            <label for="itemsSelect">Group</label>
                             <div class="input-group">
                                 <select id="itemsSelect" name="itemsSelect">
                                    <option value="red">Potato</option>
                                    <option value="green">on</option>
                                    <option value="blue">Blue</option>
                                 </select>
                               
                            </div>
                            <div class="mt-3">
                                <label for="txt_op" class="form-label">OP Stock</label>
                                <div class="input-group">
                                    <input id="txt_op" name="txt_op" type="text" class="form-control" placeholder="Weight" aria-describedby="input-group-2">
                                   
                                </div>
                            </div>
                            <div class="mt-3">
                                <label for="txt_remarks" class="form-label">Remarks</label>
                                <div class="input-group">
                                    <input id="txt_remarks"name="txt_remarks" type="text" class="form-control" aria-describedby="input-group-1">
                                  
                                </div>
                            </div>
                            

                        <!-- END: Form Layout -->
                    </div>
                </div>
                <div class="text-right mt-5">
                                 <button type="button" class="btn btn-primary w-24">Save</button>
                                <button type="button" class="btn btn-primary w-24">New</button>
                                <button type="button" class="btn btn-outline-primary w-24 mr-1">Edit</button>
                                <button type="button" class="btn btn-primary w-24">Delete</button>
           </div>
            </div>
           
      
        
		</div>
      
        
        <!-- BEGIN: JS Assets-->
        <script src="https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js"></script>
        <script src="https://maps.googleapis.com/maps/api/js?key=["your-google-map-api"]&libraries=places"></script>
        <script src="dist/js/app.js"></script>
        <!-- END: JS Assets-->
        <script src="dist/js/ckeditor-classic.js"></script>
    </body>
</html>