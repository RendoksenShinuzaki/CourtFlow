<?php
session_start();

// Check if the login is completed, otherwise redirect to the login page
if (!isset($_SESSION['userLoggedIn']) || !$_SESSION['userLoggedIn']) {
  // Redirect to the login page
  header('Location: ../index.php');
  exit;
}

// Clear the login completed session variable
unset($_SESSION['userLoggedIn']);
?>

<!DOCTYPE html>
<html lang="en" class="h-full">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="../../../dist/output.css" rel="stylesheet" />
  <title>Banned</title>
</head>

<body class="h-full">
  <main class="grid min-h-full place-items-center bg-white px-6 py-24 sm:py-32 lg:px-8">
    <div class="text-center">
      <h1 class="mt-4 text-3xl font-bold tracking-tight text-red-700 sm:text-5xl">BANNED!</h1>
      <p class="mt-6 text-base leading-7 text-gray-600">Sorry, your account has been banned by the administrator.</p>
      <div class="mt-10 flex items-center justify-center gap-x-6">
        <a href="../index.php" class="rounded-md bg-indigo-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Go back home</a>
        <a href="#" class="text-sm font-semibold text-gray-900">Contact support <span aria-hidden="true">&rarr;</span></a>
      </div>
    </div>
  </main>
</body>

</html>