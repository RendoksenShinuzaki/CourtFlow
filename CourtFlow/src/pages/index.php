<!DOCTYPE html>
<html lang="en" class="h-full bg-white">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="../../dist/output.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <!-- font link -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
  <!-- forda second link -->
  <link rel="stylesheet" href="https://demos.creative-tim.com/notus-js/assets/styles/tailwind.css">
  <link rel="stylesheet" href="https://demos.creative-tim.com/notus-js/assets/vendor/@fortawesome/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

  <title>Login</title>
</head>

<body class="bg-gray-200 h-full">
  <div class="alert">
    <?php
    if (isset($_SESSION['status'])) {
    ?>
      <div class="class-alert-success">
        <h5><?= $_SESSION['status']; ?></h5>
      </div>
    <?php
      unset($SESSION['status']);
    }
    ?>
  </div>

  <!-- Nav -->
  <nav class="bg-gray-800 dark:bg-gray-900 fixed w-full z-20 top-0 start-0 border-b border-gray-200 dark:border-gray-600">
    <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
      <a href="../LandingPage.php" class="flex items-center space-x-3 rtl:space-x-reverse">
        <img src="../images/CFLogo.png" class="h-16 w-16" style="height: 50px; width: 50px;" alt="Flowbite Logo">
        <span class="self-center text-2xl font-semibold whitespace-nowrap text-white">Court Flow</span>
      </a>
      <div class="flex md:order-2 space-x-3 md:space-x-0 rtl:space-x-reverse">
        <button data-collapse-toggle="navbar-sticky" type="button" class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600" aria-controls="navbar-sticky" aria-expanded="false">
          <span class="sr-only">Open main menu</span>
          <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h15M1 7h15M1 13h15" />
          </svg>
        </button>
      </div>

      <div class="items-center justify-between hidden w-full md:flex md:w-auto md:order-1" id="navbar-sticky">
        <ul class="flex flex-col p-4 md:p-0 mt-4 font-medium border border-gray-100 rounded-lg bg-gray-50 md:space-x-8 rtl:space-x-reverse md:flex-row md:mt-0 md:border-0 md:bg-gray-800 dark:bg-gray-800 md:dark:bg-gray-900 dark:border-gray-700">
          <li>
            <a href="../LandingPage.php" class="block py-2 px-3 text-white bg-blue-700 rounded md:bg-transparent md:text-blue-700 md:p-0 md:dark:text-blue-500" aria-current="page">Home</a>
          </li>
          <li>
            <a href="#" class="block py-2 px-3 text-white rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 md:dark:hover:text-blue-500 dark:text-white dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700">About</a>
          </li>
          <li>
            <a href="#" class="block py-2 px-3 text-white rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 md:dark:hover:text-blue-500 dark:text-white dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700">Services</a>
          </li>
          <li>
            <a href="#" class="block py-2 px-3 text-white rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 md:dark:hover:text-blue-500 dark:text-white dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700">Contact</a>
          </li>
        </ul>
      </div>

    </div>
  </nav>


  <!-- updated form -->
  <section class="bg-indigo-50 min-h-screen flex items-center justify-center">
    <div class="bg-white flex rounded-2xl shadow-2xl max-w-3xl p-5">

      <div class="sm:w-1/2 px-16 bg-white"> <!-- Adjusted background color to white -->
        <h2 class="font-bold text-2xl text-blue-700 mt-14">Login</h2>
        <p class="text-sm mt-4"></p>

        <!-- login form backend -->
        <form class="space-y-4 md:space-y-6" method="post" action="../controller/login_Controller.php">
          <!-- email -->
          <div>
            <input type="email" name="email" id="email" placeholder="Email" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
          </div>
          <!-- password -->
          <div class="relative">
            <input type="password" name="password" id="password" placeholder="Password" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
            <span id="togglePassword" class="absolute inset-y-2 right-0 flex items-center pr-3 cursor-pointer">
              <i id="eyeIcon" class="fas fa-eye"></i>
            </span>
          </div>
          <!-- Remember Me Checkbox -->

          <!-- Button Login-->
          <button type="submit" name="login" id="login" class="w-full border border-indigo-300 text-white bg-gray-800 hover:bg-gray-900 hover:border-indigo-400 hover:text-white focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800 rounded-2xl">Login</button>
        </form>
      </div>

      <!-- side logo -->
      <div class="sm:block hidden w-1/2 bg-white"> <!-- Adjusted background color to white -->
        <img class="rounded-2xl" src="../images/CourtFlowLogo.png" alt="">
      </div>
    </div>
  </section>
  <!-- end of form section -->

</body>
<script>
  document.getElementById('password').addEventListener('input', function(e) {
    const password = e.target;
    const togglePassword = document.getElementById('togglePassword');
    togglePassword.style.display = password.value ? 'block' : 'none';
  });

  document.getElementById('togglePassword').addEventListener('click', function() {
    const password = document.getElementById('password');
    const eyeIcon = document.getElementById('eyeIcon');
    const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
    password.setAttribute('type', type);

    // Toggle icon
    eyeIcon.classList.toggle('fa-eye');
    eyeIcon.classList.toggle('fa-eye-slash');
  });
</script>
<style>
  #togglePassword {
    display: none;
  }
</style>

</html>