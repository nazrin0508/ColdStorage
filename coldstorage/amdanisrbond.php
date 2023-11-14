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
                        amdani
                    </h2>
                </div>
                <div class="grid grid-cols-12 gap-6 mt-5">
                    <div class="intro-y col-span-12 lg:col-span-12">
                        <!-- BEGIN: Form Layout -->
                        <div class="intro-y box p-5">
                            <div>
                                <label for="crud-form-1" class="form-label">Agent Name</label>
                                <input id="crud-form-1" type="text" class="form-control w-full" placeholder="Input text">
                            </div>
                            <div>
                                <label for="crud-form-1" class="form-label">Sort Code</label>
                                <input id="crud-form-1" type="text" class="form-control w-full" placeholder="Input text">
                            </div>
                            <div>
                                <label for="crud-form-1" class="form-label">Village</label>
                                <input id="crud-form-1" type="text" class="form-control w-full" placeholder="Input text">
                            </div>
                            <div>
                                <label for="crud-form-1" class="form-label">Post</label>
                                <input id="crud-form-1" type="text" class="form-control w-full" placeholder="Input text">
                            </div>
                            <div>
                                <label for="crud-form-1" class="form-label">Anchal</label>
                                <input id="crud-form-1" type="text" class="form-control w-full" placeholder="Input text">
                            </div>
                            <div>
                                <label for="crud-form-1" class="form-label">District</label>
                                <input id="crud-form-1" type="text" class="form-control w-full" placeholder="Input text">
                            </div>
                            <div>
                                <label for="crud-form-1" class="form-label">Target</label>
                                <input id="crud-form-1" type="text" class="form-control w-full" placeholder="Input text">
                            </div>
                            <div>
                                <label for="crud-form-1" class="form-label">Limit</label>
                                <input id="crud-form-1" type="text" class="form-control w-full" placeholder="Input text">
                            </div>
                            <div>
                                <label for="crud-form-1" class="form-label">Interest</label>
                                <input id="crud-form-1" type="text" class="form-control w-full" placeholder="Input text">
                            </div>
                            <div>
                                <label for="crud-form-1" class="form-label">Agent Name</label>
                                <input id="crud-form-1" type="text" class="form-control w-full" placeholder="Input text">
                            </div>
                            <div>
                                <label for="crud-form-1" class="form-label">Commission</label>
                                <input id="crud-form-1" type="text" class="form-control w-full" placeholder="Input text">
                            </div>
                            <div class="mt-3">
                            <label for="group-agent" class="form-label">Group Agent</label>
                                <select id="group-agent" name="group_agent" class="form-select w-full">
                                    <option value="">Select an option</option>
                                    <option value="1">Party A/C</option>
                                    <option value="2">xxx</option>
                                    <option value="3">YYY</option>
                                    <option value="4">ZZZ</option>
                                </select>
                                </div>
                                <div>
                                <label for="crud-form-1" class="form-label">Pkt</label>
                                <input id="crud-form-1" type="text" class="form-control w-full" placeholder="Input text">
                            </div>
                            <div>
                                <label for="crud-form-1" class="form-label">VoterId/Pan</label>
                                <input id="crud-form-1" type="text" class="form-control w-full" placeholder="Input text">
                            </div>
                            <div class="text-right mt-5">
                                <button type="button" class="btn btn-outline-primary w-24 mr-1">Edit</button>

                                <button type="button" class="btn btn-primary w-24">New</button>
                                <button type="button" class="btn btn-primary w-24">Save</button>
                                <button type="button" class="btn btn-primary w-24">Delete</button>
                            </div>