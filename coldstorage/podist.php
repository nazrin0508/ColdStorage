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
        <!-- BEGIN: CSS Asset-->
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
                        Post
                    </h2>
                </div>
                <div class="grid grid-cols-12 gap-6 mt-5">
                    <div class="intro-y col-span-12 lg:col-span-12">
                        <!-- BEGIN: Form Layout -->
                        <div class="intro-y box p-5">
                            <div>
                                <label for="crud-form-1" class="form-label">Product Name</label>
                                <input id="crud-form-1" type="text" class="form-control w-full" placeholder="Input text">
                            </div>
                            <div class="mt-3">
                                <label for="crud-form-2" class="form-label">Category</label>
                                <select data-placeholder="Select your favorite actors" class="tom-select w-full" id="crud-form-2" multiple>
                                    <option value="1" selected>Sport & Outdoor</option>
                                    <option value="2">PC & Laptop</option>
                                    <option value="3" selected>Smartphone & Tablet</option>
                                    <option value="4">Photography</option>
                                </select>
                            </div>
                            <div class="mt-3">
                                <label for="crud-form-3" class="form-label">Quantity</label>
                                <div class="input-group">
                                    <input id="crud-form-3" type="text" class="form-control" placeholder="Quantity" aria-describedby="input-group-1">
                                    <div id="input-group-1" class="input-group-text">pcs</div>
                                </div>
                            </div>
                            <div class="mt-3">
                                <label for="crud-form-4" class="form-label">Weight</label>
                                <div class="input-group">
                                    <input id="crud-form-4" type="text" class="form-control" placeholder="Weight" aria-describedby="input-group-2">
                                    <div id="input-group-2" class="input-group-text">grams</div>
                                </div>
                            </div>
                            <div class="mt-3">
                                <label class="form-label">Price</label>
                                <div class="sm:grid grid-cols-3 gap-2">
                                    <div class="input-group">
                                        <div id="input-group-3" class="input-group-text">Unit</div>
                                        <input type="text" class="form-control" placeholder="Unit" aria-describedby="input-group-3">
                                    </div>
                                    <div class="input-group mt-2 sm:mt-0">
                                        <div id="input-group-4" class="input-group-text">Wholesale</div>
                                        <input type="text" class="form-control" placeholder="Wholesale" aria-describedby="input-group-4">
                                    </div>
                                    <div class="input-group mt-2 sm:mt-0">
                                        <div id="input-group-5" class="input-group-text">Bulk</div>
                                        <input type="text" class="form-control" placeholder="Bulk" aria-describedby="input-group-5">
                                    </div>
                                </div>
                            </div>
                            <div class="mt-3">
                                <label>Active Status</label>
                                <div class="form-switch mt-2">
                                    <input type="checkbox" class="form-check-input">
                                </div>
                            </div>
                            <div class="mt-3">
                                <label>Description</label>
                                <div class="mt-2">
                                    <div class="editor">
                                        <p>Content of the editor.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="text-right mt-5">
                                <button type="button" class="btn btn-outline-secondary w-24 mr-1">Cancel</button>
                                <button type="button" class="btn btn-primary w-24">Save</button>
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