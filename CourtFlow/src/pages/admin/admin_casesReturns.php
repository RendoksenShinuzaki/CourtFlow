<?php
session_start();
include('../../connection/config.php');

$loggedInRole = $_SESSION['role'] ?? '';

if (!isset($_SESSION['adminLoggedIn']) || !$_SESSION['adminLoggedIn']) {
    // Redirect to the login page
    header('Location: ../index.php');
    exit;
}

$returnsSql = "SELECT * FROM returns WHERE isApproved = '0'";
$returnsSqlResult = $conn->query($returnsSql);

//returns SQL Query
//$returnsSql = "SELECT *, v1_pao_submit_case.Version AS Version1, v1_pao_submit_case.id AS ID1, returns.id 
//FROM returns INNER JOIN v1_pao_submit_case ON v1_pao_submit_case.id = returns.CaseId;
 //-- INNER JOIN v2_fiscal_submit_case AS v2 ON v2.V1_CaseId = v1.id
 //-- INNER JOIN v3_occ_case_assignment AS v3 ON v3.V2_CaseId = v2.id
 //-- INNER JOIN v4_rtc_submit_warrant AS v4 ON v4.V3_CaseId = v3.id
 //-- INNER JOIN v5_interpreter_scheduling AS v5 ON v5.V4_CaseId = v4.id;";
//$returnsSqlResult = $conn->query($returnsSql); 

?>

<!DOCTYPE html>
<html lang="en" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../../../dist/output.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <title>Admin Case Returns</title>
</head>

