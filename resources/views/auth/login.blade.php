<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <link rel="stylesheet" href="{{asset('css/styles.css')}}">

</head>
<body>

<header class="site-header">

    <div class="wrapper">

        <div role="button" class="mobile-menu"><span></span></div>

        <div class="site-logo">
            <a href="/"> MY Beauty</a>
        </div>

        <nav class="header-nav" role="navigation">
            <ul>
                <li class="active">
                    <a href="/KAI/index.html">Home</a>
                </li>
                <li>
                    <a href="#">Articles</a>
                </li>
                <li>
                    <a href="/KAI/looks.html">Looks</a>
                </li>
                <li>
                    <a href="#">Beauty pros</a>
                </li>
                <li class="login-link">
                    <a href="/KAI/login.html">Log in</a>
                </li>
            </ul>
        </nav>

        <button class="search-btn">
            <svg fill="#222222" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24px" height="24px">
                <path d="M 9 2 C 5.1458514 2 2 5.1458514 2 9 C 2 12.854149 5.1458514 16 9 16 C 10.747998 16 12.345009 15.348024 13.574219 14.28125 L 14 14.707031 L 14 16 L 20 22 L 22 20 L 16 14 L 14.707031 14 L 14.28125 13.574219 C 15.348024 12.345009 16 10.747998 16 9 C 16 5.1458514 12.854149 2 9 2 z M 9 4 C 11.773268 4 14 6.2267316 14 9 C 14 11.773268 11.773268 14 9 14 C 6.2267316 14 4 11.773268 4 9 C 4 6.2267316 6.2267316 4 9 4 z"/>
            </svg>
        </button>

        <div class="search-box">
            <form action="" method="POST" role="search">
                <select class="search_select">
                    <option>articles</option>
                    <option>item 1</option>
                    <option>item 2</option>
                </select>
                <input type="search" placeholder="Search" />
                <button type="submit" class="btn btn-default">
                    <svg fill="#999999" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18px" height="18px">
                        <path d="M 9 2 C 5.1458514 2 2 5.1458514 2 9 C 2 12.854149 5.1458514 16 9 16 C 10.747998 16 12.345009 15.348024 13.574219 14.28125 L 14 14.707031 L 14 16 L 20 22 L 22 20 L 16 14 L 14.707031 14 L 14.28125 13.574219 C 15.348024 12.345009 16 10.747998 16 9 C 16 5.1458514 12.854149 2 9 2 z M 9 4 C 11.773268 4 14 6.2267316 14 9 C 14 11.773268 11.773268 14 9 14 C 6.2267316 14 4 11.773268 4 9 C 4 6.2267316 6.2267316 4 9 4 z"/>
                    </svg>
                </button>
            </form>
        </div>

        <div class="login-link">
            <a href="/KAI/login.html">Log in</a>
        </div>

        <div class="overlay"></div>

    </div>
</header>

<main class="site-main login-page" role="main">

    <nav aria-label="breadcrumb" class="breadcrumb">
        <ul>
            <li>
                <a href="#">Home</a><span class="bread-sep"></span>
            </li>
            <li class="active">
                <a href="#">Log In</a>
            </li>
        </ul>
    </nav>

    <div class="wrapper">
        <section class="main-content login-app">
            <div class="login-app_text">
                <h1>You can sign up through the app !</h1>
                <p>You'll find a salon that's right for you.</p>
                <div class="store-btns">
                    <a href="#" class="appStore">App Store Download</a>
                    <a href="#" class="googlePlay">Google Play Download</a>
                </div>
            </div>
            <div class="login-app_img">
                <img src="{{asset('img/phone.png')}}" alt="phone">
            </div>
        </section>

        <aside class="aside login-block">
            <h2>Log in</h2>

            <div class="login-btn">
                <a href="{{ url('/auth/redirect/facebook') }}" class=" faceBook-login"> Log in with Facebook</a>
                <a href="{{ url('/auth/redirect/google') }}" class="google-login">Log in with Google</a>
            </div>

            <form method="POST" action="{{ route('login') }}" class="form loginForm">
                @csrf
                <input name="email" type="email" placeholder="Email">
                <input name="password" type="password" placeholder="Password">
                <div class="form-submit">
                    <button type="submit" class="submit-btn">Log in</button>
                    <a href="{{ route('password.request') }}">Forgot your password?</a>
                </div>
            </form>
        </aside>

    </div>
</main>

<footer class="site-footer">
    <div class="wrapper">

        <div class="site-footer_top">
            <div class="site-logo">
                <a href="/"> MY Beauty</a>
            </div>

            <nav class="footer-nav" role="navigation">
                <ul>
                    <li><a href="/">Home</a></li>
                    <li><a href="#">Articles</a></li>
                    <li><a href="#">Looks</a></li>
                    <li><a href="#">Beauty pros</a></li>
                </ul>
            </nav>

            <div class="socials">
                <ul>
                    <li><a href="#">Instagram</a></li>
                    <li><a href="#">Facebook</a></li>
                </ul>
            </div>
        </div>

        <div class="site-footer_btm">
            <nav class="footer-secondary-nav" role="navigation">
                <ul>
                    <li><a href="#">Contact Us</a></li>
                    <li><a href="#">Privacy Policy</a></li>
                    <li><a href="#">Terms & Conditions</a></li>
                </ul>
            </nav>

            <p class="copyrights">Copyright ©️ KAI</p>
        </div>

    </div>
</footer>

<script src="https://code.jquery.com/jquery-3.5.0.min.js" integrity="sha256-xNzN2a4ltkB44Mc/Jz3pT4iU1cmeR0FkXs4pru/JxaQ=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.js"></script>
<script src="./public/js/main.js"></script>
</body>
</html>
