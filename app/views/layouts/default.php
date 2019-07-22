<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/> 
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?php echo $title; ?></title>
    <link href="/public/styles/bootstrap.css" rel="stylesheet">
    <link href="/public/styles/default.css" rel="stylesheet">
    <link href="/public/styles/custom.css" rel="stylesheet">
    <link href="/public/libs/contextMenu/jquery.contextMenu.min.css" rel="stylesheet">
    
    <link href="/public/styles/font-awesome.css" rel="stylesheet">

    <script src="/public/scripts/jquery.js"></script>
    <script src="/public/scripts/custom.js"></script>
    <script src="/public/scripts/form.js"></script>
    <script src="/public/scripts/popper.js"></script>


    <script src="/public/libs/contextMenu/jquery.contextMenu.min.js"></script>
    <script src="/public/libs/contextMenu/jquery.ui.position.min.js"></script>


    <script src="/public/scripts/bootstrap.js"></script>
    
</head>
<body class="fixed-nav sticky-footer bg-dark">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top" id="mainNav">
        <a class="navbar-brand" href="/">Панель Администратора</a>
        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav navbar-sidenav" id="exampleAccordion">
                <li class="nav-item">
                    <a class="nav-link return" action="/" href="/">
                        <i class="fa fa-reply" aria-hidden="true"></i>
                        <span>Return</span>
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="content">
        <?php echo $content; ?>
    </div>
    <footer class="sticky-footer">
        <div class="container">
            <div class="text-center">
                <small>&copy; 2019, PHP</small>
            </div>
        </div>
    </footer>

</body>
</html>