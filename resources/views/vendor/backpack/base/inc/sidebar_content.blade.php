<!-- This file is used to store sidebar items, starting with Backpack\Base 0.9.0 -->
@if(backpack_user()->hasRole('Super Admin'))
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('dashboard') }}'><i class='la la-home nav-icon'></i> Dashboards</a></li>
<!-- <li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="la la-home nav-icon"></i> {{ trans('backpack::base.dashboard') }}</a></li> -->
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('church') }}'><i class='nav-icon la la-church'></i> Church</a></li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('personel') }}'><i class='nav-icon la la-id-badge'></i> Pastors</a></li>
<li class="nav-item nav-dropdown">
	<a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon la la-file"></i>Report</a>
	<ul class="nav-dropdown-items">
        <li class='nav-item'><a class='nav-link' href='{{ backpack_url('churchreport') }}'><i class='nav-icon la la-church'></i> Church Reports</a></li>
        <li class='nav-item'><a class='nav-link' href='{{ backpack_url('pastorreport') }}'><i class='nav-icon la la-users'></i> Pastor Reports</a></li>
        <li class='nav-item'><a class='nav-link' href='{{ backpack_url('quickreport') }}'><i class='nav-icon la la-list'></i> Quick Reports</a></li>
	</ul>
</li>
<!-- <li class='nav-item'><a class='nav-link' href='{{ backpack_url('toolsupload') }}'><i class='nav-icon la la-cog'></i> Tools</a></li> -->
<li class="nav-item nav-dropdown">
	<a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon la la-cog"></i>Tools</a>
	<ul class="nav-dropdown-items">
        <li class='nav-item'><a class='nav-link' href='{{ backpack_url('import-church') }}'><i class='nav-icon la la-church'></i> Import Data Church</a></li>
        <li class='nav-item'><a class='nav-link' href='{{ backpack_url('import-personel') }}'><i class='nav-icon la la-users'></i> Import Data Pastor</a></li>
	</ul>
</li>
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
@endif

@if(backpack_user()->hasRole(['Editor', 'Viewer']))
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('dashboard') }}'><i class='la la-home nav-icon'></i> Dashboards</a></li>
<!-- <li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="la la-home nav-icon"></i> {{ trans('backpack::base.dashboard') }}</a></li> -->
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('church') }}'><i class='nav-icon la la-church'></i> Church</a></li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('personel') }}'><i class='nav-icon la la-id-badge'></i> Pastors</a></li>
<li class="nav-item nav-dropdown">
	<a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon la la-file"></i>Report</a>
	<ul class="nav-dropdown-items">
        <li class='nav-item'><a class='nav-link' href='{{ backpack_url('churchreport') }}'><i class='nav-icon la la-church'></i> Church Reports</a></li>
        <li class='nav-item'><a class='nav-link' href='{{ backpack_url('pastorreport') }}'><i class='nav-icon la la-users'></i> Pastor Reports</a></li>
        <li class='nav-item'><a class='nav-link' href='{{ backpack_url('quickreport') }}'><i class='nav-icon la la-list'></i> Quick Reports</a></li>
	</ul>
</li>
<!-- <li class='nav-item'><a class='nav-link' href='{{ backpack_url('toolsupload') }}'><i class='nav-icon la la-cog'></i> Tools</a></li> -->
<li class="nav-item nav-dropdown">
	<a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon la la-cog"></i>Tools</a>
	<ul class="nav-dropdown-items">
        <li class='nav-item'><a class='nav-link' href='{{ backpack_url('import-church') }}'><i class='nav-icon la la-church'></i> Import Data Church</a></li>
        <li class='nav-item'><a class='nav-link' href='{{ backpack_url('import-personel') }}'><i class='nav-icon la la-users'></i> Import Data Pastor</a></li>
	</ul>
</li>
@endif
<!-- <li class='nav-item'><a class='nav-link' href='{{ backpack_url('appointment_history') }}'><i class='nav-icon la la-question'></i> Appointment History</a></li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('relatedentity') }}'><i class='nav-icon la la-question'></i> Related Eentities</a></li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('educationbackground') }}'><i class='nav-icon la la-question'></i> Education Backgrounds</a></li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('statushistory') }}'><i class='nav-icon la la-question'></i> Status Histories</a></li> -->
<!-- <li class='nav-item'><a class='nav-link' href='{{ backpack_url('specialrolepersonel') }}'><i class='nav-icon la la-question'></i> Special Role Personels</a></li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('legaldocumentchurch') }}'><i class='nav-icon la la-question'></i> LegalDocumentChurches</a></li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('servicetimechurch') }}'><i class='nav-icon la la-question'></i> ServiceTimeChurches</a></li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('statushistorychurch') }}'><i class='nav-icon la la-question'></i> StatusHistoryChurches</a></li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('relatedentitychurch') }}'><i class='nav-icon la la-question'></i> RelatedEntityChurches</a></li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('structurechurch') }}'><i class='nav-icon la la-question'></i> StructureChurches</a></li> -->
<!-- <li class='nav-item'><a class='nav-link' href='{{ backpack_url('dashboard') }}'><i class='nav-icon la la-question'></i> Dashboards</a></li> -->
<!-- <li class='nav-item'><a class='nav-link' href='{{ backpack_url('churchannualreportdetail') }}'><i class='nav-icon la la-question'></i> ChurchAnnualReportDetails</a></li> -->
<!-- <li class='nav-item'><a class='nav-link' href='{{ backpack_url('childnamepastors') }}'><i class='nav-icon la la-question'></i> ChildNamePastors</a></li> -->
<!-- <li class='nav-item'><a class='nav-link' href='{{ backpack_url('ministrybackgroundpastor') }}'><i class='nav-icon la la-question'></i> MinistryBackgroundPastors</a></li> -->
<!-- <li class='nav-item'><a class='nav-link' href='{{ backpack_url('careerbackgroundpastors') }}'><i class='nav-icon la la-question'></i> CareerBackgroundPastors</a></li> -->