<body class="bg-gray-200 h-full">
    <div>
        <!-- for mobile web -->
        <!-- <nav class="lg:hidden py-6 px-6 bg-gray-800">
            <div class="flex items-center justify-between">
                <a class="text-2xl text-white font-semibold" href="#">
                    <img class="h-10" src="../images/Logo.png" alt="" width="auto">
                </a>
                <button class="navbar-burger flex items-center rounded focus:outline-none">
                    <svg class="text-white bg-indigo-500 hover:bg-indigo-600 block h-8 w-8 p-2 rounded" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" fill="currentColor">
                        <title>Mobile menu</title>
                        <path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z"></path>
                    </svg>
                </button>
            </div>
        </nav> -->
        <div class="hidden lg:block navbar-menu relative z-50">
            <div class="navbar-backdrop fixed lg:hidden inset-0 bg-white opacity-10"></div>
            <nav class="fixed top-0 left-0 bottom-0 flex flex-col w-3/4 lg:w-80 sm:max-w-xs pt-6 pb-8 bg-white overflow-y-auto border-l border-gray-400 shadow-2xl">
                <div class="flex w-full items-center px-6 pb-6 mb-6 lg:border-b border-gray-300">
                    <a class="text-xl text-white font-semibold" href="#">
                        <img class="h-8" src="../../images/CFLogo.png" alt="" width="auto">
                    </a>
                    <span class="text-2xl text-gray-800 ml-2 inline-block uppercase font-bold">CourtFlow</span>
                </div>
                <div class="px-4 pb-6">
                    <?php if (!empty($loggedInRole)) : ?>
                        <h3 class="mb-2 text-xs uppercase text-gray-500 font-medium">Logged in as: <?php echo $loggedInRole; ?></h3>
                    <?php endif; ?>
                    <ul class="text-sm font-medium">
                        <li>
                            <a href="adminDashboard.php" class="flex items-center pl-3 py-3 pr-2 text-gray-500 hover:bg-gray-700 hover:text-white hover:border border-gray-500 rounded">
                                <span class="inline-block mr-3">
                                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                    </svg>
                                </span>
                                <span>Admin Dashboard</span>
                            </a>
                        </li>
                        <li>
                            <a href="admin_userManagement.php" class="flex items-center pl-3 py-3 pr-2 text-gray-500 hover:bg-gray-700 hover:text-white hover:border border-gray-500 rounded">
                                <span class="inline-block mr-3">
                                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                                    </svg>
                                </span>
                                <span>User Management</span>
                            </a>
                        </li>
                        <li>
                            <a href="admin_rolesManagement.php" class="flex items-center pl-3 py-3 pr-2 text-gray-500 hover:bg-gray-700 hover:text-white hover:border border-gray-500 rounded">
                                <span class="inline-block mr-3">
                                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </span>
                                <span>Roles</span>
                            </a>
                        </li>
                        <li>
                            <a href="admin_branchManagement.php" class="flex items-center pl-3 py-3 pr-2 text-gray-500 hover:bg-gray-700 hover:text-white hover:border border-gray-500 rounded">
                                <span class="inline-block mr-3">
                                    <i class="fa-solid fa-scale-balanced w-5 h-5"></i>
                                    </span>
                                    <span>Branches</span>
                                    </a>

                        </li>
                        <li>
                            <a class="flex items-center pl-3 py-3 pr-2 text-white bg-gray-900 rounded">
                                <span class="inline-block mr-3">
                                    <svg class="w-5 h-5" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                       <path fill-rule="evenodd" clip-rule="evenodd" d="M10 15a.75.75 0 0 1-.53-1.28l3.22-3.22H3.75a.75.75 0 0 1 0-1.5h8.94l-3.22-3.22A.75.75 0 0 1 10.53 5.28l4.5 4.5a.75.75
                                            0 0 1 0 1.06l-4.5 4.5A.75.75 0 0 1 10 15z" fill="currentColor"/>
                                            </svg>

                                </span>
                                </span>
                                <span>Returns</span>
                            </a>
                        </li>
                        <li>
                            <a href="admin_auditTrail.php" class="flex items-center pl-3 py-3 pr-2 text-gray-500 hover:bg-gray-700 hover:text-white hover:border border-gray-500 rounded">
                                <span class="inline-block mr-3">
                                    <svg class="w-5 h-5" viewbox="0 0 20 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M18.9831 6.64169C18.9047 6.545 18.8056 6.46712 18.6931 6.41376C18.5806 6.36041 18.4576 6.33293 18.3331 6.33335H16.6665V5.50002C16.6665 4.83698 16.4031 4.20109 15.9342 3.73225C15.4654 3.26341 14.8295 3.00002 14.1665 3.00002H8.93313L8.66646 2.16669C8.49359 1.67771 8.17292 1.2546 7.74888 0.955986C7.32484 0.657367 6.81843 0.498019 6.2998 0.500019H3.33313C2.67009 0.500019 2.0342 0.763411 1.56536 1.23225C1.09652 1.70109 0.83313 2.33698 0.83313 3.00002V13C0.83313 13.6631 1.09652 14.2989 1.56536 14.7678C2.0342 15.2366 2.67009 15.5 3.33313 15.5H15.3331C15.9008 15.4984 16.451 15.3036 16.8933 14.9476C17.3355 14.5917 17.6435 14.0959 17.7665 13.5417L19.1665 7.35002C19.1918 7.22578 19.1885 7.0974 19.1567 6.97466C19.1249 6.85191 19.0656 6.73803 18.9831 6.64169ZM4.4748 13.1834C4.43246 13.3713 4.32629 13.5388 4.17435 13.6574C4.02241 13.7759 3.8341 13.8381 3.64146 13.8334H3.33313C3.11212 13.8334 2.90015 13.7456 2.74387 13.5893C2.58759 13.433 2.4998 13.221 2.4998 13V3.00002C2.4998 2.779 2.58759 2.56704 2.74387 2.41076C2.90015 2.25448 3.11212 2.16669 3.33313 2.16669H6.2998C6.48152 2.1572 6.66135 2.20746 6.81183 2.30978C6.9623 2.4121 7.07515 2.56087 7.13313 2.73335L7.58313 4.10002C7.6366 4.25897 7.7368 4.39809 7.8706 4.49919C8.00441 4.60029 8.16561 4.65867 8.33313 4.66669H14.1665C14.3875 4.66669 14.5994 4.75448 14.7557 4.91076C14.912 5.06704 14.9998 5.27901 14.9998 5.50002V6.33335H6.66646C6.47383 6.32864 6.28551 6.39084 6.13358 6.50935C5.98164 6.62786 5.87546 6.79537 5.83313 6.98335L4.4748 13.1834ZM16.1415 13.1834C16.0991 13.3713 15.993 13.5388 15.841 13.6574C15.6891 13.7759 15.5008 13.8381 15.3081 13.8334H6.00813C6.05117 13.7405 6.08198 13.6425 6.0998 13.5417L7.33313 8.00002H17.3331L16.1415 13.1834Z" fill="currentColor"></path>
                                    </svg>
                                </span>
                                <span>Audit Trail</span>
                            </a>
                        </li>
                        <li>
                            <a class="flex items-center pl-3 py-3 pr-2 text-gray-500 hover:bg-gray-700 hover:text-white hover:border border-gray-500 rounded">
                                <span class="inline-block mr-3">
                                    <svg class="w-5 h-5" viewbox="0 0 18 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M2.09182 8.575C2.01257 8.49913 1.91911 8.43966 1.81682 8.4C1.61394 8.31665 1.38637 8.31665 1.18349 8.4C1.08119 8.43966 0.98774 8.49913 0.908486 8.575C0.832619 8.65425 0.773148 8.74771 0.733486 8.85C0.66967 9.00176 0.652235 9.16902 0.68338 9.33068C0.714525 9.49234 0.792855 9.64115 0.908486 9.75833C0.989487 9.83194 1.0825 9.89113 1.18349 9.93333C1.28324 9.97742 1.39109 10.0002 1.50015 10.0002C1.60921 10.0002 1.71707 9.97742 1.81682 9.93333C1.91781 9.89113 2.01082 9.83194 2.09182 9.75833C2.20745 9.64115 2.28578 9.49234 2.31693 9.33068C2.34807 9.16902 2.33064 9.00176 2.26682 8.85C2.22716 8.74771 2.16769 8.65425 2.09182 8.575ZM4.83349 1.66667H16.5002C16.7212 1.66667 16.9331 1.57887 17.0894 1.42259C17.2457 1.26631 17.3335 1.05435 17.3335 0.833333C17.3335 0.61232 17.2457 0.400358 17.0894 0.244078C16.9331 0.0877975 16.7212 0 16.5002 0H4.83349C4.61247 0 4.40051 0.0877975 4.24423 0.244078C4.08795 0.400358 4.00015 0.61232 4.00015 0.833333C4.00015 1.05435 4.08795 1.26631 4.24423 1.42259C4.40051 1.57887 4.61247 1.66667 4.83349 1.66667ZM2.09182 4.40833C1.97463 4.2927 1.82582 4.21437 1.66416 4.18323C1.50251 4.15208 1.33525 4.16952 1.18349 4.23333C1.0825 4.27554 0.989487 4.33472 0.908486 4.40833C0.832619 4.48759 0.773148 4.58104 0.733486 4.68333C0.689399 4.78308 0.666626 4.89094 0.666626 5C0.666626 5.10906 0.689399 5.21692 0.733486 5.31667C0.775688 5.41765 0.834877 5.51067 0.908486 5.59167C0.989487 5.66528 1.0825 5.72447 1.18349 5.76667C1.28324 5.81075 1.39109 5.83353 1.50015 5.83353C1.60921 5.83353 1.71707 5.81075 1.81682 5.76667C1.91781 5.72447 2.01082 5.66528 2.09182 5.59167C2.16543 5.51067 2.22462 5.41765 2.26682 5.31667C2.31091 5.21692 2.33368 5.10906 2.33368 5C2.33368 4.89094 2.31091 4.78308 2.26682 4.68333C2.22716 4.58104 2.16769 4.48759 2.09182 4.40833ZM16.5002 4.16667H4.83349C4.61247 4.16667 4.40051 4.25446 4.24423 4.41074C4.08795 4.56703 4.00015 4.77899 4.00015 5C4.00015 5.22101 4.08795 5.43298 4.24423 5.58926C4.40051 5.74554 4.61247 5.83333 4.83349 5.83333H16.5002C16.7212 5.83333 16.9331 5.74554 17.0894 5.58926C17.2457 5.43298 17.3335 5.22101 17.3335 5C17.3335 4.77899 17.2457 4.56703 17.0894 4.41074C16.9331 4.25446 16.7212 4.16667 16.5002 4.16667ZM2.09182 0.241667C2.01257 0.165799 1.91911 0.106329 1.81682 0.0666666C1.66506 0.00285041 1.4978 -0.0145849 1.33614 0.0165602C1.17448 0.0477053 1.02567 0.126035 0.908486 0.241667C0.834877 0.322667 0.775688 0.415679 0.733486 0.516667C0.689399 0.616417 0.666626 0.724274 0.666626 0.833333C0.666626 0.942392 0.689399 1.05025 0.733486 1.15C0.775688 1.25099 0.834877 1.344 0.908486 1.425C0.989487 1.49861 1.0825 1.5578 1.18349 1.6C1.33525 1.66382 1.50251 1.68125 1.66416 1.65011C1.82582 1.61896 1.97463 1.54063 2.09182 1.425C2.16543 1.344 2.22462 1.25099 2.26682 1.15C2.31091 1.05025 2.33368 0.942392 2.33368 0.833333C2.33368 0.724274 2.31091 0.616417 2.26682 0.516667C2.22462 0.415679 2.16543 0.322667 2.09182 0.241667ZM16.5002 8.33333H4.83349C4.61247 8.33333 4.40051 8.42113 4.24423 8.57741C4.08795 8.73369 4.00015 8.94565 4.00015 9.16667C4.00015 9.38768 4.08795 9.59964 4.24423 9.75592C4.40051 9.9122 4.61247 10 4.83349 10H16.5002C16.7212 10 16.9331 9.9122 17.0894 9.75592C17.2457 9.59964 17.3335 9.38768 17.3335 9.16667C17.3335 8.94565 17.2457 8.73369 17.0894 8.57741C16.9331 8.42113 16.7212 8.33333 16.5002 8.33333Z" fill="currentColor"></path>
                                    </svg>
                                </span>
                                <span>Complaint Section</span>
                            </a>
                        </li>
                    </ul>
                    <div class="pt-8">
                        <a class="flex items-center pl-3 py-3 pr-2 text-gray-500 hover:bg-gray-700 hover:text-white hover:border border-gray-500 rounded" href="#">
                            <span class="inline-block mr-4">
                                <svg class="w-5 h-5" viewbox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M17.7666 7.9583L16.1916 7.4333L16.9333 5.94996C17.0085 5.7947 17.0336 5.61993 17.0053 5.44977C16.9769 5.27961 16.8964 5.12245 16.775 4.99996L15 3.22496C14.8768 3.1017 14.7182 3.02013 14.5463 2.99173C14.3743 2.96333 14.1979 2.98953 14.0416 3.06663L12.5583 3.8083L12.0333 2.2333C11.9778 2.06912 11.8726 1.92632 11.7322 1.82475C11.5918 1.72319 11.4232 1.66792 11.25 1.66663H8.74996C8.57526 1.66618 8.40483 1.72064 8.26277 1.82233C8.12071 1.92402 8.0142 2.06778 7.9583 2.2333L7.4333 3.8083L5.94996 3.06663C5.7947 2.99145 5.61993 2.9663 5.44977 2.99466C5.27961 3.02302 5.12245 3.10349 4.99996 3.22496L3.22496 4.99996C3.1017 5.1231 3.02013 5.28177 2.99173 5.45368C2.96333 5.62558 2.98953 5.80205 3.06663 5.9583L3.8083 7.44163L2.2333 7.96663C2.06912 8.02208 1.92632 8.12732 1.82475 8.26772C1.72319 8.40812 1.66792 8.57668 1.66663 8.74996V11.25C1.66618 11.4247 1.72064 11.5951 1.82233 11.7372C1.92402 11.8792 2.06778 11.9857 2.2333 12.0416L3.8083 12.5666L3.06663 14.05C2.99145 14.2052 2.9663 14.38 2.99466 14.5502C3.02302 14.7203 3.10349 14.8775 3.22496 15L4.99996 16.775C5.1231 16.8982 5.28177 16.9798 5.45368 17.0082C5.62558 17.0366 5.80205 17.0104 5.9583 16.9333L7.44163 16.1916L7.96663 17.7666C8.02253 17.9321 8.12904 18.0759 8.2711 18.1776C8.41317 18.2793 8.58359 18.3337 8.7583 18.3333H11.2583C11.433 18.3337 11.6034 18.2793 11.7455 18.1776C11.8875 18.0759 11.9941 17.9321 12.05 17.7666L12.575 16.1916L14.0583 16.9333C14.2126 17.0066 14.3856 17.0307 14.5541 17.0024C14.7225 16.9741 14.8781 16.8947 15 16.775L16.775 15C16.8982 14.8768 16.9798 14.7182 17.0082 14.5463C17.0366 14.3743 17.0104 14.1979 16.9333 14.0416L16.1916 12.5583L17.7666 12.0333C17.9308 11.9778 18.0736 11.8726 18.1752 11.7322C18.2767 11.5918 18.332 11.4232 18.3333 11.25V8.74996C18.3337 8.57526 18.2793 8.40483 18.1776 8.26277C18.0759 8.12071 17.9321 8.0142 17.7666 7.9583ZM16.6666 10.65L15.6666 10.9833C15.4367 11.0579 15.2257 11.1816 15.0483 11.3459C14.871 11.5102 14.7315 11.711 14.6395 11.9346C14.5475 12.1582 14.5053 12.3991 14.5158 12.6406C14.5262 12.8821 14.5891 13.1185 14.7 13.3333L15.175 14.2833L14.2583 15.2L13.3333 14.7C13.1196 14.5935 12.8855 14.5342 12.6469 14.526C12.4083 14.5179 12.1707 14.5611 11.9502 14.6528C11.7298 14.7445 11.5316 14.8824 11.3691 15.0573C11.2066 15.2322 11.0835 15.44 11.0083 15.6666L10.675 16.6666H9.34996L9.01663 15.6666C8.94204 15.4367 8.81832 15.2257 8.65404 15.0483C8.48977 14.871 8.28888 14.7315 8.06531 14.6395C7.84174 14.5475 7.60084 14.5053 7.35932 14.5158C7.11779 14.5262 6.88143 14.5891 6.66663 14.7L5.71663 15.175L4.79996 14.2583L5.29996 13.3333C5.41087 13.1185 5.47373 12.8821 5.48417 12.6406C5.49461 12.3991 5.45238 12.1582 5.36041 11.9346C5.26845 11.711 5.12894 11.5102 4.95158 11.3459C4.77422 11.1816 4.56325 11.0579 4.3333 10.9833L3.3333 10.65V9.34996L4.3333 9.01663C4.56325 8.94204 4.77422 8.81832 4.95158 8.65404C5.12894 8.48977 5.26845 8.28888 5.36041 8.06531C5.45238 7.84174 5.49461 7.60084 5.48417 7.35932C5.47373 7.11779 5.41087 6.88143 5.29996 6.66663L4.82496 5.74163L5.74163 4.82496L6.66663 5.29996C6.88143 5.41087 7.11779 5.47373 7.35932 5.48417C7.60084 5.49461 7.84174 5.45238 8.06531 5.36041C8.28888 5.26845 8.48977 5.12894 8.65404 4.95158C8.81832 4.77422 8.94204 4.56325 9.01663 4.3333L9.34996 3.3333H10.65L10.9833 4.3333C11.0579 4.56325 11.1816 4.77422 11.3459 4.95158C11.5102 5.12894 11.711 5.26845 11.9346 5.36041C12.1582 5.45238 12.3991 5.49461 12.6406 5.48417C12.8821 5.47373 13.1185 5.41087 13.3333 5.29996L14.2833 4.82496L15.2 5.74163L14.7 6.66663C14.5935 6.88033 14.5342 7.11442 14.526 7.35304C14.5179 7.59165 14.5611 7.82924 14.6528 8.0497C14.7445 8.27016 14.8824 8.46835 15.0573 8.63086C15.2322 8.79337 15.44 8.9164 15.6666 8.99163L16.6666 9.32496V10.65ZM9.99996 6.66663C9.34069 6.66663 8.69623 6.86213 8.14806 7.2284C7.5999 7.59467 7.17266 8.11526 6.92036 8.72435C6.66807 9.33344 6.60206 10.0037 6.73068 10.6503C6.8593 11.2969 7.17676 11.8908 7.64294 12.357C8.10911 12.8232 8.70306 13.1406 9.34966 13.2692C9.99626 13.3979 10.6665 13.3319 11.2756 13.0796C11.8847 12.8273 12.4053 12.4 12.7715 11.8519C13.1378 11.3037 13.3333 10.6592 13.3333 9.99996C13.3333 9.11591 12.9821 8.26806 12.357 7.64294C11.7319 7.01782 10.884 6.66663 9.99996 6.66663ZM9.99996 11.6666C9.67033 11.6666 9.34809 11.5689 9.07401 11.3857C8.79993 11.2026 8.58631 10.9423 8.46016 10.6378C8.33402 10.3332 8.30101 9.99811 8.36532 9.67481C8.42963 9.35151 8.58836 9.05454 8.82145 8.82145C9.05454 8.58836 9.35151 8.42963 9.67481 8.36532C9.99811 8.30101 10.3332 8.33402 10.6378 8.46016C10.9423 8.58631 11.2026 8.79993 11.3857 9.07401C11.5689 9.34809 11.6666 9.67033 11.6666 9.99996C11.6666 10.442 11.491 10.8659 11.1785 11.1785C10.8659 11.491 10.442 11.6666 9.99996 11.6666Z" fill="currentColor"></path>
                                </svg>
                            </span>
                            <span class="font-semibold">Settings</span>
                        </a>
                        <form action="../../controller/logout.php" method="post">
                            <a name="logout" class="flex items-center pl-3 py-3 pr-2 text-gray-500 hover:bg-gray-700 hover:text-white hover:border border-gray-500 rounded">
                                <span class="inline-block mr-4">
                                    <svg class="w-5 h-5" viewbox="0 0 14 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M0.333618 8.99996C0.333618 9.22097 0.421416 9.43293 0.577696 9.58922C0.733976 9.7455 0.945938 9.83329 1.16695 9.83329H7.49195L5.57528 11.7416C5.49718 11.8191 5.43518 11.9113 5.39287 12.0128C5.35057 12.1144 5.32879 12.2233 5.32879 12.3333C5.32879 12.4433 5.35057 12.5522 5.39287 12.6538C5.43518 12.7553 5.49718 12.8475 5.57528 12.925C5.65275 13.0031 5.74492 13.0651 5.84647 13.1074C5.94802 13.1497 6.05694 13.1715 6.16695 13.1715C6.27696 13.1715 6.38588 13.1497 6.48743 13.1074C6.58898 13.0651 6.68115 13.0031 6.75862 12.925L10.0919 9.59163C10.1678 9.51237 10.2273 9.41892 10.2669 9.31663C10.3503 9.11374 10.3503 8.88618 10.2669 8.68329C10.2273 8.581 10.1678 8.48755 10.0919 8.40829L6.75862 5.07496C6.68092 4.99726 6.58868 4.93563 6.48716 4.89358C6.38564 4.85153 6.27683 4.82988 6.16695 4.82988C6.05707 4.82988 5.94826 4.85153 5.84674 4.89358C5.74522 4.93563 5.65298 4.99726 5.57528 5.07496C5.49759 5.15266 5.43595 5.2449 5.3939 5.34642C5.35185 5.44794 5.33021 5.55674 5.33021 5.66663C5.33021 5.77651 5.35185 5.88532 5.3939 5.98683C5.43595 6.08835 5.49759 6.18059 5.57528 6.25829L7.49195 8.16663H1.16695C0.945938 8.16663 0.733976 8.25442 0.577696 8.4107C0.421416 8.56698 0.333618 8.77895 0.333618 8.99996ZM11.1669 0.666626H2.83362C2.17058 0.666626 1.53469 0.930018 1.06585 1.39886C0.59701 1.8677 0.333618 2.50358 0.333618 3.16663V5.66663C0.333618 5.88764 0.421416 6.0996 0.577696 6.25588C0.733976 6.41216 0.945938 6.49996 1.16695 6.49996C1.38797 6.49996 1.59993 6.41216 1.75621 6.25588C1.91249 6.0996 2.00028 5.88764 2.00028 5.66663V3.16663C2.00028 2.94561 2.08808 2.73365 2.24436 2.57737C2.40064 2.42109 2.6126 2.33329 2.83362 2.33329H11.1669C11.388 2.33329 11.5999 2.42109 11.7562 2.57737C11.9125 2.73365 12.0003 2.94561 12.0003 3.16663V14.8333C12.0003 15.0543 11.9125 15.2663 11.7562 15.4225C11.5999 15.5788 11.388 15.6666 11.1669 15.6666H2.83362C2.6126 15.6666 2.40064 15.5788 2.24436 15.4225C2.08808 15.2663 2.00028 15.0543 2.00028 14.8333V12.3333C2.00028 12.1123 1.91249 11.9003 1.75621 11.744C1.59993 11.5878 1.38797 11.5 1.16695 11.5C0.945938 11.5 0.733976 11.5878 0.577696 11.744C0.421416 11.9003 0.333618 12.1123 0.333618 12.3333V14.8333C0.333618 15.4963 0.59701 16.1322 1.06585 16.6011C1.53469 17.0699 2.17058 17.3333 2.83362 17.3333H11.1669C11.83 17.3333 12.4659 17.0699 12.9347 16.6011C13.4036 16.1322 13.6669 15.4963 13.6669 14.8333V3.16663C13.6669 2.50358 13.4036 1.8677 12.9347 1.39886C12.4659 0.930018 11.83 0.666626 11.1669 0.666626Z" fill="currentColor"></path>
                                    </svg>
                                </span>
                                <button type="submit" name="logout" class="font-semibold">Logout</button>
                            </a>
                        </form>
                    </div>
                </div>
            </nav>
        </div>
    </div>
    
    <!--View Reason Form-->
    <div id="viewReasonFormContainer" class="hidden">
        <div class="fixed inset-0 z-10 flex items-center justify-center bg-black bg-opacity-50">
            <form id="viewReasonForm" class="bg-white p-6 rounded-md">
                <div class="mt-2">
                    <textarea readonly class="h-72 w-96 resize-none border rounded-md p-2" name="reason" id="reason"></textarea>
                </div>
                <div>
                    <button type="button" name="viewReasonBackButton" id="viewReasonBackButton" class="mt-5 flex w-full justify-center rounded-md bg-gray-300 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-gray-400 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                        Back
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!--Table for Returns-->
    <!--Version 1 dapat makita ang data sa accused name complainant name ug pwede niya ma download-->
    <div class="py-20 ml-40 mr-5 pl-44">
        <table class="mt-5 table-auto w-full border-b border-gray-900 bg-gray-600 shadow-2xl rounded-lg">
            <thead class="border-b-2 border-gray-900">
                <tr>
                    <th class="pl-6 py-6">
                        <p class="flex items-center text-xs text-white">Case ID</p>
                    </th>
                    <th>
                        <p class="flex items-center text-xs text-white">Version</p>
                    </th>
                    <th>
                        <p class="flex items-center text-xs text-white">To Employee</p>
                    </th>
                    <th>
                        <p class="flex items-center text-xs text-white">From Employee</p>
                    </th>
                    <th>
                        <p class="flex items-center text-xs text-white">File</p>
                    </th>
                    <th>
                        <p class="flex items-center text-xs text-white">Reason of Return</p>
                    </th>
                    <!--<th>
                        <p class="flex items-center text-xs text-white">Case File</p>
                    </th>-->
                    
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($returnsSqlResult as $returns) :
                ?>
                    <tr class="text-xs bg-blue-50 border-b border-gray-100 edit-trigger">
                        <td class="hidden"><?= $returns['id']; ?></td>
                        <td class="pl-6 py-6 bg-blue-100"><?= $returns['CaseId']; ?></td>
                        <td class="pl-6 py-6"><?= $returns['Version']; ?></td>
                        <td class="pl-6 py-6 bg-blue-100"><?= $returns['ToEmployee']; ?></td>
                        <td class="pl-6 py-6 "><?= $returns['FromEmployee']; ?></td>
                        <td class="hidden"><?= $returns['Reason']; ?></td>
                        <td class="pl-6 py-6 bg-blue-100">
                            <a href="../../controller/admin_casesReturnsController.php?ViewFile=<?= $returns['id']; ?>"> View
                            </a>
                        </td>
                        <td class="pl-6 py-6"> <!-- View Reason of return-->
                            <button class="viewReason-button">
                                View
                            </button>
                        </td>
                        <td class="pl-6 py-6 bg-blue-100"> <!--Accept Button to accept the return of cases-->
                            <a href="../../controller/admin_casesReturnsController.php?ReturnAccept=<?= $returns['id']; ?>"> Accept
                            </a>
                        </td>
                        <td class="pl-6 py-6"> <!--Decline Button to decline the return of cases-->
                            <a href="../../controller/admin_casesReturnsController.php?ReturnDecline=<?= $returns['id']; ?>"> Decline
                            </a>
                        </td>

                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>

</html>

<script src="../../javascript/admin_casesReturns.js"></script>