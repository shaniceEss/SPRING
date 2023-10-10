<?php include 'db_connect.php' ?>
<style>
   span.float-right.summary_icon {
    font-size: 3rem;
    position: absolute;
    right: 1rem;
    top: 0;
}
</style>

<div class="containe-fluid">
    <div class="row p-4">
        <?php echo "Welcome ". $_SESSION['login_name']."!"  ?>
        <p>
            <strong>
                A house rental management system is a comprehensive
                software solution designed to streamline and automate the
                process of managing rental properties. It offers a centralized
                platform that enables property owners, landlords, and property
                managers to efficiently handle various aspects of their rental business.
                The system facilitates tasks such as property listings, tenant screening,
                lease management, rent collection, maintenance tracking, and financial reporting.
                By utilizing this system, users can easily list available properties.
            </strong>
        </p>
    </div>

    <div class="card-columns">
        <div class="card border-primary">
            <div class="card-body bg-primary">
                <div class="card-body text-white">
                    <span class="float-right summary_icon"> <i class="fa fa-home "></i></span>
                    <h4><b>
                            <?php echo $conn->query("SELECT * FROM houses")->num_rows ?>
                        </b></h4>
                    <p><b>Total Houses</b></p>
                </div>
            </div>
            <div class="card-footer">
                <div class="row">
                    <div class="col-lg-12">
                        <a href="index.php?page=houses" class="text-primary float-right">View List <span class="fa fa-angle-right"></span></a>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-warning">
            <div class="card-body bg-warning">
                <div class="card-body text-white">
                    <span class="float-right summary_icon"> <i class="fa fa-user-friends "></i></span>
                    <h4><b>
                            <?php echo $conn->query("SELECT * FROM tenants where status = 1 ")->num_rows ?>
                        </b></h4>
                    <p><b>Total Tenants</b></p>
                </div>
            </div>
            <div class="card-footer">
                <div class="row">
                    <div class="col-lg-12">
                        <a href="index.php?page=tenants" class="text-primary float-right">View List <span class="fa fa-angle-right"></span></a>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-success">
            <div class="card-body bg-success">
                <div class="card-body text-white">
                    <span class="float-right summary_icon"> <i class="fa fa-file-invoice "></i></span>
                    <h4><b>
                            <?php
                            $payment = $conn->query("SELECT sum(amount) as paid FROM payments where date(date_created) = '".date('Y-m-d')."' ");
                            echo $payment->num_rows > 0 ? number_format($payment->fetch_array()['paid'],2) : 0;
                            ?>
                        </b></h4>
                    <p><b>Payments This Month</b></p>
                </div>
            </div>
            <div class="card-footer">
                <div class="row">
                    <div class="col-lg-12">
                        <a href="index.php?page=invoices" class="text-primary float-right">View Payments <span class="fa fa-angle-right"></span></a>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>
<script>
	$('#manage-records').submit(function(e){
        e.preventDefault()
        start_load()
        $.ajax({
            url:'ajax.php?action=save_track',
            data: new FormData($(this)[0]),
            cache: false,
            contentType: false,
            processData: false,
            method: 'POST',
            type: 'POST',
            success:function(resp){
                resp=JSON.parse(resp)
                if(resp.status==1){
                    alert_toast("Data successfully saved",'success')
                    setTimeout(function(){
                        location.reload()
                    },800)

                }
                
            }
        })
    })
    $('#tracking_id').on('keypress',function(e){
        if(e.which == 13){
            get_person()
        }
    })
    $('#check').on('click',function(e){
            get_person()
    })
    function get_person(){
            start_load()
        $.ajax({
                url:'ajax.php?action=get_pdetails',
                method:"POST",
                data:{tracking_id : $('#tracking_id').val()},
                success:function(resp){
                    if(resp){
                        resp = JSON.parse(resp)
                        if(resp.status == 1){
                            $('#name').html(resp.name)
                            $('#address').html(resp.address)
                            $('[name="person_id"]').val(resp.id)
                            $('#details').show()
                            end_load()

                        }else if(resp.status == 2){
                            alert_toast("Unknow tracking id.",'danger');
                            end_load();
                        }
                    }
                }
            })
    }
</script>