<?php

session_start();
include('system/globalfunctions.php');
checkSystemSession();

?>



<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Bagsed-It!</title>

    <!-- Bootstrap Core CSS -->
    <link href="bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="bower_components/metisMenu/dist/metisMenu.min.css" rel="stylesheet">

    <!-- Timeline CSS -->
    <link href="dist/css/timeline.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="dist/css/sb-admin-2.css" rel="stylesheet">

    <!-- ServerTools Custom CSS -->
    <link href="dist/css/stcustom.css" rel="stylesheet">

    <!-- Morris Charts CSS -->
    <link href="bower_components/morrisjs/morris.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    <script src="//code.jquery.com/jquery-1.11.2.min.js"></script>

    <script type="text/javascript">    
    $(window).load(function(){
        <?php if($CurrentUserId=="0") { ?>
            loadScript('publichome');
           // $('#loginToBagsedit').modal('show');     // this will show the login modal if ServerTools isn't signed in
        <?php } else { ?>
            loadScript('dashboard');
        <?php } ?>
    });


    function ajaxRequest(path) {

        var xmlhttp;
        xmlhttp=new XMLHttpRequest();
     
        xmlhttp.open("GET",path,false);
        xmlhttp.send();
        var RESPONSE = xmlhttp.responseText;
          
        return RESPONSE;
        delete window.xmlhttp;

    }


    function loadScript(ScriptAlias,Params) {

        Params = typeof Params !== 'undefined' ? Params : "param2:value2;param3:value3";

        document.getElementById('loadingdiv').style.visibility="visible"

        var scriptoutputdata = ajaxRequest("system/internaltarget.php?Action=requestScript&ScriptAlias="+ScriptAlias+"&Params=" + Params);

        if(scriptoutputdata.indexOf("<ERR>") > -1) {
            $('#errorDisplay').modal('show');
            var ErrorDescription = ajaxRequest("system/internaltarget.php?Action=requestErrorInfo&ErrCode="+scriptoutputdata+"&Field=Description");
            var ErrorSolution = ajaxRequest("system/internaltarget.php?Action=requestErrorInfo&ErrCode="+scriptoutputdata+"&Field=Solution");
            document.getElementById("errDescription").innerHTML = ErrorDescription
            document.getElementById("errSolution").innerHTML = ErrorSolution

        }
        else {
            document.getElementById('page-wrapper').innerHTML = scriptoutputdata;
        }

        

        document.getElementById('loadingdiv').style.visibility="hidden"
       


    }

    function submitLogin() {

        var UsernameEmail = document.getElementById('Username').value;
        var UserPassword = document.getElementById('Password').value;

        var LoginResponse = ajaxRequest("system/internaltarget.php?Action=signIn&Username="+UsernameEmail+"&Password="+UserPassword);

        if(LoginResponse=="<ERR>System.InternalTarget.signIn.AccessDenied") {
            $('#loginToBagsedit').modal('hide');
            $('#errorDisplay').modal('show');

            var ErrorDescription = ajaxRequest("system/internaltarget.php?Action=requestErrorInfo&ErrCode="+LoginResponse+"&Field=Description");
            var ErrorSolution = ajaxRequest("system/internaltarget.php?Action=requestErrorInfo&ErrCode="+LoginResponse+"&Field=Solution");
            document.getElementById("errDescription").innerHTML = ErrorDescription
            document.getElementById("errSolution").innerHTML = ErrorSolution
            document.getElementById("errDismiss").onclick=showLoginScreen;
        }

        else {
            $('#loginToBagsedit').modal('hide');
            loadScript('dashboard');

        }

       

    }

    function submitLoginForm() {

        var UsernameEmail = document.getElementById('EmailAddress').value;
        var UserPassword = document.getElementById('Password2').value;

        var LoginResponse = ajaxRequest("system/internaltarget.php?Action=signIn&Username="+UsernameEmail+"&Password="+UserPassword);

        if(LoginResponse=="<ERR>System.InternalTarget.signIn.AccessDenied") {
            
            $('#errorDisplay').modal('show');

            var ErrorDescription = ajaxRequest("system/internaltarget.php?Action=requestErrorInfo&ErrCode="+LoginResponse+"&Field=Description");
            var ErrorSolution = ajaxRequest("system/internaltarget.php?Action=requestErrorInfo&ErrCode="+LoginResponse+"&Field=Solution");
            document.getElementById("errDescription").innerHTML = ErrorDescription
            document.getElementById("errSolution").innerHTML = ErrorSolution
            document.getElementById("errDismiss").onclick=showLoginScreen;
        }

        else {

            loadScript('dashboard');

        }

       

    }

    function showLoginScreen() {
            $('#loginToBagsedit').modal('show');
            $('#errorDisplay').modal('hide');
    }

    function doSearch() {



        $('#searchResults').modal('show');

        var retval = ajaxRequest("system/searchresults.php?q=" + document.getElementById('searchQ').value);
        document.getElementById('searchResultsArea').innerHTML = retval;


    }

    function closeSearch() {
        $('#searchResults').modal('hide');

    }
	</script>

