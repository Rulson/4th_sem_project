<style>
    .no-bg:active{
        background-color: transparent;!important;
    }
</style>
<header class="app-header navbar">

    <button class="navbar-toggler mobile-sidebar-toggler d-lg-none" type="button">
        <span class="navbar-toggler-icon"></span>
    </button>
    <a class="navbar-brand" href="#">{{getAppDetailsGeneral()->name}}</a>
    <button class="navbar-toggler sidebar-toggler d-md-down-none" type="button">
        <span class="navbar-toggler-icon"></span>
    </button>
    <ul class="nav navbar-nav d-md-down-none mr-auto">

    </ul>
    <ul class="nav navbar-nav ml-auto">

        @if(Auth::user()->level_id == 1 || Auth::user()->level_id == 2)
            @php $identification_request = App\Modules\Sender\Models\Sender::join('identifications','identifications.senders_id','=','senders.id')
                     /*  ->where('identifications.current',0)
                     */  ->where('Identification_status_id',1)
                        ->orderBy('identifications.identification_id','desc')-> get();

                        $count_identification = $identification_request->count();

            @endphp
            <li class="nav-item dropdown d-md-down-none">
                <a class="nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-users"></i><span class="badge badge-pill badge-info">{{$count_identification}}</span>
                </a>
                <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg" style="max-height: 350px;overflow: auto;" >
                    <div class="dropdown-header text-center ">
                        <strong><span class="" >You have {{$count_identification}} identification request(s)</span></strong>
                    </div>

                    @if(isset($identification_request))
                        @foreach($identification_request as $identification)
                            @php
                                $senderName =  App\Modules\Sender\Models\Sender::join('person','person.id','=','senders.person_id')->where('senders.id',$identification->senders_id)->first();
                            @endphp
                        <span  class="dropdown-item" style="min-height: 77px !important;">
                    <div class="message" style="margin-top: -10px; margin-left: -10px;">

                        <a href="{{route('sender.show',[$identification->senders_id])}}">
                        <div>
                            <small class="text-muted" style=""><strong>{{$senderName->first_name}} {{$senderName->last_name}}</strong></small>
                            <small class="text-muted"> requested for </small><br>
                            <small class="text-muted"> identification change</small>
                              </div>
                            <div class="row" style="padding:1px; margin-top: 6px; margin-left: -16px;">
                                            <div class="col-md-6 col-xs-6">
                                               <form method="post" action="{{route('sender.identification.approve')}}">
                                                           {{csrf_field()}}
                                                   {{Form::hidden('identification_id',$identification->identification_id)}}
                                                   {{Form::hidden('sender_id',$identification->senders_id)}}
                                                   <button type="submit" class="btn-xs btn-success no-padding" onclick="return confirm('Are you sure? You want to approve the identification request ?')" data-toggle="tooltip" data-placement="top" title="Approve"><i
                                                               class="fa fa-check" aria-hidden="true"></i>
                                                            </button>
                                                        </form>
                                            </div>
                                            <div class="col-md-6 col-xs-6" >
                                                 <form method="post" action="{{route('sender.identification.decline')}}">
                                                            {{csrf_field()}}
                                                     {{Form::hidden('identification_id',$identification->identification_id)}}
                                                     {{Form::hidden('sender_id',$identification->senders_id)}}

                                                     <button type="submit" class="btn-xs btn-danger no-padding" onclick="return confirm('Are you sure? You want to decline the identification request ?')" data-toggle="tooltip" data-placement="top" title="Declined"><i
                                                                 class="fa fa-close" aria-hidden="true"></i>
                                                            </button>
                                                        </form>
                                            </div>
                                        </div>
                <small class="text-muted float-right mt-1"> {{get_notification_format($identification->created_at)}}</small>
                </a>
            </div>
               </span>
                    @endforeach
                        @endif
                </div>
            </li>
        @endif
        @if(Auth::user()->level_id == 1 || Auth::user()->level_id == 2)
            @php
                $notApprovedClients = App\Modules\User\Models\User::leftJoin('person','person.id','=','users.person_id')
                ->leftJoin('senders','senders.person_id','=','users.person_id')
                ->where('users.level_id',5)->where('users.user_status_id',1)
                ->where('senders.sender_status_id',1)
                ->orderBy('users.id','desc')
                ->select('senders.*','person.*','users.*','users.id as user_id')->get();
            @endphp
           <li class="nav-item dropdown d-md-down-none">
            <a class="nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-user"></i><span class="badge badge-pill badge-info">{{count($notApprovedClients)+$sender_verification_notification->count()}}</span>
            </a>
            <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg" style="max-height: 350px;overflow: auto; width: 300px;">
                <div class="dropdown-header text-center ">
                    <strong><span class="" >You have {{count($notApprovedClients)+$sender_verification_notification->count()}} request(s)</span></strong>
                </div>
                @foreach($sender_verification_notification as $sender)
                    <span  class="dropdown-item" style="min-height: 77px !important;">
                    <div class="message" style="margin-top: -10px; margin-left: -10px;">

                        <a href="{{route('sender.show',[$sender->id])}}">
                        <div>

                            <small class="text-muted"><strong>{{getSenderName($sender->id)}}</strong></small>
                            <small class="text-muted">requested for identification verification</small>
                              </div>
  <div class="row" style="padding:1px; margin-top: 6px; margin-left: -16px;">
                                            <div class="col-md-6 col-xs-6">


                                                    <button type="submit"  class="btn btn-sm btn-success"  data-toggle="tooltip" data-placement="top" title="view"><i
                                                                class="fa fa-eye" aria-hidden="true" style="padding-left: 12px;"></i>
                                                    </button>

                                            </div>

                                        </div>
                <small class="text-muted float-right mt-1"> {{get_notification_format($sender->created_at)}}</small>
                </a>
            </div>
               </span>
                @endforeach
              @foreach($notApprovedClients as $clients)
                    @php $person = App\Modules\User\Models\Person::where('id',$clients->person_id)->first();
                                            $sender = \App\Modules\Sender\Models\Sender::where('person_id',$person->id)->first();
                    @endphp
             <span  class="dropdown-item" style="min-height: 77px !important;">
                    <div class="message" style="margin-top: -10px; margin-left: -10px;">

                        <a href="{{route('sender.show',[$sender['id']])}}">
                        <div>

                            <small class="text-muted"><strong>{{get_user_name($clients->user_id)}}</strong></small>
                            <small class="text-muted">requested for activation</small>
                              </div>
                            <div class="row" style="padding:1px; margin-top: 6px; margin-left: -16px;">
                                            <div class="col-md-6 col-xs-6">
                                                <form method="post" action="{{route('client.approve')}}">
                                                    {{csrf_field()}}
                                                    {{Form::hidden('client_user_id',$clients->user_id)}}

                                                    <button type="submit"  class="btn-xs btn-success no-padding" onclick="return confirm('Are you sure? You want to approve the request')" data-toggle="tooltip" data-placement="top" title="Approve"><i
                                                                class="fa fa-check" aria-hidden="true"></i>
                                                    </button>
                                                </form>
                                            </div>
                                            <div class="col-md-6 col-xs-6" >
                                                <form method="post" action="{{route('client.decline')}}">
                                                   {{csrf_field()}}
                                                    {{Form::hidden('client_user_id',$clients->id)}}
                                                    <button type="submit" class="btn-xs btn-danger no-padding" onclick="return confirm('Are you sure? You want to decline the identification request')" data-toggle="tooltip" data-placement="top" title="Declined"><i
                                                                class="fa fa-close" aria-hidden="true"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                <small class="text-muted float-right mt-1"> {{get_notification_format($clients->created_at)}}</small>
                </a>
            </div>
               </span>
            @endforeach
            </div>
        </li>
        @endif
        @if(Auth::user()->level_id != 7)
            @if(in_array(Auth::user()->level_id, [5]))
                <li class="nav-item dropdown ">
                    @php $count = $transaction_notification->count(); @endphp
                    <a class="nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-money"></i><span class="badge badge-pill badge-info count-transaction-notification">{{$count}}</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg transaction-not-class" style="max-height: 350px;overflow-y: auto;width:400px;">
                        <div class="dropdown-header text-center ">
                            <strong><span class="count-transaction-notification-msg" >You have {{$count}} notification(s)</span></strong>
                        </div>
                        @if(isset($transaction_notification))
                            @foreach($transaction_notification as $notification)
                                <span  class="dropdown-item no-bg" style="min-height: 61px !important;">
                                     <div class="float-left" style="margin: 0 12px 0 0;">
                                        <input type="checkbox" data-id="{{ $notification->id }}" name="trans-not"  class="mark-as-read">
                                    </div>

                                        <div class="message">
                                            @if(isset($notification->data['identification']))
                                                <div class="small text-muted text-truncate" data-toggle="tooltip" title="{{$notification->data['message']}}">{{$notification->data['message']}}</div>
                                            @elseif(isset($notification->data['transaction_edit']))
                                                <a href="{{url('/transactions/show',$notification->data['transaction_id'])}}">
                                                    <div class="small text-muted text-truncate" data-toggle="tooltip" title="{{$notification->data['message']}}">{{$notification->data['message']}}</div>
                                                @else
                                                <a href="{{url('/transactions/show',$notification->data['transaction_id'])}}">
                                                @if(isset( $notification->data['new']))
                                                <div class="small text-muted text-truncate">Transaction( {{format_id($notification->data['transaction_id'],'T')}} ) created.</div>
                                                @else
                                                <div class="small text-muted text-truncate">Transaction( {{format_id($notification->data['transaction_id'],'T')}} ) status changed to {{getStatusName($notification->data['status_id'])}}.</div>
                                                @endif
                                            @endif
                                            <small class="text-muted float-right mt-1">{{get_notification_format($notification->created_at)}}</small>

                                        </div>
                                    </a>
                                </span>
                                @if( count($transaction_notification)!=0 && $loop->last)
                                    <div class="dropdown-header text-center ">
                                        <strong><span class="" ><a href="#" id="mark-all">
                                            Mark all as read
                                        </a></span></strong>
                                    </div>
                                @endif
                            @endforeach
                        @endif
                    </div>
                </li>
            @endif
           <li class="nav-item dropdown d-md-down-none">
            @php $count = $notification_comment->count(); @endphp
            <a class="nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                <i class="icon-envelope-letter"></i><span class="badge badge-pill badge-info count-notification">{{$count}}</span>
            </a>
            <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg" style="max-height: 350px;overflow: auto;">
                <div class="dropdown-header text-center ">
                    <strong><span class="count-notification-msg" >You have {{$count}} notification(s)</span></strong>
                </div>       @if(isset($notification_comment))
                    @foreach($notification_comment as $notification_comment)
                <span  class="dropdown-item" style="min-height: 77px !important;">
                    <div class="message">
                        <div class="py-3 mr-3 float-left">
                              <input type="checkbox" id="{{$notification_comment->assignId}}" name="admin" value="admin" class="read-note">
                        </div>
                        <a class="read-note-click" id="{{$notification_comment->assignId}}"
                           transactionid="{{$notification_comment->Tid}}">
                        <div>

                            <small class="text-muted"><strong>{{get_user_name($notification_comment->addedBy)}}</strong></small>
                            <small class="text-muted">commented</small>
                              </div>
                        <div class="small text-muted text-truncate">"{{$notification_comment->comment}}" in {{format_id($notification_comment->Tid,'T')}}</div>
                        <small class="text-muted float-right mt-1">{{get_notification_format($notification_comment['created_at'])}}</small>
                        </a>
                    </div>
                </span>
                    @endforeach @endif

            </div>
            </li>
        @endif
            <li class="nav-item dropdown">
                <a class="nav-link nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                    <img src="{{asset('assets/coreUI/img/user.png')}}" class="img-avatar" >
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <div class="dropdown-header text-center">
                        <strong> {{ $current_user->full_name }}</strong>
                    </div>
                    @if(Auth::user()->level_id == 5)
                        @php
                            $sender = App\Modules\User\Models\User::join('person','person.id','=','users.person_id')->join('senders','senders.person_id','=','users.person_id')->where('users.id',Auth::user()->id)->select('senders.id as sender_id')->first();
                        @endphp
                        <a class="dropdown-item" href="{{route('sender.show',$sender->sender_id)}}"><i class="fa fa-user"></i> Profile</a>

                    @else
                        <a class="dropdown-item" href="{{route('user.edit',Auth::user()->id)}}"><i class="fa fa-user"></i>Profile</a>
                    @endif
                    @if(Auth::user()->level_id == 3 || Auth::user()->level_id == 5  )
                        <a class="dropdown-item" href="{{route('user.credential.edit',Auth::user()->id)}}"><i class="fa fa-lock"></i>Credentials</a>

                    @endif
                    <a class="dropdown-item" href="{{ route('logout') }}"><i class="fa fa-lock"></i> Logout</a>
                </div>
            </li>

    </ul>
</header>
