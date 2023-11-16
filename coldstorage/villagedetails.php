<!DOCTYPE html>
<html lang="en" class="light">
<head>
    <meta charset="utf-8">
    <link href="dist/images/logo.svg" rel="shortcut icon">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Midone admin is super flexible, powerful, clean & modern responsive tailwind admin template with unlimited possibilities.">
    <meta name="keywords" content="admin template, Midone Admin Template, dashboard template, flat admin template, responsive admin template, web app">
    <meta name="author" content="LEFT4CODE">
    <title>CRUD Form - Midone - Tailwind HTML Admin Template</title>
    <link rel="stylesheet" href="dist/css/app.css" />
</head>
<body class="py-5">
    <div class="flex mt-[4.7rem] md:mt-0">
        <?php include 'sidemenu.php' ?>
        <div class="content">
            <?php include 'topbar.php' ?>
            <div class="intro-y flex items-center mt-8">
                <h2 class="text-lg font-medium mr-auto">Village</h2>
            </div>
            <div class="grid grid-cols-12 gap-6 mt-5">
                <div class="intro-y col-span-12 lg:col-span-12">
                    <form action="villagedetails.php" method="POST" id="frm_user">
                        <div class="intro-y box p-5">
                            <div>
                                <label for="txt_vill" class="form-label">Village</label>
                                <input id="txt_vill" name="txt_vill" type="text" class="form-control w-full" placeholder="Enter your Village">
                            </div>
                            <div class="mt-3">
                                <label for="txt_post" class="form-label">Post</label>
                                <div class="input-group">
                                    <input id="txt_post" name="txt_post" type="text" class="form-control" aria-describedby="input-group-1" placeholder="Enter your Post">
                                </div>
                            </div>
                            <div class="mt-3">
                                <label for="txt_anchal" class="form-label">Anchal</label>
                                <div class="input-group">
                                    <input id="txt_anchal" name="txt_anchal" type="text" class="form-control"  aria-describedby="input-group-1" placeholder="Enter your Anchal">
                                </div>
                            </div>
                            <div class="mt-3">
                                <label for="txt_dist" class="form-label">District</label>
                                <div class="input-group">
                                    <input id="txt_dist" name="txt_dist" type="text" class="form-control"  aria-describedby="input-group-2" placeholder="Enter your District">
                                </div>
                            </div>
                            <div class="text-right mt-5">
                                <button type="submit" name="submit"id="btn_save" class="btn btn-primary w-24">Save</button>
                                <button type="button" class="btn btn-primary w-24">New</button>
                                <button type="button" class="btn btn-outline-secondary w-24 mr-1">Edit</button>
                                <button type="button" class="btn btn-primary w-24">Delete</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_GOOGLE_MAPS_API_KEY&libraries=places"></script>
    <script src="dist/js/app.js"></script>
</body>
<script>  
    $("#btn_save").on("click", function() {	
        const form = $("#frm_user");
        const formData = form.serialize();

        $.ajax({
            url: form.attr("action"),
            type: form.attr("method"),
            data: formData,
            success: function(data) {
                if (data.status == "Ok") {
                    console.log("Form data submitted successfully");
                }
            },
            error: function(error) {
                console.error("Error submitting form data", error);
            },
        });
    });
</script>
</html>
