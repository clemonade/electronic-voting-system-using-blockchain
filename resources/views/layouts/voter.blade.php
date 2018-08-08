<?php use Illuminate\Support\Facades\Session; ?>

<h1 class="display-1 text-center">ELECTION</h1>
<nav class="navbar navbar-expand bg-light sticky-top justify-content-center">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" href="/">Home</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/voter">Voter</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/admin">Admin</a>
        </li>
    </ul>
</nav>

<nav class="navbar navbar-expand bg-light fixed-bottom justify-content-center">
    <span id="status"><?php echo Session::get('status'); ?></span>
</nav>
