<?php use Illuminate\Support\Facades\Session; ?>

<h1 class="display-1 text-center">ELECTION</h1>
<nav class="navbar navbar-expand bg-light sticky-top justify-content-center">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" href="/admin/dashboard">Dashboard</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/admin/registervoter">Voter</a>
        </li>
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="/admin/party" id="navbardrop" data-toggle="dropdown">
                Party
            </a>
            <div class="dropdown-menu">
                <a class="dropdown-item" href="/admin/party/">List</a>
                <a class="dropdown-item" href="/admin/party/register">Register</a>
            </div>
        </li>
        <!--DEV-->
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="/admin/registercandidate" id="navbardrop" data-toggle="dropdown">
                Candidate
            </a>
            <div class="dropdown-menu">
                <a class="dropdown-item" href="/admin/registercandidate">Register</a>
                <a class="dropdown-item bg-warning" href="/admin/add">Increment (DEV/DEMO)</a>
            </div>
        </li>
        <!--        <li class="nav-item">-->
        <!--            <a class="nav-link" href="/admin/registercandidate">Candidate</a>-->
        <!--        </li>-->
        <li class="nav-item">
            <a class="nav-link" href="/">Home</a>
        </li>
    </ul>
</nav>

<nav
    class="navbar navbar-expand bg-light fixed-bottom justify-content-center">
    <span id="status" class="{{ (null !== (Session::get('status'))) ? explode(' ', Session::get('status'))[0]: ''}}">
        {{ (null !== (Session::get('status'))) ? strstr(Session::get('status'), ' '): '' }}
    </span>
</nav>
