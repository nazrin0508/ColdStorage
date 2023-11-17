<?php
//check if the form is submitted
if($_SERVER["REQUEST_METHOD"]=="POST"){
    $item_name = $_POST['txt_itm'];
    $contact_name = $_POST['txt_cont'];
    $srn_name = $_POST['txt_SRN'];
    $sr_name = $_POST['txt_SR'];
    $ac_name = $_POST['txt_ac'];
    // $acn_name = $_POST['txt_acn'];
    // $hir_name = $_POST['txt_hir'];
    // $addr_name = $_POST['txt_addr'];
    // $post_name = $_POST['txt_post'];
    // $anch_name = $_POST['txt_anch'];
    // $dist_name = $_POST['txt_dist'];
    // $lot_name = $_POST['txt_lot'];
    // $rem_name = $_POST['txt_rem'];
    // $rs_name = $_POST['txt_rs'];
    // $wt_name = $_POST['txt_wt'];
    // $txt1_name = $_POST['txt_1'];
    // $txt2_name = $_POST['txt_2'];
    // $sh_name = $_POST['txt_sh'];
    // $lod_name = $_POST['txt_lod'];
    // $sup_name = $_POST['txt_sup'];
    // $lory_name = $_POST['txt_lory'];
    // $advn_name = $_POST['txt_advn'];
    // $recv_name = $_POST['txt_rec'];
    // $depo_name = $_POST['txt_depo'];
    // $oth_name = $_POST['txt_oth'];
    // $con_name = $_POST['txt_con'];
    // $loryf_name = $_POST['txt_loryf'];
    // $recv_name = $_POST['txt_recv'];
    // $kanta_name = $_POST['txt_kanta'];


    //Insert data into the database (use prepared statements to prevent SQL injection)
 //$query = "INSERT INTO amdanisrbond (item, contact,SRNo (1),SRNo (2)) VALUES ('$item_name', '$contact_name','$srn_name','$sr_name','$ac_name')";
// // Add other fields to the SQL query

 //$data = mysqli_query($conn,$query);
 //if($data)
 //{
   //  echo "data inserted";
   
//}
 //$stmt = $conn->prepare("INSERT INTO amdanisrbond(item,contact,SRNO(1),SRNO(2)VALUES(?,?,?,?)");



// //EXECUTE THE QUERY
//if($stmt->execute()){
     //echo"record inserted successfully";

 //}else{
     //echo"Error:" .$stmt->error;

 //}

//$conn = mysqli_connect("localhost","root","","coldstoragedb") or die("connection failed");

  
    
 }
