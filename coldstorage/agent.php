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
                        Agent 
                    </h2>
                </div>
                <div class="grid grid-cols-12 gap-6 mt-5">
                    <div class="intro-y col-span-12 lg:col-span-12">
                        <!-- BEGIN: Form Layout -->
                        <div class="intro-y box p-5">
                            <div>
                                <label for="txt_agent" class="form-label">Agent Name</label>
                                <input id="txt_agent"  name="txt_agent"type="text" class="form-control w-full" placeholder="Enter your Agent name">
                            </div>
                            <div>
                                <label for="txt_code" class="form-label">Sort Code</label>
                                <input id="txt_code"  name="txt_code"type="text" class="form-control w-full" placeholder="Enter your sort code">
                            </div>
                            <div>
                                <label for="txt_village" class="form-label">Village</label>
                                <input id="txt_village" name="txt_village" type="text" class="form-control w-full" placeholder="Enter your village">
                            </div>
                            <div>
                                <label for="txt_post" class="form-label">Post</label>
                                <input id="txt_post" name="txt_post" type="text" class="form-control w-full" placeholder="Enter your post">
                            </div>
                            <div>
                                <label for="txt_anchal" class="form-label">Anchal</label>
                                <input id="txt_anchal" name="txt_anchal" type="text" class="form-control w-full" placeholder="Enter your Anchal">
                            </div>
                            <div>
                                <label for="txt_dist" class="form-label">District</label>
                                <input id="txt_dist"  name="txt_dist"type="text" class="form-control w-full" placeholder="Enter your District">
                            </div>
                            <div>
                                <label for="txt_target" class="form-label">Target</label>
                                <input id="txt_target"   name="txt_target" type="text" class="form-control w-full" placeholder="Enter your Target">
                            </div>
                            <div>
                                <label for="txt_limit" class="form-label">Limit</label>
                                <input id="txt_limit"  name="txt_limit"type="text" class="form-control w-full" placeholder="Enter your Limit">
                            </div>
                            <div>
                                <label for="txt_interest" class="form-label">Interest</label>
                                <input id="txt_interest"  name="txt_interest"type="text" class="form-control w-full" placeholder="Enter your Interest">
                            </div>
                            
                            <div>
                                <label for="txt_commission" class="form-label">Commission</label>
                                <input id="txt_commission"   name="txt_commission"type="text" class="form-control w-full" placeholder="Enter your commission">
                            </div>
                            <div class="mt-3">
                            <label for="txt-agent" class="form-label">Group Agent</label>
                                <select id="txt-agent" name="txt_agent" class="form-select w-full">
                                    <option value="">Select an option</option>
                                    <option value="1">Party A/C</option>
                                    <option value="2">xxx</option>
                                    <option value="3">YYY</option>
                                    <option value="4">ZZZ</option>
                                </select>
                                </div>
                                <div>
                                <label for="txt_pxt" class="form-label">Pkt</label>
                                <input id="txt_pxt" name="txt_pxt" type="text" class="form-control w-full" placeholder="Enter your pxt">
                            </div>
                            <div>
                                <label for="txt_voter" class="form-label">VoterId/Pan</label>
                                <input id="txt_voter"  name="txt_voter"type="text" class="form-control w-full" placeholder="Enter your Voterid/pan">
                            </div>
                            <div class="text-right mt-5">
                                <button type="button" class="btn btn-outline-primary w-24 mr-1">Edit</button>

                                <button type="button" class="btn btn-primary w-24">New</button>
                                <button type="button" class="btn btn-primary w-24">Save</button>
                                <button type="button" class="btn btn-primary w-24">Delete</button>
                            </div>

                            </div>
                        
                        <!-- END: Form Layout -->
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