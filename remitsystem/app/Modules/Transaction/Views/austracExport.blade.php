<table>
    <tr>
    <th>Date money/property received from the ordering customer</th>
    <th>Date money/property made available to the beneficiary customer</th>
    <th>Currency Code</th>
    <th>Total Amount/ Value</th>
    <th>Type of transfer</th>
    <th>Description of property</th>
    <th>Transaction Reference Number</th>
    <th>Full name</th>
    <th>If known by any other name</th>
    <th>Date of birth (if an individual)</th>
    <th>Business/residential address (not a post box address)</th>
    <th>City/town/suburb</th>
    <th>State</th>
    <th>Postcode</th>
    <th>Country</th>
    <th>Postal Address</th>
    <th>City/town/suburb</th>
    <th>State</th>
    <th>Postcode</th>
    <th>Country</th>
    <th>Phone</th>
    <th>Email</th>
    <th>Occupation, business or principal activity</th>
    <th>ABN, ACN or ARBN</th>
    <th>Customer number (allocated by remitter)</th>
    <th>Account number (held by remitter)</th>
    <th>Business structure (if not an individual)</th>
    <th>ID type(1)</th>
    <th>ID type(if 'Other')</th>
    <th>Number</th>
    <th>Issuer</th>
    <th>ID type(2)</th>
    <th>ID type(if 'Other')</th>
    <th>Number</th>
    <th>Issuer</th>
    <th>Electronic data source</th>
    <th>Full name</th>{{--Beneficiary Full name--}}
    <th>Date of birth (if an individual)</th>
    <th>Any business name under which the beneficiary customer is operating</th>
    <th>Business/residential address (not a post box address)</th>
    <th>City/town/suburb</th>
    <th>State</th>
    <th>Postcode</th>
    <th>Country</th>
    <th>Postal Address</th>
    <th>City/town/suburb</th>
    <th>State</th>
    <th>Postcode</th>
    <th>Country</th>
    <th>Phone</th>
    <th>Email</th>
    <th>Occupation, business or principal activity</th>
    <th>ABN, ACN or ARBN</th>
    <th> Business structure (if not an individual)</th>
    <th>Account number</th>
    <th>Name of institution(where account is held)</th>{{--Bank name--}}
    <th>City</th>
    <th>Country</th>
    <th>Identification number of the retail outlet/business location</th>
    <th>Full name</th>{{-- Name --}}
    <th>Business/residential address (not a post box address)</th>
    <th>City/town/suburb</th>
    <th>State</th>
    <th>Postcode</th>
    <th>Is this person/organisation accepting the money or property?</th>
    <th>Is this person/organisation sending the transfer instruction?</th>
    <th>Full name</th>{{--Person/organisation accepting the money or property from the ordering customer (if different)--}}
    <th>Business/residential address (not a post box address)</th>
    <th>City/town/suburb</th>
    <th>State</th>
    <th>Postcode</th>
    <th>Full name</th>{{--Person/organisation sending the transfer instruction (if different)--}}
    <th>If known by any other name</th>
    <th>Date of birth (if an individual)</th>
    <th>Business/residential address (not a post box address)</th>
    <th>City/town/suburb</th>
    <th>State</th>
    <th>Postcode</th>
    <th>Postal Address</th>
    <th>City/town/suburb</th>
    <th>State</th>
    <th>Postcode</th>
    <th>Phone</th>
    <th>Email</th>
    <th>Occupation, business or principal activity</th>
    <th>ABN, ACN or ARBN</th>
    <th>Business structure (if not an individual)</th>
    <th>Full name</th>{{--Person/organisation receiving the transfer instruction--}}
    <th>Business/residential address (not a post box address)</th>
    <th>City/town/suburb</th>
    <th>State</th>
    <th>Postcode</th>
    <th>Country</th>
    <th>Is this person/organisation accepting the money or property?</th>
    <th>Is there a separate retail outlet/business location at which the money or property is being distributed?</th>
    <th>Full name</th>{{--Person/organisation distributing money or property (if different)	--}}
    <th>Business/residential address (not a post box address)</th>
    <th>City/town/suburb</th>
    <th>State</th>
    <th>Postcode</th>
    <th>Country</th>
    <th>Full name</th>{{--Retail outlet/business location where money or property is being distributed (if different)	--}}
    <th>Business/residential address (not a post box address)</th>
    <th>City/town/suburb</th>
    <th>State</th>
    <th>Postcode</th>
    <th>Country</th>
    <th>Reason for the transfer</th>
    <th>Full name</th>{{--person completing this report--}}
    <th>Job title</th>
    <th>Phone</th>
    <th>Email</th>
{{--    <th>Ord ID</th>--}}
{{--    <th>Status</th>--}}
    </tr>

    @foreach($data as $data)

        <tr>
            <td>{{Carbon\Carbon::parse($data['transaction_date'])->format('d-F-Y')}}</td>
            <td> {{Carbon\Carbon::parse($data['transaction_date'])->addDays(1)->format('d-F-Y')}}</td>
            <td>AUD</td>
            <td>{{$data->total_amount}}</td>
            <td>Money</td>
            <td></td>
            <td></td>
            <td>{{$data->sender_name}}</td>
            <td></td>
            <td>{{ (bool)strtotime($data['sender_dob']) ? (Carbon\Carbon::parse($data['sender_dob'])->addDays(1)->format('d-F-Y')) : ''}}</td>
            <td>{{str_replace(',', ' ', $data->sender_street)}}</td>
            <td>{{str_replace(',', ' ', $data->sender_suburb)}}</td>
            <td>{{str_replace(',', ' ', $data->sender_state)}}</td>
            <td>{{str_replace(',', ' ', $data->sender_postcode)}}</td>
            <td>{{$data->sender_country}}</td>

            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>{{$data->sender_phone}}</td>
            <td>{{strtolower($data->sender_email)}}</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>{{$data->id_type}}</td>
            <td></td>
            <td>{{$data->id_number}}</td>
            <td>{{$data->issued_by}}</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>{{$data->beneficiary_name}}</td>
            <td></td>
            <td></td>
             <td>{{str_replace(',', ' ', $data->b_street)}}</td>
            <td>{{str_replace(',', ' ', $data->b_suburb)}}</td>
            <td>{{str_replace(',', ' ', $data->b_state)}}</td>
            <td>{{str_replace(',', ' ', $data->b_postcode)}}</td>
            <td>{{$data->b_country}}</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>{{$data->beneficiary_phone}}</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>{{$data->account_no}}</td>
            <td>{{$data->bank_name}}</td>
            <td>{{$data->bsb}}</td>
            <td>{{$data->b_country}}</td>
            <td>1</td>
            <td>{{getAppDetailsGeneral()->company_name}}</td>
            <td>{{getAppDetailsGeneral()->street}}</td>
            <td>{{getAppDetailsGeneral()->suburb}}</td>
            <td>{{getAppDetailsGeneral()->state}}</td>
            <td>{{getAppDetailsGeneral()->postcode}}</td>
            <td>Yes</td>
            <td>Yes</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>Hub remit Pty ltd</td>
            <td>Nakshal Kathmandu</td>
            <td>Kathmandu</td>
            <td>Bagmati</td>
            <td>44600</td>
            <td>Nepal</td>
            <td>Yes</td>
            <td>No</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>{{$data->purpose_of_transfer}}</td>
            <td>{{getAppDetailsGeneral()->contact_person}}</td>
            <td>{{getAppDetailsGeneral()->designation}}</td>
            <td>{{getAppDetailsGeneral()->phone_number}}</td>
            <td>{{getAppDetailsGeneral()->email}}</td>
{{--            <td>{{$data->transaction_id}}</td>--}}
{{--            <td> {{$data->transaction_status}}</td>--}}


        </tr>
    @endforeach
</table>