// ?>
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
                     Amdani
                    </h2>
                </div>
                <form action="#" method="POST">
                <div class="grid grid-cols-12 gap-6 mt-5">
                    <div class="intro-y col-span-12 lg:col-span-12">
                        <!-- BEGIN: Form Layout -->
                        
                        <div class="intro-y box p-5">
                            <div>
                                <label for="txt_itm" class="form-label">Item</label>
                                <select id="txt_itm" name="txt_itm">
            
                         <option value="dropdown">Potato</option>
            
        </select>
                            </div>
                            <div class="mt-3">
                            <label for="txt_cont" class="form-label">Contact</label>
                                 <div class="input-group">
                                
                                    <input id="txt_cont" name="txt_cont" type="number" class="form-control" aria-describedby="input-group-1" placeholder="Enter your number">
                                   
                                </div>
                            </div>
                           
                            <div class="mt-3">
                                <label for="txt_SRN" class="form-label">SRNo (1)</label>
                                <div class="input-group">
                                    <input id="txt_SRN"  name="txt_SRN"type="number" class="form-control"  aria-describedby="input-group-1" placeholder="Enter your SRNo (1)">
                                   
                                </div>
                            </div>
                            <div class="mt-3">
                                <label for="txt_SR" class="form-label">SRNo (2)</label>
                                <div class="input-group">
                                    <input id="txt_SR"  name="txt_SR"type="number" class="form-control"  aria-describedby="input-group-2" placeholder="Enter your SRNo (2)">
                              
                                </div>
                            </div>
                            <div class="mt-3">
                                <label for="txt_ac" class="form-label">A/c Code</label>
                                <div class="input-group">
                                    <input id="txt_ac"  name="txt_ac"type="number" class="form-control"  aria-describedby="input-group-2" >
                              
                                </div>
                             </div>
                             <div class="mt-3">
                                <label for="txt_acn" class="form-label">A/c Name</label>
                                <div class="input-group">
                                    <input id="txt_acn"  name="txt_acn"type="text" class="form-control"  aria-describedby="input-group-2" >
                              
                                </div>
                            </div>
                            <div class="mt-3">
                                <label for="txt_hir" class="form-label">Hirer Name</label>
                                <div class="input-group">
                                    <input id="txt_hir"  name="txt_hir"type="text" class="form-control"  aria-describedby="input-group-2" >
                              
                                </div>
                            </div>
                            <div class="mt-3">
                                <label for="txt_addr" class="form-label">Address</label>
                                <div class="input-group">
                                    <input id="txt_addr"  name="txt_addr"type="text" class="form-control"  aria-describedby="input-group-2" >
                              
                                </div>
                            </div>
                            <div class="mt-3">
                                <label for="txt_post" class="form-label">Post</label>
                                <div class="input-group">
                                    <input id="txt_post"  name="txt_post"type="text" class="form-control"  aria-describedby="input-group-2" >
                              
                                </div>
                            </div>
                            <div class="mt-3">
                                <label for="txt_anch" class="form-label">Anchal</label>
                                <div class="input-group">
                                    <input id="txt_anch"  name="txt_anch"type="text" class="form-control"  aria-describedby="input-group-2" >
                              
                                </div>
                            </div>
                            <div class="mt-3">
                                <label for="txt_dist" class="form-label">District</label>
                                <div class="input-group">
                                    <input id="txt_dist"  name="txt_dist"type="text" class="form-control"  aria-describedby="input-group-2" >
                              
                                </div>
                            </div>
                            <div class="mt-3">
                                <label for="txt_lot" class="form-label">lot</label>
                                <div class="input-group">
                                    <input id="txt_lot"  name="txt_lot"type="text" class="form-control"  aria-describedby="input-group-2" >
                              
                                </div>
                            </div>
                            <div class="mt-3">
                                <label for="txt_rem" class="form-label">Remarks</label>
                                <div class="input-group">

                                    <select id="txt_rem" name="txt_rem"type="text" class="form-control">
            
            <option value="dropdown">Average</option>
            <option value="dropdown">Better</option>
            

</select>
                              
                                </div>
                            </div><br>
                            <div class="mt-3">
                                <label for="txt_rs" class="form-label">@Rs</label>
                                <div class="input-group">
                                    <input id="txt_rs"  name="txt_rs"type="text" class="form-control"  aria-describedby="input-group-2" >
                              
                                </div>
                            </div>
                            <div class="mt-3">
                                <label for="txt_wt" class="form-label">Weight</label>
                                <div class="input-group">
                                    <select id="txt_wt" name="txt_wt" type="number" class="form-control">
                                    <option>Select an option</option>
                                        <option>50</option>
                                        <option>40</option>
                                        <option>30</option>
                                        <option>20</option>
                                        <option>10</option>
                                        <option>5</option>
</select>
                                </div>
                            </div>
                            <div class="mt-3">
                                <label for="txt_1" class="form-label">1.</label>
                                <div class="input-group">
                                    <select id="txt_1" name="txt_1" type="number" class="form-control">
                                        <option>00</option>
                                        <option>40</option>
                                        <option>30</option>
                                        <option>20</option>
                                        <option>10</option>
                                        <option>5</option>
</select>
                                </div>
                            </div>
                            <div class="mt-3">
                                <label for="txt_2" class="form-label">2.</label>
                                <div class="input-group">
                                    <select id="txt_2" name="txt_2" type="number" class="form-control">
                                        <option>00</option>
                                        <option>40</option>
                                        <option>30</option>
                                        <option>20</option>
                                        <option>10</option>
                                        <option>5</option>
</select>
                                </div>
                            </div>
                            <div class="mt-3">
                                <label for="txt_sh" class="form-label">Shead</label>
                                <div class="input-group">
                                    
                                   
                                    <select id="txt_sh" name="txt_sh" type="number" class="form-control">
                                    <option>select</option>
                                        <option>1</option>
                                        <option>40</option>
                                        <option>30</option>
                                        <option>20</option>
                                        <option>10</option>
                                        <option>5</option>
