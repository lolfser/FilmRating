<html>
    <head>
        <title>@yield('pageTitle') - Filme bewerten und Programme erstellen</title>
        <style>
            * {
                color: rgb(55 65 81);
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 0;
            }
            body {
                background-color: #e5e7eb;
            }
            div.content {
                background-color: #f7f7f7;
                margin: 10px;
                padding: 10px;
            }
            a {
                text-decoration: none;
            }
            a.active {
                font-weight: bold;
                text-decoration: underline;
            }
            a:hover {
                text-decoration: underline;
                color: blue;
            }
            @yield('style')
        </style>
    </head>
    <body>
        <header style="background-color: #f7f7f7; padding: 10px;">
            <div style="display: flex;
                        justify-content: center;
                        gap: 10px 30px;
                        align-items: center;
                        color: unset;
                        font-size: 14px;
            ">
                <a href="/">
                    <img src="/images/logo.jpg" alt="Logo" style="height: 60px;">
                </a>
<?php
    foreach ($headerLinks ?? [] as $link) {
        $classes = $link['active'] ? 'active' : '';
    ?>
                <span>
                    <a href="<?php echo $link['href']; ?>"
                       <?php if ($classes !== '') { echo 'class="' . $classes . '"'; } ?>
                    >
                       <?php echo $link['label'] ?>
                    </a>
                </span>
    <?php } ?>
            <span></span><span></span>
            <span>
                <a href="/user/profile/">
                    <?php echo Auth::user()->name; ?>
                </a>
                <br>
                <form id="logout-form" action="/logout" method="POST" style="display: none">
                    {{ csrf_field() }}
                </form>
                <span style="font-size: 9px">
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        Logout
                    </a>
                </span>
            </>
            </div>
        </header>
        <div class="content">
            @yield('content')
        </div>
        <script>
            @yield('script')
        </script>
    </body>
</html>
