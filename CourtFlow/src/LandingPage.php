<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Landing Page</title>
  <link rel="stylesheet" href="../dist/output.css">
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <!-- font link -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
  <!-- forda second link -->
  <link rel="stylesheet" href="https://demos.creative-tim.com/notus-js/assets/styles/tailwind.css">
  <link rel="stylesheet" href="https://demos.creative-tim.com/notus-js/assets/vendor/@fortawesome/fontawesome-free/css/all.min.css">
</head>

<body class="bg-gray-200">

  <nav class="bg-gray-800 dark:bg-gray-900 fixed w-full z-20 top-0 start-0 border-b border-gray-200 dark:border-gray-600">
    <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
      <a href="../src/LandingPage.php" class="flex items-center space-x-3 rtl:space-x-reverse">
        <img src="../src/images/CFLogo.png" class="h-16 w-16" style="height: 50px; width: 50px;" alt="Flowbite Logo">
        <span class="self-center text-2xl font-semibold whitespace-nowrap text-white">Court Flow</span>
      </a>


      <div class="flex md:order-2 space-x-3 md:space-x-0 rtl:space-x-reverse">
        <a href="../src/pages/index.php" class="text-gray-200 bg-blue-500 hover:bg-blue-600 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded text-sm px-4 py-2 text-center dark:bg-blue-500 dark:hover:bg-gray-800 dark:focus:ring-blue-800">Login</a>
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
            <a href="../src/LandingPage.php" class="block py-2 px-3 text-white bg-blue-700 rounded md:bg-transparent md:text-blue-700 md:p-0 md:dark:text-blue-500" aria-current="page">Home</a>
          </li>
          <li>
            <a href="#about-us-section" class="block py-2 px-3 text-white rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 md:dark:hover:text-blue-500 dark:text-white dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700">About</a>
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

  <!-- first section -->
  <section class="max-w-screen-xl mx-auto px-4 md:px-8 py-12 md:py-24 h-screen flex items-center justify-center">
    <div class="flex flex-col md:flex-row items-center w-full">
      <!-- Image on top for mobile, on the left for larger screens -->
      <div class="md:w-1/2 md:order-last">
        <img src="../src/images/lawyer_updated.png" alt="Sample Image" class="w-full h-auto md:float-right">
      </div>

      <!-- Text on the bottom for mobile, on the right for larger screens -->
      <div class="lg:w-1/2 lg:flex items-center text-center lg:text-left px-4 md:px-8 py-8 md:w-full">
        <div class="mb-4 md:mb-0">
          <h2 class="text-3xl font-serif font-semibold text-gray-800 md:text-6xl">The Law <span class="text-indigo-300">Protects</span></h2>
          <p class="mt-2 text-sm text-gray-500 md:text-base text-justify">The Law Protects strives to redefine legal processes through an innovative case file tracking solution. We are committed to ensuring the seamless management of legal documents, providing efficiency, security, and trust in the pursuit of justice.</p>
          <div class="flex justify-center lg:justify-start mt-6">
            <a href="../src/pages/index.php" class="px-4 py-3 bg-opacity-100 bg-blue-500 text-gray-200 text-xs font-semibold rounded hover:bg-gray-800" href="#">Get Started</a>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- end of first section -->

  <!-- Welcome to our website -->
  <section class="flex flex-col lg:flex-row items-center justify-center py-8 lg:py-16 bg-white shadow-lg">
    <!-- Left side - Welcome text -->
    <div class="lg:w-1/2 lg:px-8 mb-8 lg:mb-0">
      <h2 class="text-3xl lg:text-4xl font-extrabold text-gray-900 mb-4 lg:mb-8">WELCOME TO OUR WEBSITE</h2>
      <p class="text-gray-500 text-justify">Welcome to Court Flow, where navigating the legal process becomes seamless and efficient. Our innovative system is tailored to simplify your experience, offering intuitive case file tracking and management solutions. We're dedicated to ensuring an effortless journey through legal documents, enhancing efficiency, security, and trust in the pursuit of justice.</p>
    </div>

    <!-- Right side - Latest News -->
    <div class="lg:w-1/2 lg:px-8">
      <div class="bg-gray-900 rounded-lg shadow-md p-4 lg:p-6">
        <h3 class="text-2xl lg:text-3xl font-bold text-indigo-200 mb-4 lg:mb-6">LATEST NEWS</h3>
        <div class="mb-4 lg:mb-6">
          <p class="text-indigo-200">About recent court cases</p>
          <p class="text-indigo-100 text-sm">11/17/2023</p>
        </div>
        <div>
          <p class="text-indigo-200">Updates on legal reforms</p>
          <p class="text-indigo-100 text-sm">11/17/2023</p>
        </div>
      </div>
    </div>
  </section>

  <!-- About Us  -->
  <section id="about-us-section" class="bg-gray-200 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <h2 class="text-3xl font-extrabold text-gray-900 text-center mb-12">About Us</h2>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">

        <!-- Profile 1 -->
        <div class="bg-gray-900 p-6 rounded-lg shadow-2xl">
          <img src="../src/images/st.jpg" alt="Profile Picture" class="w-24 h-24 rounded-full mx-auto mb-4">
          <h3 class="text-xl font-semibold text-indigo-200">Stephen Ceniza</h3>
          <p class="text-indigo-100">Back-End Developer</p>
          <p class="mt-4 text-indigo-50">Database management and optimizing server-side processes for efficient performance.</p>
        </div>

        <!-- Profile 2 -->
        <div class="bg-gray-900 p-6 rounded-lg shadow-2xl">
          <img src="../src/images/lyyy.jpg" alt="Profile Picture" class="w-24 h-24 rounded-full mx-auto mb-4">
          <h3 class="text-xl font-semibold text-indigo-200">Lysander Quiñones</h3>
          <p class="text-indigo-100">Back-End Developer</p>
          <p class="mt-4 text-indigo-50">Specializes in algorithmic problem-solving and backend architecture.</p>
        </div>

        <!-- Profile 3 -->
        <div class="bg-gray-900 p-6 rounded-lg shadow-2xl">
          <img src="../src/images/jai.jpg" alt="Profile Picture" class="w-24 h-24 rounded-full mx-auto mb-4">
          <h3 class="text-xl font-semibold text-indigo-200">Jaya Olipendo</h3>
          <p class="text-indigo-100">Front-End Developer</p>
          <p class="mt-4 text-indigo-50">Passionate about crafting user-friendly interfaces and responsive designs.</p>
        </div>

        <!-- Profile 4 -->
        <div class="bg-gray-900 p-6 rounded-lg shadow-2xl">
          <img src="../src/images/catprofile.png" alt="Profile Picture" class="w-24 h-24 rounded-full mx-auto mb-4">
          <h3 class="text-xl font-semibold text-indigo-200">Nicolle Gacayan</h3>
          <p class="text-indigo-100">Front-End Developer</p>
          <p class="mt-4 text-indigo-50">Creative thinker with a focus on elegant and intuitive frontend solutions.</p>
        </div>
      </div>
    </div>
  </section>
  <!-- end of profile section -->


  <!-- author's saying -->
  <section class="bg-white shadow-lg py-8 lg:py-16">
    <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-2xl 2xl:shadow-gray flex flex-col lg:flex-row items-center justify-center p-8">
      <!-- Left side - Image -->
      <div class="lg:w-1/2 lg:px-8 mb-8 lg:mb-0 flex justify-center">
        <img src="../src/images/author.jpg" alt="Author Image" class="w-32 h-32 rounded-full object-cover">
      </div>

      <!-- Right side - Text and Author -->
      <div class="lg:w-1/2 lg:px-8">
        <div class="bg-gray-900 rounded-lg shadow-md p-4 lg:p-6">
          <p class="text-indigo-200 mb-6">It is the spirit and not the form of law that keeps justice alive.</p>
          <p class="text-indigo-100">Earl Warren (CEO, BOOKS AUTHOR)</p>
        </div>
      </div>
    </div>
  </section>



  <!-- Last Section -->
  <section class="bg-gray-900 text-white py-12">
    <div class="max-w-4xl mx-auto flex flex-col lg:flex-row items-center justify-between px-4 lg:px-0">
      <!-- Newsletter Subscription -->
      <div class="w-full lg:w-1/2 mb-8 lg:mb-0">
        <h3 class="text-2xl font-semibold mb-2">DO YOU WANT TO GET NEWS?</h3>
        <p class="mb-4">JOIN OUR NEWSLETTER</p>
        <div class="flex items-center border-b border-gray-600">
          <input type="email" placeholder="ENTER YOUR EMAIL" class="bg-transparent text-white flex-grow py-2 px-4 focus:outline-none">
          <i class="fas fa-envelope text-white ml-2"></i>
        </div>
      </div>

      <!-- Social Links -->
      <div class="flex space-x-4 lg:space-x-8 items-center">
        <a href="https://www.facebook.com/" class="text-white hover:text-gray-400">
          <i class="fab fa-facebook-f"></i>
        </a>
        <a href="https://twitter.com/" class="text-white hover:text-gray-400">
          <i class="fab fa-twitter"></i>
        </a>
        <a href="https://www.linkedin.com/login" class="text-white hover:text-gray-400">
          <i class="fab fa-linkedin-in"></i>
        </a>
      </div>
    </div>

    <!-- Copyright Text -->
    <div class="text-center mt-16">
      <p class="text-sm text-gray-600">Copyright 2023 CaseFileTracking. Designed by CFCFT. All rights reserved.</p>
    </div>
  </section>


</body>

</html>