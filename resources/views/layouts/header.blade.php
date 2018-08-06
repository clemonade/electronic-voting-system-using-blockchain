<?php use Illuminate\Support\Facades\Session; ?>

<nav class="navbar navbar-expand-sm bg-white sticky-top justify-content-center">
    <a class="navbar-brand" href="/admin/dashboard">Election</a>
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" href="/admin/registervoter">Voter</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/admin/party">Party</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/admin/registercandidate">Candidate</a>
        </li>
    </ul>
</nav>

<nav class="navbar navbar-expand-sm bg-light fixed-bottom">
    <span id="status"><?php echo Session::get('status'); ?></span>
</nav>
