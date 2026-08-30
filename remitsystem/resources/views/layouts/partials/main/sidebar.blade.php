<div class="sidebar">
    <nav class="sidebar-nav">
        <ul class="nav">
            <li class="nav-item">
                <a class="nav-link" href="{{route('user.dashboard')}}"><i class="fa fa-dashboard"></i> Dashboard</a>
            </li>
            @if(in_array(Auth::user()->level_id, [1,2,3]))
                <li class="nav-item">
                    <a class="nav-link" href="{{route('transaction.sendmoney')}}"><i class="fa fa-money"></i> Send Money</a>
                </li>
            @endif
            @if(in_array(Auth::user()->level_id, [1,2,3,6,8]))
                <li class="nav-item">
                    <a class="nav-link" href="{{route('transactions.rates')}}"><i class="fa fa-money custom"></i> Rates</a>
                </li>
            @endif
            @if(in_array(Auth::user()->level_id, [1,2,6,7,8]))
                <li class="nav-item">
                    <a class="nav-link" href="{{route('transactions.orders')}}"><i class="fa fa-money custom"></i> Orders</a>
                </li>
            @endif
@php
    $user = App\Modules\User\Models\User::where('users.id',Auth::user()->id)->first();
$sender = \App\Modules\Sender\Models\Sender::where('person_id',$user['person_id'])->first();
@endphp
@if(Auth::user()->level_id == 5  && $sender->sender_status_id == 2)

    <li class="nav-item">
        <a class="nav-link" href="{{route('transaction.sendmoney')}}"><i class="fa fa-money"></i> SendMoney</a>
    </li>
@endif
@if(in_array(Auth::user()->level_id, [1,2,6,7,8]))
<li class="nav-item nav-dropdown">
    <a class="nav-link nav-dropdown-toggle" href="#"><i class="fa fa-money"></i> Txn Manager</a>
    <ul class="nav-dropdown-items">
            @if(Auth::user()->level_id !=2)
            <li class="nav-item">
                <a class="nav-link" href="{{route('transactions.dashboard')}}"><i
                            class="fa fa-circle-o"></i> Txn Dashboard</a>
            </li>
            @endif
            @if(!in_array(Auth::user()->level_id, [2,7,8]))
            <li class="nav-item">
                <a class="nav-link" href="{{route('transactions.profit-per-day')}}"><i
                            class="fa fa-circle-o"></i>Profit</a>
            </li>
            @endif
        <li class="nav-item">
            <a class="nav-link" href="{{route('transactions.search.view')}}"><i class="fa fa-circle-o"></i>
                Advanced Search</a>
        </li>
    </ul>
</li>
@endif
@if(Auth::user()->level_id == 5)
    <li class="nav-item">
        <a class="nav-link" href="{{route('transactions.orders')}}"><i class="fa fa-money"></i> All Transactions</a>
    </li>
@endif
@if(in_array(Auth::user()->level_id, [1,2,3,6,8]))

    <li class="nav-item nav-dropdown">
        <a class="nav-link nav-dropdown-toggle" href="#"><i class="fa fa-user-plus"></i> Manage Senders</a>
        <ul class="nav-dropdown-items">
            <li class="nav-item">
                <a class="nav-link" href="{{route('senders.index')}}"><i class="fa fa-circle-o"></i>View All
                    Senders</a>
            </li>
            @if(in_array(Auth::user()->level_id,[1,2,8]))
            <li class="nav-item">
                <a class="nav-link" href="{{route('senders.similar')}}"><i class="fa fa-circle-o"></i>Similar Senders</a>
            </li>
            @endif
            @if(Auth::user()->level_id != 6 && Auth::user()->level_id != 8)
            <li class="nav-item">
                <a class="nav-link" href="{{route('sender.create')}}"><i class="fa fa-circle-o"></i>Add
                    Senders</a>
            </li>
            @endif
            <li class="nav-item">
                <a class="nav-link" href="{{route('sender.search.view')}}"><i class="fa fa-circle-o"></i>
                    Advanced Search</a>
            </li>

        </ul>
    </li>
@endif
@if(in_array(Auth::user()->level_id,[1,2,3,6,8]))
    <li class="nav-item nav-dropdown">
        <a class="nav-link nav-dropdown-toggle" href="#"><i class="fa fa-user-plus"></i> Beneficiaries</a>
        <ul class="nav-dropdown-items">
            <li class="nav-item">
                <a class="nav-link" href="{{route('beneficiaries.index')}}"><i class="fa fa-circle-o"></i>View
                    All Beneficiaries</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{route('beneficiary.search.view')}}"><i
                            class="fa fa-circle-o"></i> Advanced Search</a>
            </li>
        </ul>
    </li>
@endif

@if(Auth::user()->level_id == 5)
    <li class="nav-item">
        <a class="nav-link" href="{{route('beneficiaries.index')}}"><i class="fa fa-user-plus"></i>
            All Receivers</a>
    </li>
@endif
            @if(in_array(Auth::user()->level_id,[1,2,5]))
            <li class="nav-item">
                <a class="nav-link" href="{{route('referral')}}"><i class="fa fa-ticket"></i> Referrals</a>
            </li>
            @endif
@if(Auth::check() && Auth::user()->level_id == 3)
    <li class="nav-item">
        <a class="nav-link" href="{{route('agent.payment.summary')}}"><i class="fa fa-money"></i> Payment
            Summary</a>
    </li>
