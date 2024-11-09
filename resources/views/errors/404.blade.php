<style>
    @import url('https://fonts.googleapis.com/css?family=Open+Sans|Nova+Mono');
    :root {
        --font-header: 'Nova Mono', monospace;
        --font-text: 'Open Sans', sans-serif;
        --color-theme: #F1EEDB;
        --color-bg: #282B24;

        --animation-sentence: 'You know you\'re supposed to leave, right?';
        --animation-duration: 40s;
    }
    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }
    body {
        width: 100%;
        font-family: var(--font-text);
        color: var(--color-theme);
        background: var(--color-bg);
        overflow: hidden;
    }
    .container {
        text-align: center;
        margin: 20rem auto;
    }
    .container h1 {
        font-family: var(--font-header);
        font-size: calc(4rem + 2vw);
        text-transform: uppercase;
    }
    .container p {
        text-transform: uppercase;
        letter-spacing: 0.2rem;
        font-size: 2rem;
        margin: 1.5rem 0 3rem;
    }
    .container a {
        text-decoration: none;
        color: black;
        background-color: white;
        padding: 0.5rem;
        border-radius: 0.2rem;
    }
</style>

<!-- include in a container a heading, paragraph and svg for the keyhole -->
<div class="container">
    <h1>404</h1>
    <p>Không tồn tại trang này</p>
    <a href="#" onclick="goBack()">Go back</a>
    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();">
        {{ __('Logout') }}<i class="fas fa-sign-out-alt"></i>
    </a>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
    </form>
</div>

<script>
    function goBack()
    {
        window.history.back()
    }
</script>