</select>

                              
                                </div>
                            </div>
                            <div class="mt-3">
                                <label for="txt_lod" class="form-label">Loading@</label>
                                <div class="input-group">
                                    <input id="txt_lod"  name="txt_lod"type="text" class="form-control"  aria-describedby="input-group-2" >
                              
                                </div>
                            </div>
                            <div class="mt-3">
                                <label for="txt_sup" class="form-label">Supplier</label>
                                <div class="input-group">
                                    <input id="txt_sup"  name="txt_sup"type="text" class="form-control"  aria-describedby="input-group-2" >
                              
                                </div>
                            </div>
                            <div class="mt-3">
                                <label for="txt_lory" class="form-label">Lorry No.</label>
                                <div class="input-group">
                                    <input id="txt_lory"  name="txt_lory"type="text" class="form-control"  aria-describedby="input-group-2" >
                              
                                </div>
                            </div>
                            <div class="mt-3">
                                <label for="txt_advn" class="form-label">Total Advn</label>
                                <div class="input-group">
                                    <input id="txt_advn"  name="txt_advn"type="text" class="form-control"  aria-describedby="input-group-2" >
                              
                                </div>
                            </div>
                            <div class="mt-3">
                                <label for="txt_rec" class="form-label">Receiver</label>
                                <div class="input-group">
                                    <input id="txt_rec"  name="txt_rec"type="text" class="form-control"  aria-describedby="input-group-2" placeholder="COMPANY">
                              
                                </div>
                            </div>
                            <div class="mt-3">
                                <label for="txt_depo" class="form-label">Depositor</label>
                                <div class="input-group">
                                    <input id="txt_depo"  name="txt_depo"type="text" class="form-control"  aria-describedby="input-group-2">
                              
                                </div>
                            </div>
                            <div class="mt-3">
                                <label for="txt_oth" class="form-label">Oth Rmks</label>
                                <div class="input-group">
                                    <input id="txt_oth"  name="txt_oth"type="text" class="form-control"  aria-describedby="input-group-2">
                              
                                </div>
                            </div>
                            <div class="mt-3">
                                <label for="txt_con" class="form-label">Consignor</label>
                                <div class="input-group">
                                    <input id="txt_con"  name="txt_con"type="text" class="form-control"  aria-describedby="input-group-2">
                              
                                </div>
                            </div>
                            <div class="mt-3">
                                <label for="txt_loryf" class="form-label">Lorry Fare</label>
                                <div class="input-group">
                                    <input id="txt_loryf"  name="txt_lory"type="text" class="form-control"  aria-describedby="input-group-2">
                              
                                </div>
                            </div>
                            <div class="mt-3">
                                <label for="txt_chrg" class="form-label">Other Chrg</label>
                                <div class="input-group">
                                    <input id="txt_chrg"  name="txt_chrg"type="text" class="form-control"  aria-describedby="input-group-2">
                              
                                </div>
                            </div>
                            <div class="mt-3">
                                <label for="txt_recv" class="form-label">Recv.Dt</label>
                                <div class="input-group">
                                    <input id="txt_recv"  name="txt_recv"type="date" class="form-control"  aria-describedby="input-group-2">
                              
                                </div>
                            </div>
                            <div class="mt-3">
                                <label for="txt_kanta" class="form-label">Kanta Wgt</label>
                                <div class="input-group">
                                    <input id="txt_kanta"  name="txt_recv"type="number" class="form-control"  aria-describedby="input-group-2">
                              
                                </div>
                            </div>
                            
                            
                            
                            
                            
                            
                            
                            
                             
                            
                            
                            
                            
                            <div class="text-right mt-5">
                            <button type="submit" id="save" name="save" class="btn btn-primary w-24">Save</button>
                                <button type="button" class="btn btn-primary w-24">New</button>
                                <button type="button" class="btn btn-outline-secondary w-24 mr-1">Edit</button>
                                <button type="button" class="btn btn-primary w-24">Delete</button> 
                            </div>
                            
                        </div>
                        
                        <!-- END: Form Layout -->
                    </div>
                    
                </div>
                
            </div>
            
        
</form>
</body>
</html>