@endif
@if(in_array(Auth::user()->level_id , [1,2,6,7]))
        <li class="nav-item nav-dropdown">
            <a class="nav-link nav-dropdown-toggle" href="#"><i class="fa fa-user-secret"></i>Manage Agents</a>
            <ul class="nav-dropdown-items">
                <li class="nav-item">
                    <a class="nav-link" href="{{route('agents.dashboard')}}"><i class="fa fa-circle-o"></i>Agent
                        Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{route('agents.index')}}"><i class="fa fa-circle-o"></i>View All
                        Agents</a>
                </li>
                @if(!in_array(Auth::user()->level_id , [2,6,7]))
                <li class="nav-item">
                    <a class="nav-link" href="{{route('agent.create')}}"><i class="fa fa-circle-o"></i>Add Agent</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{route('agent.payment.create')}}"><i class="fa fa-circle-o"></i>Add
                        Payment</a>
                </li>
                    @endif
            </ul>
        </li>
    @if(!in_array(Auth::user()->level_id, [7,8]))
        @if(!in_array(Auth::user()->level_id, [2]))
            <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="#"><i class="fa fa-user"></i>Manage Distributors</a>
                <ul class="nav-dropdown-items">
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('distributors.index')}}"><i class="fa fa-circle-o"></i>View All Distributors</a>
                    </li>
                        @if(Auth::user()->level_id != 6)
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('distributor.create')}}"><i class="fa fa-circle-o"></i>Add Distributor</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('distributor.payment.create')}}"><i class="fa fa-circle-o"></i>Add Payment</a>
                    </li>
                        @endif
                </ul>
            </li>
        @endif
        @if(Auth::user()->level_id != 6)
        <li class="nav-item nav-dropdown">
            <a class="nav-link nav-dropdown-toggle" href="#"><i class="fa fa-users"></i>Manage Users</a>
            <ul class="nav-dropdown-items">
                <li class="nav-item">
                    <a class="nav-link" href="{{route('users.index')}}"><i class="fa fa-circle-o"></i>View All
                        Users</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{route('user.create')}}"><i class="fa fa-circle-o"></i>Add
                        User</a>
                </li>
            </ul>
        </li>
        @endif
    @endif
    @if(Auth::user()->level_id == 1)
        <li class="nav-item nav-dropdown">
            <a class="nav-link nav-dropdown-toggle" href="#"><i class="fa fa-envelope"></i>SMS</a>
            <ul class="nav-dropdown-items">
                <li class="nav-item">
                    <a class="nav-link" href="{{route('sms.compose')}}"><i class="fa fa-circle-o"></i> Send
                        SMS</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{route('sms.compose.bulk')}}"><i class="fa fa-circle-o"></i> Send Bulk
                        SMS</a>
                </li>
            </ul>
        </li>

        <li class="nav-item nav-dropdown">
            <a class="nav-link nav-dropdown-toggle" href="#"><i class="fa fa-envelope"></i>Email System</a>
            <ul class="nav-dropdown-items">
                <li class="nav-item">
                    <a class="nav-link" href="{{route('email.compose')}}"><i class="fa fa-circle-o"></i> Send
                        Email</a>
                </li><li class="nav-item">
                    <a class="nav-link" href="{{route('email.bulk.compose')}}"><i class="fa fa-circle-o"></i> Send
                        Bulk Email</a>
                </li>

              <li class="nav-item">
                    <a class="nav-link" href="{{route('email.log')}}"><i class="fa fa-circle-o"></i>Email
                        Log</a>
                </li>
            </ul>
        </li>
         <li class="nav-item nav-dropdown">
            <a class="nav-link nav-dropdown-toggle" href="#"><i class="fa fa-envelope"></i>Email Templates</a>
            <ul class="nav-dropdown-items">
                <li class="nav-item">
                    <a class="nav-link" href="{{route('email-template.index')}}"><i class="fa fa-circle-o"></i> View All
                    </a>
                </li><li class="nav-item">
                    <a class="nav-link" href="{{route('email-template.create')}}"><i class="fa fa-circle-o"></i>
                        Add</a>
                </li>

            </ul>
        </li>
    @endif
    @if(in_array(Auth::user()->level_id, [1, 2]))
        <li class="nav-item nav-dropdown">
            <a class="nav-link nav-dropdown-toggle" href="#"><i class="fa fa-envelope"></i>Notifications</a>
            <ul class="nav-dropdown-items">
                <li class="nav-item">
                    <a class="nav-link" href="{{route('notification.send_notification')}}"><i class="fa fa-circle-o"></i> Send
                        Notification</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{route('notification.log')}}"><i class="fa fa-circle-o"></i>Notification
                        Log</a>
                </li>
            </ul>
        </li>
    @endif
    @if(Auth::user()->level_id == 1)
        <li class="nav-item">
            <a class="nav-link" href="{{route('settings.index')}}"><i class="fa fa-cogs"></i> Settings</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{route('coupons.index')}}"><i class="fa fa-bank"></i> Manage Coupon</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{route('banks.index')}}"><i class="fa fa-bank"></i> Manage Banks</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{route('states.index')}}"><i class="fa fa-bank"></i> Manage State/District</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{route('applications.index')}}"><i class="fa fa-bank"></i> Manage Application</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{route('sliders.index')}}"><i class="fa fa-bank"></i> Manage Slider</a>
        </li>
    @endif
@endif

</ul>
</nav>
<button class="sidebar-minimizer brand-minimizer" type="button"></button>
</div>
