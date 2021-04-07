<!-- This file is used to store sidebar items, starting with Backpack\Base 0.9.0 -->
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="la la-home nav-icon"></i> {{ trans('backpack::base.dashboard') }}</a></li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('personel') }}'><i class='nav-icon la la-id-badge'></i> Personels</a></li>
<li class="nav-item nav-dropdown">
	<a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon la la-users"></i> Admin</a>
	<ul class="nav-dropdown-items">
        <li class='nav-item'><a class='nav-link' href='{{ backpack_url('user') }}'><i class='nav-icon la la-user'></i> Users</a></li>
        <li class='nav-item'><a class='nav-link' href='{{ backpack_url('accountstatus') }}'><i class='nav-icon la la-id-badge'></i> Account Status</a></li>
        <li class='nav-item'><a class='nav-link' href='{{ backpack_url('role') }}'><i class='nav-icon la la-id-badge'></i> Roles</a></li>
        <!-- <li class='nav-item'><a class='nav-link' href='{{ backpack_url('permission') }}'><i class='nav-icon la la-list'></i> Permissions</a></li> -->
        <li class='nav-item'><a class='nav-link' href='{{ backpack_url('rcdpwlist') }}'><i class='nav-icon la la-list'></i> Rc / Dpw Lists</a></li>
        <li class='nav-item'><a class='nav-link' href='{{ backpack_url('churchentitytype') }}'><i class='nav-icon la la-church'></i> Church Type</a></li>
        <li class='nav-item'><a class='nav-link' href='{{ backpack_url('servicetype') }}'><i class='nav-icon la la-church'></i> Service Type</a></li>
        <li class='nav-item'><a class='nav-link' href='{{ backpack_url('titlelist') }}'><i class='nav-icon la la-list'></i> Title Lists</a></li>
        <li class='nav-item'><a class='nav-link' href='{{ backpack_url('ministryrole') }}'><i class='nav-icon la la-id-badge'></i> Ministry Role</a></li>
        <li class='nav-item'><a class='nav-link' href='{{ backpack_url('specialrole') }}'><i class='nav-icon la la-id-badge'></i> Special Role</a></li>
        <li class='nav-item'><a class='nav-link' href='{{ backpack_url('licensetype') }}'><i class='nav-icon la la-list'></i> License Type</a></li>
        <li class='nav-item'><a class='nav-link' href='{{ backpack_url('legaldocument') }}'><i class='nav-icon la la-file'></i> Legal Document</a></li>
        <li class='nav-item'><a class='nav-link' href='{{ backpack_url('countrylist') }}'><i class='nav-icon la la-list'></i> Country List</a></li>
	</ul>
</li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('appointment_history') }}'><i class='nav-icon la la-question'></i> Appointment History</a></li>