</head>

<body>

	<div class="modal fade" id="loginToBagsedit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	    <div class="modal-dialog">
	        <div class="modal-content">
	            <div class="modal-header">
	                <!--button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button-->
	                <h4 class="modal-title" id="myModalLabel">Sign in to ServerTools</h4>
	            </div>
	            <div class="modal-body" align="center">
	            	<div style="width:300px;" align="left">
	                <form role="form">
                            <fieldset>
                                <div class="form-group">
                                    Username or Email Address <input class="form-control" placeholder="Username" id="Username" name="username" type="text" autofocus>
                                </div>
                                <div class="form-group">
                                    Password <input class="form-control" placeholder="Password" name="password" type="password" id="Password" value="">
                                </div>
                      
                            </fieldset>
                        </form>
                    </div>
	            </div>
	            <div class="modal-footer">
	                <button type="button" class="btn btn-default">Quit</button>
	                <!--button type="button" class="btn btn-default" data-dismiss="modal">Quit</button-->
	                <button type="button" class="btn btn-primary" onclick="submitLogin()">Sign In</button>
	            </div>
	        </div>
	    </div>
	</div>
    <div class="modal fade" id="searchResults" tabindex="-1" role="dialog" aria-labelledby="searchreslabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <!--button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button-->
                    <h4 class="modal-title" id="searchreslabel">Search Results</h4>
                </div>
                <div class="modal-body" align="left">
                    
                        <div id="searchResultsArea"></div>
                  
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" onclick="closeSearch()">Close Search</button>
                    
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="errorDisplay" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <!--button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button-->
                    <h4 class="modal-title" id="myModalLabel2">An error has occured...</h4>
                </div>
                <div class="modal-body">
                    <p><strong><span id="errDescription"></span></strong></p>
                    <p><i>Solution:</i><br><span id="errSolution"></span></p>

                </div>
                <div class="modal-footer">
                    
                    <!--button type="button" class="btn btn-default" data-dismiss="modal">Quit</button-->
                    <button type="button" class="btn btn-primary" onclick="$('#errorDisplay').modal('hide');" id="errDismiss">Dismiss</button>
                </div>
            </div>
        </div>
    </div>
    <div id="loadingdiv">Loading...</div>
    <div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#" onclick="loadScript('publichome')">Bagsed-It!</a>
            </div>
            <!-- /.navbar-header -->

            <ul class="nav navbar-top-links navbar-right">
                <li class="dropdown">
                   
                    <ul class="dropdown-menu dropdown-messages">
                        <li>
                            <a href="#">
                                <div>
                                    <strong>John Smith</strong>
                                    <span class="pull-right text-muted">
                                        <em>Yesterday</em>
                                    </span>
                                </div>
                                <div>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque eleifend...</div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="#">
                                <div>
                                    <strong>John Smith</strong>
                                    <span class="pull-right text-muted">
                                        <em>Yesterday</em>
                                    </span>
                                </div>
                                <div>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque eleifend...</div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="#">
                                <div>
                                    <strong>John Smith</strong>
                                    <span class="pull-right text-muted">
                                        <em>Yesterday</em>
                                    </span>
                                </div>
                                <div>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque eleifend...</div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a class="text-center" href="#">
                                <strong>Read All Messages</strong>
                                <i class="fa fa-angle-right"></i>
                            </a>
                        </li>
                    </ul>
                    <!-- /.dropdown-messages -->
                </li>
                <!-- /.dropdown -->
                
                <!-- /.dropdown -->
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-bell fa-fw"></i>  <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-alerts">
                        <li>
                            <a href="#">
                                <div>
                                    <i class="fa fa-comment fa-fw"></i> New Comment
                                    <span class="pull-right text-muted small">4 minutes ago</span>
                                </div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="#">
                                <div>
                                    <i class="fa fa-twitter fa-fw"></i> 3 New Followers
                                    <span class="pull-right text-muted small">12 minutes ago</span>
                                </div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="#">
                                <div>
                                    <i class="fa fa-envelope fa-fw"></i> Message Sent
                                    <span class="pull-right text-muted small">4 minutes ago</span>
                                </div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="#">
                                <div>
                                    <i class="fa fa-tasks fa-fw"></i> New Task
                                    <span class="pull-right text-muted small">4 minutes ago</span>
                                </div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="#">
                                <div>
                                    <i class="fa fa-upload fa-fw"></i> Server Rebooted
                                    <span class="pull-right text-muted small">4 minutes ago</span>
                                </div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a class="text-center" href="#">
                                <strong>See All Alerts</strong>
                                <i class="fa fa-angle-right"></i>
                            </a>
                        </li>
                    </ul>
                    <!-- /.dropdown-alerts -->
                </li>
                <!-- /.dropdown -->
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-user fa-fw"></i>  <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li><a href="#"><i class="fa fa-user fa-fw"></i> User Profile</a>
                        </li>
                        <li><a href="#"><i class="fa fa-gear fa-fw"></i> Settings</a>
                        </li>
                        <li class="divider"></li>
                        <li><a href="login.html"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                        </li>
                    </ul>
                    <!-- /.dropdown-user -->
                </li>
                <!-- /.dropdown -->
            </ul>
            <!-- /.navbar-top-links -->

            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                        <li class="sidebar-search">
                            <div class="input-group custom-search-form">
                                <input type="text" class="form-control" placeholder="Search..." id="searchQ">
                                <span class="input-group-btn">
                                <button class="btn btn-default" type="button" id="searchbutton" onclick="doSearch()">
                                    <i class="fa fa-search"></i>
                                </button>
                            </span>
                            </div>
                            <!-- /input-group -->
                        </li>
                        <li onclick="loadScript('dashboard')">
                            <a href="#"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a>
                        </li>
                        <!--li>
                            <a href="#"><i class="fa fa-bar-chart-o fa-fw"></i> Charts<span class="fa arrow"></span></a>
                            <!--ul class="nav nav-second-level">
                                <li>
                                    <a href="flot.html">Flot Charts</a>
                                </li>
                                <li>
                                    <a href="morris.html">Morris.js Charts</a>
                                </li>
                            </ul>
                            <!-- /.nav-second-level -->
                        <!--/li>
                        <li>
                            <a href="tables.html"><i class="fa fa-table fa-fw"></i> Tables</a>
                        </li-->
                        <li>
                            <a href="#"><i class="fa fa-edit fa-fw"></i> Create a Booking</a>
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-wrench fa-fw"></i> Resources Setup</a>
                            <!--ul class="nav nav-second-level">
                                <li>
                                    <a href="panels-wells.html">Panels and Wells</a>
                                </li>
                                <li>
                                    <a href="buttons.html">Buttons</a>
                                </li>
                                <li>
                                    <a href="notifications.html">Notifications</a>
                                </li>
                                <li>
                                    <a href="typography.html">Typography</a>
                                </li>
                                <li>
                                    <a href="icons.html"> Icons</a>
                                </li>
                                <li>
                                    <a href="grid.html">Grid</a>
                                </li>
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-calendar fa-fw"></i> Calendar</span></a>
                            <!--ul class="nav nav-second-level">
                                <li>
                                    <a href="#">Second Level Item</a>
                                </li>
                                <li>
                                    <a href="#">Second Level Item</a>
                                </li>
                                <li>
                                    <a href="#">Third Level <span class="fa arrow"></span></a>
                                    <ul class="nav nav-third-level">
                                        <li>
                                            <a href="#">Third Level Item</a>
                                        </li>
                                        <li>
                                            <a href="#">Third Level Item</a>
                                        </li>
                                        <li>
                                            <a href="#">Third Level Item</a>
                                        </li>
                                        <li>
                                            <a href="#">Third Level Item</a>
                                        </li>
                                    </ul>
                                    <!-- /.nav-third-level 
                                </li>
                            </ul-->
                            <!-- /.nav-second-level -->
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-user fa-fw"></i> My Account</a>
                            <!--ul class="nav nav-second-level">
                                <li>
                                    <a href="blank.html">Blank Page</a>
                                </li>
                                <li>
                                    <a href="login.html">Login Page</a>
                                </li>
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->
        </nav>

        <div id="page-wrapper">
           &nbsp;  
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- jQuery -->
    <script src="bower_components/jquery/dist/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="bower_components/metisMenu/dist/metisMenu.min.js"></script>

    <!-- Morris Charts JavaScript -->
    <script src="bower_components/raphael/raphael-min.js"></script>
    <script src="bower_components/morrisjs/morris.min.js"></script>
    <script src="js/morris-data.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="dist/js/sb-admin-2.js"></script>

</body>

</html>
