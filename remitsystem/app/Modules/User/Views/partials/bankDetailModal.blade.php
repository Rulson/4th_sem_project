<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">Our Account Details:</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <table class="table table-striped">
        <tr>
            <th>Account Name:</th>
            <td>{{$application->bank_account_name}}</td>
        </tr>
        <tr>
            <th>BSB:</th>
            <td>{{$application->bank_bsb}}</td>
        </tr>
        <tr>
            <th>Account Number:</th>
            <td>{{$application->bank_account_number}}</td>
        </tr>
        <tr>
            <th>Bank Name:</th>
            <td>{{$application->bank_name}}</td>
        </tr>
    </table>
    <h5>Note:</h5>
    <p>👉 We do bank deposit above 1 lakh (Any ABSS Charges will Be deducted from receiver amount)and paid within 1-3 working days.</p>
    <p>👉 We do Local Remit below 1 Lakh (Remit charges will applies) and paid within next day.</p>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
</div>
