@extends('layouts.main')
@section('title', 'Similar Senders')
@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">Similar Senders</li>
@stop
@section('content')
    <style>
        th, td {
            padding: 5px;
        }

        ul, li {
            list-style: none;
        }

        a {
            /*color: inherit;*/
            /*text-underline: none;*/
            cursor: pointer;
        }
        .padding-0-25{
            padding:0 25px;
        }

        .padding-5-25{
            padding:5px 25px;
        }

        .modal-1200 {
            max-width: 1200px;
            margin: 30px auto;
        }


    </style>
    <div class="animated fadeIn">
        <div class="card">
            <div class="card-header">
                List

            </div>
            <div class="card-body">
                <table class="w-100 table-bordered border">
                    <tr class="text-center">
                        <th width="50%">New Sender</th>
                        <th width="50%">Similar Old Senders</th>
                    </tr>
                    @foreach($senders as $sender)
                        <tr class="users">
                            <td class="padding-0-25"><a
                                        href="{{route('sender.show',$sender->id)}}">{{format_id($sender->id,"S")}}
                                        - {{$sender->first_name}} {{$sender->last_name}}</a>
                            </td>
                            <td class="">
                                @if(empty($sender->similar_ids))
                                   <span>No record</span>
                                @else
                                    @php  $ids_array = explode(',',$sender->similar_ids);    @endphp
                                    @if(!empty($ids_array))
                                        <table cellspacing="0px" width="100%">
                                            @foreach($ids_array as $key=>$sender_id)
                                                @php $details = getSenderDetails($sender_id); @endphp
                                            <tr>
                                                <td class="padding-5-25">
                                                    <a href="{{route('sender.show',$details->sender_id)}}">{{format_id($details->sender_id,"S")}}
                                                        - {{$details->full_name}} </a>
                                                    <span class="pull-right">
                                                         <a type="button" data-toggle="modal" data-url="{{route('senders.compare',[$sender->id,$details->sender_id])}}" data-target="#money-transfer-modal" class="btn btn-primary  btn-sm">Compare</a>
                                                 </span>
                                                </td>

                                            </tr>
                                            @endforeach
                                        </table>
                                    @endif
                                @endif

                            </td>
                        </tr>
                    @endforeach
                </table>
                <div class="pull-right">
                    {{$senders->links()}}
                </div>
            </div>

           </div>
    </div>
    <div class="modal fade sendingmodal" id="money-transfer-modal" tabindex="-1" role="dialog"
         aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-lg modal-1200" role="document">
            <div class="modal-content">
                <div class="modal-body load-by-url">Loading...</div>
            </div>
        </div>
    </div>
@endsection

@section('page-script')
    <script type="text/javascript">
        $(document).ready(function () {
        });
    </script>
@stop
