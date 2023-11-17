<?php
require_once('db.php');
$query='select * from chember';
$connection=new mysqli(
    $GLOBALS['server'],$GLOBALS['user'],
    $GLOBALS['pass'],$GLOBALS['database']);
$result = mysqli_query($connection, $query);


?> 
 
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
        <script src="dist/js/jquery.js"></script>
        <link rel="stylesheet" href="dist/css/app.css" />
        <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
        <!-- END: CSS Assets-->
        <script>
            $(document).ready(function(){            	
            new DataTable('#data');
            });
        </script>
    </head>
    <!-- END: Head -->
    <body class="py-5">
		<div class="flex mt-[4.7rem] md:mt-0">
		 <?php include 'sidemenu.php' ?><!-- hello  -->
            <!-- BEGIN: Content -->
            <div class="content">
			 <?php include 'topbar.php' ?>
                  <div class="intro-y flex items-center mt-8">
                    <h2 class="text-lg font-medium mr-auto">
                       Chember/Floor
                    </h2>
                 </div>
                <div class="grid grid-cols-12 gap-6 mt-5">
                    <div class="intro-y col-span-12 lg:col-span-12">
                        <!-- BEGIN: Form Layout -->
                        <table  id="data"class="display table-responsive">
                            <thead>
                                <tr>
                                    <th>Ch/Fl/Ty </th>
                                    <th>Type</th>
                                    <th>Sardar</th>
                                    <th>Edit</th>
                                    <th>Delete</th>
                                   
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                <?php
                                    while($row= mysqli_fetch_assoc($result)){
                                     ?>
                                     <td> <?php echo $row['ch_fl_ty']?></td>
                                     <td> <?php echo $row['type']?></td>
                                     <td> <?php echo $row['sarder']?></td>
                                     <td> <a href ="#" class="btn btn-primary"> Edit</a></td>
                                     <td> <a href="#" class=" btn btn-danger">Delete</a></td>
                                      </tr>
                                     <?php   
                                        }
                                     ?>
                                    
                            
                            </tbody>
                        </table>
                      
                    </div>
                </div>
            </div>
            <!-- END: Content -->
        <!-- BEGIN: Dark Mode Switcher-->
        <!-- <div data-url="side-menu-dark-crud-form.html" class="dark-mode-switcher cursor-pointer shadow-md fixed bottom-0 right-0 box border rounded-full w-40 h-12 flex items-center justify-center z-50 mb-10 mr-10">
            <div class="mr-4 text-slate-600 dark:text-slate-200">Dark Mode</div>
            <div class="dark-mode-switcher__toggle border"></div>
        </div>
		</div> -->
        <!-- END: Dark Mode Switcher-->
        
        <!-- BEGIN: JS Assets-->
        <script src="https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js"></script>
        <script src="https://maps.googleapis.com/maps/api/js?key=["your-google-map-api"]&libraries=places"></script>
        <script src="dist/js/app.js"></script>
        <!-- END: JS Assets-->
        <script src="dist/js/ckeditor-classic.js"></script>
    </body>
</html>