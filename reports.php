<?php 
include 'db_connect.php';
?>
<div class="card">
    <div class="card-body">
        <div class="card-columns">
            <div class="card border-primary h-100">
                <div class="card-body bg-light">
                    <h5><strong>Monthly Payments Report</strong></h5>
                </div>
                <div class="card-footer">
                    <div class="col-md-12">
                        <a href="index.php?page=payment_report" class="d-flex justify-content-between"> <span>View Report</span> <span class="fa fa-chevron-circle-right"></span></a>
                    </div>
                </div>
            </div>

            <div class="card border-primary h-100">
                <div class="card-body bg-light ">
                    <h5><strong>Rental Balances Report</strong></h5>
                </div>
                <div class="card-footer">
                    <div class="col-md-12">
                        <a href="index.php?page=balance_report" class="d-flex justify-content-between"> <span>View Report</span> <span class="fa fa-chevron-circle-right"></span></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
