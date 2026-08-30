<style>
    th, td {
        padding: 5px;
    }
</style>
<div class="modal-header">
    <h5 class="modal-title" id="myModalLabel">Compare Senders</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
    </button>
</div>


<div class="modal-body" style="overflow-y: auto;">

    <table class="w-100 table-bordered border">
        @if(isset($error))
            <tr style="color:red;">{{$error}}</tr>
            @else
        <tr>
            <th width="20%">Attributes</th>
            <th width="40%">New Sender</th>
            <th width="40%">Old Sender</th>
        </tr>
        <tr>
            <th  width="20%">Sender Id</th>
            <td width="50%">{{format_id($sender1['sender_id'],"S")}}</td>
            <td width="50%">{{format_id($sender2['sender_id'],"S")}}</td>
        </tr>
        <tr>
            <th width="20%">Name</th>
            <td width="40%">{{$sender1['full_name']}}</td>
            <td width="40%">{{$sender2['full_name']}}</td>
        </tr>
        <tr>
            <th width="20%">Email</th>
            <td width="40%">{{$sender1['email']}}</td>
            <td width="40%">{{$sender2['email']}}</td>
        </tr>
        <tr>
            <th width="20%">Phone</th>
            <td width="40%">{{$sender1['number']}}</td>
            <td width="40%">{{$sender2['number']}}</td>
        </tr>
        <tr>
            <th width="20%">Date of Birth</th>
            <td width="40%">{{standard_date($sender1['dob'])}}</td>
            <td width="40%">{{standard_date($sender2['dob'])}}</td>

        </tr>

        <tr>
            <th width="20%">Identification Type</th>
            <td width="40%">{{getIdTypeName($sender1['identification_types_id'])}}</td>
            <td width="40%">{{getIdTypeName($sender2['identification_types_id'])}}</td>

        </tr>
        <tr>
            <th width="20%">Identification Number</th>
            <td width="40%">{{$sender1['id_number']}}</td>
            <td width="40%">{{$sender2['id_number']}}</td>
        </tr>
        <tr>
            <th width="20%">Issued By</th>
            <td width="40%">{{$sender1['issued_by']}}</td>
            <td width="40%">{{$sender2['issued_by']}}</td>
        </tr>
        <tr>
            <th width="20%">Expiry Date</th>
             <td width="40%">{{standard_date($sender1['expiry_date'])}}</td>
            <td width="40%">{{standard_date($sender2['expiry_date'])}}</td>

        </tr>
        <tr>
            <th width="20%">Identification Image</th>
            <td width="40%"><img src="{{ asset('identification/'.$sender1['image'])}}" width="450" height="300"></td>
            <td width="40%"><img src="{{ asset('identification/'.$sender2['image'])}}" width="450" height="300"></td>

        </tr>
        @if(!empty($sender1['image1']) || !empty($sender2['image1']))
            <tr>
                <th width="20%">Identification Image 2</th>
                <td width="40%"><img src="{{!empty($sender1['image1']) ?   asset('identification/'.$sender1['image1']) : '#'}}" alt="{{empty($sender1['image1']) ? 'No Image Uploaded' : '' }}" width="450" height="300"></td>
                <td width="40%"><img src="{{!empty($sender2['image1']) ?  asset('identification/'.$sender2['image1']) : '#'}}" alt="{{empty($sender2['image1']) ? 'No Image Uploaded' : '' }}" width="450" height="300"></td>

            </tr>
            @endif
@endif
    </table>
</div>

