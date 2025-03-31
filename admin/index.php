
<!DOCTYPE html>
<html>

<head>
  <title>Placement Portal</title>
  <link href="../img/logo.png" rel="icon">
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../css/AdminLTE.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/iCheck/1.0.2/skins/square/blue.css">
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>

<body class="hold-transition login-page bg-blue-100 text-black">

  <?php include '../uploads/admin_header.php'; ?>

  <div class="login-box">
    <div class="login-logo">
      <a href="../index.php" style="color:black"><b>Placement Portal</b></a>
    </div>

    <!-- /.login-logo -->
    <div class="login-box-body bg-blue-200 text-black">
      <p class="login-box-msg text-2xl text-black">Admin Login</p>

      <style>
        .large {
          width: 350px;
          height: 300px;
        }

        .small {
          font-size: small;
        }

        #footer {
          position: absolute;
          bottom: 0;
          width: 100%;
          height: 60px;
        }

        @media only screen and (max-width: 768px) {
          .large {
            margin: auto;
          }

          .small {
            position: absolute;
          }
        }
      </style>

      <form action="checklogin.php" method="post">
        <div class="form-group has-feedback">
          <input type="text" class="form-control" name="username" placeholder="Username">
          <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
        </div>
        <div class="form-group has-feedback">
          <input type="password" class="form-control" name="password" placeholder="Password">
          <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        </div>
        <div class="row">
          <div class="col-xs-4">
            <button type="submit" class="flex mx-auto mt-6 text-white bg-indigo-500 border-0 py-2 px-5 focus:outline-none hover:bg-indigo-600 rounded">Sign In</button>
          </div>
        </div>

        <?php
        if (isset($_SESSION['loginError'])) {
        ?>
          <div>
            <p class="text-center">Invalid Email/Password! Try Again!</p>
          </div>
        <?php
          unset($_SESSION['loginError']);
        }
        ?>
      </form>
    </div>
    <!-- /.login-box-body -->
  </div>

  <div>
    <footer id="footer" class="text-gray-600 body-font bg-[#3a4753] border-t-2 border-gray-700 small mb-0">
      <div class="pt-1 pb-2">
        <ul class="flex space-x-16 justify-center text-white my-4">
          <li><i class="fa fa-copyright" aria-hidden="true"></i> Placement Portal @ 2024</li>
          <li><i class="fa fa-facebook" aria-hidden="true"></i></li>
          <li><i class="fa fa-twitter" aria-hidden="true"></i></li>
          <li><i class="fa fa-instagram" aria-hidden="true"></i></li>
          <li><i class="fa fa-linkedin" aria-hidden="true"></i></li>
        </ul>
      </div>
    </footer>
  </div>

  <!-- jQuery 3 -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <!-- Bootstrap 3.3.7 -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <!-- AdminLTE App -->
  <script src="../js/adminlte.min.js"></script>
  <!-- iCheck -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/iCheck/1.0.2/icheck.min.js"></script>
</body>

</html>
