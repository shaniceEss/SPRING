<?php
session_start();

if(!isset($_SESSION['login_id']))
    header('location:login.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo isset($_SESSION['system']['name']) ? $_SESSION['system']['name'] : '' ?></title>
    <?php
    include('./header.php');
//    include('./auth.php');
    ?>
    <style>
        @import "https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700";body{font-family: 'Poppins', sans-serif;background: #fafafa}p{font-size: 1.1em;font-weight: 300;line-height: 1.7em;color:#999}a, a:hover, a:focus{color:inherit;text-decoration: none;transition: all 0.3s}.navbar{padding:15px 10px;background: #fff;border:none;border-radius: 0;margin-bottom: 40px;box-shadow: 1px 1px 3px rgba(0,0,0,0.1)}.navbar-btn{box-shadow: none;outline:none !important;border:none}.line{width:100%;height:1px;border-bottom: 1px dashed #ddd}.wrapper{display: flex;width:100%;align-items:stretch}#sidebar{min-width:250px;max-width: 250px;background: #005086;color:#fff;transition: all 0.3s}#sidebar.active{margin-left:-250px}#sidebar .sidebar-header{padding:20px;background: #005086}#sidebar ul.components{padding:20px 0px;border-bottom:1px solid #47748b}#sidebar ul p{padding:10px;font-size:15px;display: block;color:#fff}#sidebar ul li a{padding:10px;font-size: 1.1em;display: block}#sidebar ul li a:hover{color:#fff;background: #318fb5}#sidebar ul li.active>a, a[aria-expanded="true"]{color:#fff;background: #318fb5}a[data-toggle="collapse"]{position: relative}.dropdown-toggle::after{display: block;position: absolute;top:50%;right:20px;transform: translateY(-50%)}ul ul a{font-size:0.9em !important;padding-left: 30px !important;background: #318fb5}ul.CTAs{padding:20px}ul.CTAs a{text-align: center;font-size:0.9em !important;display: block;border-radius: 5px;margin-bottom:5px}a.download, a.download:hover{background:#318fb5;color:#fff}#content{width:100%;padding:20px;min-height: 100vh;transition: all 0.3s}.content-wrapper{padding:15px}@media(maz-width:768px){#sidebar{margin-left:-250px}#sidebar.active{margin-left:0px}#sidebarCollapse span{display:none}}
    </style>
    <style>
        .modal-dialog.large {
            width: 80% !important;
            max-width: unset;
        }
        .modal-dialog.mid-large {
            width: 50% !important;
            max-width: unset;
        }
        #viewer_modal .btn-close {
            position: absolute;
            z-index: 999999;
            /*right: -4.5em;*/
            background: unset;
            color: white;
            border: unset;
            font-size: 27px;
            top: 0;
        }
        #viewer_modal .modal-dialog {
            width: 80%;
            max-width: unset;
            height: calc(90%);
            max-height: unset;
        }
        #viewer_modal .modal-content {
            background: black;
            border: unset;
            height: calc(100%);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        #viewer_modal img,#viewer_modal video{
            max-height: calc(100%);
            max-width: calc(100%);
        }
    </style>
</head>
<body>
<!-- main content -->
<div class="wrapper">
    <!-- Sidebar -->
    <nav id="sidebar">
        <div class="sidebar-header">
            <h3>SPRING MENU</h3>
        </div>

        <ul class="list-unstyled components">
            <?php include "navbar.php" ?>
        </ul>

    </nav>
    <!-- Page Content -->
    <div id="content">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">

                <button type="button" id="sidebarCollapse" class="btn btn-info">
                    <i class="fas fa-toggle-on"></i>
                    <span>Menu</span>
                </button>
                <?php include "topbar.php" ?>
            </div>
        </nav>
        <main id="view-panel">
            <?php $page = isset($_GET['page']) ? $_GET['page'] :'home'; ?>
            <?php include $page.'.php' ?>
        </main>
    </div>
</div>
<!-- end main body -->

<div id="preloader"></div>
<a href="#" class="back-to-top"><i class="icofont-simple-up"></i></a>

<div class="modal fade" id="confirm_modal" role='dialog'>
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmation</h5>
            </div>
            <div class="modal-body">
                <div id="delete_content"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id='confirm' onclick="">Continue</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="uni_modal" role='dialog'>
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id='submit' onclick="$('#uni_modal form').submit()">Save</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="viewer_modal" role='dialog'>
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <button type="button" class="btn-close" data-dismiss="modal"><span class="fa fa-times"></span></button>
            <img src="" alt="">
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        $("#sidebarCollapse").on('click', function(){
            $("#sidebar").toggleClass('active');
        });
    });
</script>
<script>
    window.start_load = function(){
        $('body').prepend('<di id="preloader2"></di>')
    }
    window.end_load = function(){
        $('#preloader2').fadeOut('fast', function() {
            $(this).remove();
        })
    }
    window.viewer_modal = function($src = ''){
        start_load()
        var t = $src.split('.')
        t = t[1]
        if(t =='mp4'){
            var view = $("<video src='"+$src+"' controls autoplay></video>")
        }else{
            var view = $("<img src='"+$src+"' />")
        }
        $('#viewer_modal .modal-content video,#viewer_modal .modal-content img').remove()
        $('#viewer_modal .modal-content').append(view)
        $('#viewer_modal').modal({
            show:true,
            backdrop:'static',
            keyboard:false,
            focus:true
        })
        end_load()

    }
    window.uni_modal = function($title = '' , $url='',$size=""){
        start_load()
        $.ajax({
            url:$url,
            error:err=>{
                console.log()
                alert("An error occured")
            },
            success:function(resp){
                if(resp){
                    $('#uni_modal .modal-title').html($title)
                    $('#uni_modal .modal-body').html(resp)
                    if($size != ''){
                        $('#uni_modal .modal-dialog').addClass($size)
                    }else{
                        $('#uni_modal .modal-dialog').removeAttr("class").addClass("modal-dialog modal-md")
                    }
                    $('#uni_modal').modal({
                        show:true,
                        backdrop:'static',
                        keyboard:false,
                        focus:true
                    })
                    end_load()
                }
            }
        })
    }
    window._conf = function($msg='',$func='',$params = []){
        $('#confirm_modal #confirm').attr('onclick',$func+"("+$params.join(',')+")")
        $('#confirm_modal .modal-body').html($msg)
        $('#confirm_modal').modal('show')
    }
    window.alert_toast= function($msg = 'TEST',$bg = 'success'){
        $('#alert_toast').removeClass('bg-success')
        $('#alert_toast').removeClass('bg-danger')
        $('#alert_toast').removeClass('bg-info')
        $('#alert_toast').removeClass('bg-warning')

        if($bg == 'success')
            $('#alert_toast').addClass('bg-success')
        if($bg == 'danger')
            $('#alert_toast').addClass('bg-danger')
        if($bg == 'info')
            $('#alert_toast').addClass('bg-info')
        if($bg == 'warning')
            $('#alert_toast').addClass('bg-warning')
        $('#alert_toast .toast-body').html($msg)
        $('#alert_toast').toast({delay:3000}).toast('show');
    }
    $(document).ready(function(){
        $('#preloader').fadeOut('fast', function() {
            $(this).remove();
        })
    })
    $('.datetimepicker').datetimepicker({
        format:'Y/m/d H:i',
        startDate: '+3d'
    })
    $('.select2').select2({
        placeholder:"Please select here",
        width: "100%"
    })
</script>
</body>
</html>