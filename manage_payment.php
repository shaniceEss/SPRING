<?php
include 'db_connect.php';
if (isset($_GET['id'])) {
    $qry = $conn->query("SELECT * FROM payments where id= " . $_GET['id']);
    foreach ($qry->fetch_array() as $k => $val) {
        $$k = $val;
    }
}
?>
<div class="container-fluid">
    <form action="" id="manage-payment">
        <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
        <div id="msg"></div>
        <div class="form-group">
            <label for="" class="control-label">Tenant</label>
            <select name="house_no" id="house_no" class="custom-select select2">
                <option value=""></option>

                <?php
                $tenant = $conn->query("SELECT *,concat(lastname,', ',firstname,' ',middlename) as name FROM tenants where status = 1 order by name asc");
                while ($row = $tenant->fetch_assoc()) :
                ?>
                    <option value="
                    <?php
                    $house_id = $row['house_id'];
                    $result = $conn->query("SELECT * FROM houses WHERE id = $house_id");
                    $house = $result->fetch_object();
                    echo $house->house_no
                    ?>" <?php echo isset($house_no) && $house_no == $house->house_no ? 'selected' : ''?>><?php echo ucwords($row['name']) ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="form-group" id="details">

        </div>
        <div class="form-group">
            <label for="" class="control-label">Enter pay number: </label>
            <input type="number" class="form-control" name="phone_number" placehoder="254722000000" value="<?php echo isset($phone_number) ? $phone_number : '' ?>">
        </div>
        <div class="form-group">
            <label for="" class="control-label">Amount to pay: </label>
            <input type="number" class="form-control text-right" step="any" name="amount" value="<?php echo isset($amount) ? $amount : '' ?>">
        </div>
    </form>
</div>

<div id="details_clone" style="display: none">
    <div class='d'>
        <large><b>Details</b></large>
        <hr>
        <p>Tenant: <b class="tname"></b></p>
        <p>Monthly Rental Rate: <b class="price"></b></p>
        <p>Outstanding Balance: <b class="outstanding"></b></p>
        <p>Total Paid: <b class="total_paid"></b></p>
        <p>Rent Started: <b class='rent_started'></b></p>
        <p>Payable Months: <b class="payable_months"></b></p>
        <hr>
    </div>
</div>
<script>
    $(document).ready(function() {
        if ('<?php echo isset($id) ? 1 : 0 ?>' == 1)
            $('#tenant_id').trigger('change')
    })
    $('.select2').select2({
        placeholder: "Please Select Here",
        width: "100%"
    })
    $('#tenant_id').change(function() {
        if ($(this).val() <= 0)
            return false;

        start_load()
        $.ajax({
            url: 'ajax.php?action=get_tdetails',
            method: 'POST',
            data: {
                id: $(this).val(),
                pid: '<?php echo isset($id) ? $id : '' ?>'
            },
            success: function(resp) {
                if (resp) {
                    resp = JSON.parse(resp)
                    var details = $('#details_clone .d').clone()
                    details.find('.tname').text(resp.name)
                    details.find('.price').text(resp.price)
                    details.find('.outstanding').text(resp.outstanding)
                    details.find('.total_paid').text(resp.paid)
                    details.find('.rent_started').text(resp.rent_started)
                    details.find('.payable_months').text(resp.months)
                    console.log(details.html())
                    $('#details').html(details)
                }
            },
            complete: function() {
                end_load()
            }
        })
    })
    $('#manage-payment').submit(function(e) {
        e.preventDefault()
        start_load()
        $('#msg').html('')
        $.ajax({
            url: 'stk_push.php',
            data: new FormData($(this)[0]),
            cache: false,
            contentType: false,
            processData: false,
            method: 'POST',
            type: 'POST',
            success: function(resp) {
                console.log(resp)
                if (resp.ResponseCode == "0") {
                    alert_toast("Payment successfully made.", 'success')
                    setTimeout(function() {
                        location.reload()
                    }, 1000)
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                // Handle the error here
                console.log("AJAX error: " + errorThrown);
            }
        })
    })
</script>