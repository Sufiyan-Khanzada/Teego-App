<?php

require '../vendor/autoload.php';
//include '../Configs.php';

use Parse\ParseException;
use Parse\ParseQuery;
use Parse\ParseUser;


//session_start();

$currUser = ParseUser::getCurrentUser();
if ($currUser){

    // Store current user session token, to restore in case we create new user
    $_SESSION['token'] = $currUser -> getSessionToken();
} else {

    header("Refresh:0; url=../index.php");
}

function array_get_by_index($index, $array) {

    $i=0;
    foreach ($array as $value) {
        if($i==$index) {
            return $value;
        }
        $i++;
    }
    // may be $index exceedes size of $array. In this case NULL is returned.
    return NULL;
}

?>

<div class="page-wrapper">
    <!-- Bread crumb -->
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-primary">All Users </h3> </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Users</a></li>
                <li class="breadcrumb-item active">All Users </li>
            </ol>
        </div>
    </div>

    <!-- Container fluid  -->
    <div class="container-fluid">
        <!-- Start Page Content -->
        <div class="row bg-white m-l-0 m-r-0 box-shadow ">

        </div>
        <div class="row">
            <div class="col-lg">
               <div class="card">

                    <h5 class="card-subtitle">Copy or Export CSV, Excel, PDF and Print data</h5>
                    <div class="card-body">
                        <div class="table-responsive">

                            <table id="example23" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>Action</th>
                                    <th>ObjectId</th>
                                    <th>Name</th>
                                    <th>Username</th>
                                    <th>Avatar</th>
                                    <th>Gender</th>
                                    <th>Bithday</th>
                                    <th>Age</th>
                                    <th>Location</th>
                                    <th>Status</th>
                                    <th>Activation</th>
                                    <th>Action</th>
                                    
                                </tr>
                                </thead>
                                <tbody>

                                <?php
                                try {

                                    $currUser = ParseUser::getCurrentUser();
                                    $cuObjectID = $currUser->getObjectId();

                                    $query = new ParseQuery("_User");
                                    $query->descending('createdAt');
                                    $catArray = $query->find(false);

                                    foreach ($catArray as $iValue) {
                                        // Get Parse Object
                                        $cObj = $iValue;

                                        $objectId = $cObj->getObjectId();

                                        $name = $cObj->get('name');
                                        $username = $cObj->get('username');
                                        $email = $cObj->get('email');

                                        if ($cObj->get("avatar") !== null) {
                                            $photos = $cObj->get('avatar');

                                            $profilePhotoUrl = $photos->getURL();

                                            $avatar = "<span/><a target='_blank' href=\"$profilePhotoUrl\" class=\"badge badge-info\">Download</a></span>";
                                        } else {
                                            $avatar = "<span/><a class=\"badge badge-warning\">No Avatar</a></span>";
                                        }

                                        $gender = $cObj->get('gender');

                                        if ($gender === "male"){
                                            $UserGender = "Male";
                                        } else if ($gender === "female"){
                                            $UserGender = "Female";
                                        } else {
                                            $UserGender = "Private";
                                        }


                                        $birthday= $cObj->get('birthday');

                                        if ($birthday != null) {
                                            $birthDate = date_format($birthday, "d/m/Y");
                                            $age = $cObj->get('age');
                                        } else {
                                            $birthDate = "<span class=\"badge badge-warning\">Unavailable</span>";
                                            $age = "<span class=\"badge badge-warning\">Unavailable</span>";
                                        }


                                        $verified = $cObj->get('emailVerified');
                                        if ($verified == false){
                                            $verification = "<span class=\"badge badge-warning\">UNVERIFIED</span>";
                                        } else {
                                            $verification = "<span class=\"badge badge-success\">VERIFED</span>";
                                        }

                                        $locaton = $cObj->get('location');
                                        if ($locaton == null){
                                            $city_location = "<span class=\"badge badge-warning\">Unavailable</span>";
                                        } else{
                                            $city_location = "<span class=\"badge badge-info\">$locaton</span>";
                                        }

                                        $activation = $cObj->get('activationStatus');
                                        if ($activation == false){
                                            $active = "<span class=\"badge badge-warning\">SUSPENDED</span>";
                                        } else {
                                            $active = "<span class=\"badge badge-success\">ENABLED</span>";
                                        }

                                        echo '
		            	
		            	        <tr>
		            	            <td><a href="../dashboard/edit_user.php?objectId='.$objectId.'" target="_blank" <span class="badge badge-warning">Edit user</span></a></td>
                                    <td>'.$objectId.'</td>
                                    <td>'.$name.'</td>
                                    <td>'.$username.'</td>
                                    <td>'.$avatar.'</td>
                                    <td><span>'.$UserGender.'</span></td>
                                    <td><span>'.$birthDate.'</span></td>
                                    <td>'.$age.'</td>
                                    <td>'.$city_location.'</td>
                                    <td>'.$verification.'</td>
                                    <td>'.$active.'</td>
                                    <td>
                <a href="index.php?edit=#?>" class="edit_btn" >Edit</a>
            
                 <a href="server.php?del=#" class="del_btn">Delete</a>
            </td>
            

                                </tr>
                                
                                ';
                                    }
                                    // error in query
                                } catch (ParseException $e){ echo $e->getMessage(); }
                                ?>

                                </tbody>
                            </table>
                        </div>
                    </div>



                </div>
            </div>
        </div>

        <!-- End PAge Content -->
    </div>
    <!-- End Container fluid  -->
    <!-- footer -->

    <!-- End footer -->
</div>
