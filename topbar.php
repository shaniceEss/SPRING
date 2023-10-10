<h3 class="text-center">SPRING NEIGHBOURHOOD</h3>
<div class="float-right">

    <div class="dropdown mr-4">
        <a href="#" class="text-dark dropdown-toggle"  id="account_settings" data-toggle="dropdown" aria-haspopup="true"
           aria-expanded="false"><?php echo $_SESSION['login_name'] ?> </a>
          <div class="dropdown-menu" aria-labelledby="account_settings" style="left: -2.5em;">
            <a class="dropdown-item" href="javascript:void(0)" id="manage_my_account"><i class="fa fa-cog"></i> Manage Account</a>
            <a class="dropdown-item" href="ajax.php?action=logout"><i class="fa fa-power-off"></i> Logout</a>
          </div>
    </div>

</div>

<script>
  $('#manage_my_account').click(function(){
    uni_modal("Manage Account","manage_user.php?id=<?php echo $_SESSION['login_id'] ?>&type=own")
  })
</script>
